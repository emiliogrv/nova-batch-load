<template>
  <from-tab
    :id="field.attribute"
    :field="field"
    :resource-name="resourceName"
  />
</template>

<script>
import { FormField } from 'laravel-nova'
import FormTab from './FormTab'

export default {
  mixins: [FormField],

  components: { 'from-tab': FormTab },

  props: ['resourceName', 'resourceId', 'field'],

  data: () => ({
    h1: null
  }),

  mounted() {
    this.h1 = document.getElementsByTagName('h1')[0]

    this.$nextTick(this.init)
  },

  beforeDestroy() {
    this.h1.parentNode.removeChild(this.h1)
  },

  methods: {
    init() {
      const wrapper = document.getElementById('field-wrapper')

      if (!wrapper) {
        const batchField = document.getElementById(this.field.attribute)

        const form = document.getElementsByTagName('form')[0]
        const parentForm = form.parentNode

        parentForm.setAttribute('id', 'field-wrapper')

        batchField.parentNode.removeChild(batchField)

        parentForm.removeChild(form)

        this.$nextTick(() => {
          parentForm.appendChild(batchField)

          this.$nextTick(() => {
            document.getElementById('form-wrapper').appendChild(form)

            this.$nextTick(() => {
              form.firstChild.className = ''
              form.lastChild.className = 'bg-30 flex items-center px-8 py-4'

              parentForm.parentNode.prepend(this.h1)
            })
          })
        })
      } else {
        wrapper.parentNode.removeChild(wrapper)

        location.reload()
      }
    }
  }
}
</script>
