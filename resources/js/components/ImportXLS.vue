<template>
  <span class="form-file mr-4">
    <input
      :accept="accept"
      class="form-file-input select-none"
      id="file-select"
      type="file"
      @change="fileChange"
    />

    <label
      v-text="__('Choose File')"
      class="form-file-btn btn btn-default btn-primary select-none"
      for="file-select"
    />

    <span
      v-text="currentLabel"
      class="text-gray-50 select-none"
    />
  </span>
</template>

<script>
import XLSX from 'xlsx'

export default {
  props: {
    accept: {
      type: String,
      default: '.xlsx, .xls, .csv'
    }
  },

  data() {
    return {
      rawFile: null,
      fileName: '',
      workbook: null,
      tableData: {
        header: [],
        body: []
      },
      rABS: false
    }
  },

  computed: {
    currentLabel() {
      return this.fileName || this.__('no file selected')
    }
  },

  methods: {
    fileChange(event) {
      if (event.target.files[0]) {
        this.clearAllData()

        this.fileName = event.target.value.match(/[^\\/]*$/)[0]
        this.rawFile = event.target.files[0]

        this.fileConvert()
      }
    },

    fileConvert() {
      this.fileConvertToWorkbook(this.rawFile)
        .then(workbook => {
          let xlsxArr = XLSX.utils.sheet_to_json(
            workbook.Sheets[workbook.SheetNames[0]]
          )
          this.workbook = workbook
          this.emitData(this.xlsxArrToTableArr(xlsxArr))
        })
        .catch(err => {
          this.$toasted.error(err)
        })
    },

    fileConvertToWorkbook(file) {
      let reader = new FileReader()
      let fixdata = data => {
        let o = '',
          l = 0,
          w = 10240
        for (; l < data.byteLength / w; ++l) {
          o += String.fromCharCode.apply(
            null,
            new Uint8Array(data.slice(l * w, l * w + w))
          )
        }
        o += String.fromCharCode.apply(null, new Uint8Array(data.slice(l * w)))
        return o
      }

      return new Promise((resolve, reject) => {
        try {
          reader.onload = renderEvent => {
            let data = renderEvent.target.result
            if (this.rABS) {
              /* if binary string, read with type 'binary' */
              resolve(XLSX.read(data, { type: 'binary', raw: true }))
            } else {
              /* if array buffer, convert to base64 */
              let arr = fixdata(data)
              resolve(XLSX.read(btoa(arr), { type: 'base64', raw: true }))
            }
          }

          reader.onerror = error => {
            reject(error)
          }

          if (this.rABS) {
            reader.readAsBinaryString(file)
          } else {
            reader.readAsArrayBuffer(file)
          }
        } catch (error) {
          reject(error)
        }
      })
    },

    xlsxArrToTableArr(xlsxArr) {
      let tableArr = []
      let length = 0
      let maxLength = 0
      let maxLengthIndex = 0

      xlsxArr.forEach((item, index) => {
        length = Object.keys(item).length
        if (maxLength < length) {
          maxLength = length
          maxLengthIndex = index
        }
      })

      let tableHeader = Object.keys(xlsxArr[maxLengthIndex] || {})
      let rowItem = {}

      xlsxArr.forEach(item => {
        rowItem = {}
        for (let i = 0; i < maxLength; i++) {
          rowItem[tableHeader[i]] = item[tableHeader[i]] || ''
        }
        tableArr.push(rowItem)
      })

      return {
        header: tableHeader,
        data: tableArr
      }
    },

    emitData({ data, header }) {
      this.tableData.header = header
      this.tableData.body = data
      this.$emit('on-selected', this.tableData)
    },

    clearAllData() {
      this.tableData = {
        header: [],
        body: []
      }

      this.rawFile = null
      this.workbook = null
      this.fileName = null
    }
  }
}
</script>
