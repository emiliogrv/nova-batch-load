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

  mounted() {
    this.$nextTick(this.init)
  },

  methods: {
    init() {
      if (!document.getElementById('field-wrapper')) {
        const batchField = document.getElementById(this.field.attribute)

        const form = document.getElementsByTagName('form')[0]
        const parentForm = form.parentNode

        parentForm.setAttribute('id', 'field-wrapper')

        form.removeChild(batchField.parentNode)

        parentForm.removeChild(form)

        this.$nextTick(() => {
          document
            .getElementById('field-wrapper')
            .appendChild(batchField.parentNode)

          this.$nextTick(() =>
            document.getElementById('form-wrapper').appendChild(form)
          )
        })
      } else {
        location.reload()
      }
    }
  }
}
</script>
