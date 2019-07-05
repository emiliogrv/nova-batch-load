<template>
  <loading-view :loading="loading">
    <div class="text-center my-4">
      <ImportXLS
        :accept="field.accept"
        @on-selected="onSelected"
      />
    </div>

    <template v-if="dataAvailable">
      <div>
        <component
          :errors="validationErrors"
          :is="'form-text-field'"
          :field="dataField"
        />
      </div>

      <!-- Fields -->
      <div
        v-for="(field, indexRF) in fields"
        :key="indexRF"
      >
        <component
          :errors="validationErrors"
          :is="'form-' + field.component"
          :resource-name="resourceName"
          :field="field"
          :via-resource="viaResource"
          :via-resource-id="viaResourceId"
          :via-relationship="viaRelationship"
        />
      </div>

      <!-- Create Button -->
      <div class="bg-30 flex items-center px-8 py-4">
        <a
          @click="$root.$router.back()"
          class="btn btn-link dim cursor-pointer text-80 ml-auto mr-6"
        >
          {{ __('Cancel') }}
        </a>

        <progress-button
          class="mr-3"
          dusk="create-and-add-another-button"
          @click.native="createAndAddAnother"
          :disabled="isWorking"
          :processing="submittedViaCreateAndAddAnother"
        >
          {{ __('Upload file & Add Another') }}
        </progress-button>

        <progress-button
          dusk="create-button"
          @click.native="createResource"
          :disabled="isWorking"
          :processing="submittedViaCreateResource"
        >
          {{ __('Upload file') }}
        </progress-button>
      </div>
    </template>
  </loading-view>
</template>

<script>
import Create from '@nova/views/Create'
import ImportXLS from './ImportXLS'

export default {
  mixins: [Create],

  components: { ImportXLS },

  props: ['field'],

  data: () => ({
    dataField: {
      attribute: 'data',
      component: 'text-field',
      extraAttributes: { type: 'hidden' },
      indexName: '',
      name: '',
      nullable: false,
      panel: null,
      prefixComponent: true,
      readonly: false,
      sortable: true,
      textAlign: 'left',
      value: null
    },
    fileData: [],
    fields: [],
    selectOptions: []
  }),

  computed: {
    dataAvailable() {
      return !!this.fileData.length && !!this.selectOptions.length
    }
  },

  methods: {
    onSelected({ header, body }) {
      this.selectOptions = []

      header.forEach(item =>
        this.selectOptions.push({ value: item, label: item })
      )

      this.fileData = body

      this.fields.forEach(field => (field.options = this.selectOptions))

      if (!this.dataAvailable) {
        this.$toasted.error(this.__('File empty!'))
      }
    },

    keepField(field) {
      return new RegExp(this.field.keepOriginalFields).test(field)
    },

    /**
     * Get the available fields for the resource.
     */
    async getFields() {
      this.loading = true
      this.fields = []
      this.fileData = []
      this.selectOptions = []

      const { data: fields } = await Nova.request().get(
        `/nova-api/${this.resourceName}/creation-fields`,
        {
          params: {
            editing: true,
            editMode: 'create',
            viaResource: this.viaResource,
            viaResourceId: this.viaResourceId,
            viaRelationship: this.viaRelationship
          }
        }
      )

      fields
        .filter(field => field.component !== 'batch-load-field')
        .forEach(field => {
          if (!this.keepField(field.component)) {
            field.component = 'select-field'
            field.options = []
          }

          this.fields.push(field)
        })

      this.loading = false
    },

    /**
     * Send a create request for this resource
     */
    createRequest() {
      return new Promise((resolve, reject) => {
        Nova.request()
          .post(
            `/nova-vendor/nova-batch-load/${this.resourceName}`,
            this.createResourceFormData()
          )
          .then(resolve)
          .catch(error => {
            let text

            if (error.response.status == 422) {
              if (!error.response.data.errors.data) {
                _.each(this.fields, field => {
                  text = _.find(error.response.data.errors, (e, i) =>
                    new RegExp(field.attribute).test(i)
                  )

                  if (text) {
                    error.response.data.errors[field.attribute] = text
                  }
                })
              }
            }

            reject(error)
          })
      })
    },

    /**
     * Create the form data for creating the resource.
     */
    createResourceFormData() {
      const FD = new FormData()
      let key
      let fromField = false

      return _.tap(new FormData(), formData => {
        _.each(this.fields, field => {
          fromField = false

          field.fill(FD)
          key = FD.get(field.attribute)

          fromField = this.keepField(field.component)

          _.each(this.fileData, (row, index) => {
            formData.append(
              `data[${index}][${field.attribute}]`,
              fromField ? key : row[key]
            )
          })
        })

        formData.append('viaResource', this.viaResource)
        formData.append('viaResourceId', this.viaResourceId)
        formData.append('viaRelationship', this.viaRelationship)
      })
    }
  }
}
</script>

