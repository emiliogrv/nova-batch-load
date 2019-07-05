<template>
  <div class="card overflow-hidden">
    <div class="text-center text-primary my-4">
      <a
        v-text="__('Load per file')"
        :class="{ 'font-bold': !tabActive }"
        class="inline-block cursor-pointer mx-2"
        @click="tabActive = 0"
      />

      <a
        v-text="__('Manual loading')"
        :class="{ 'font-bold': tabActive }"
        class="inline-block cursor-pointer mx-2"
        @click="tabActive = 1"
      />
    </div>

    <div v-show="!tabActive">
      <ImporterField
        :field="field"
        :resource-name="resourceName"
      />
    </div>

    <div
      v-cloak
      v-show="tabActive"
      id="form-wrapper"
    />
  </div>
</template>

<script>
import ImporterField from './ImporterField'

export default {
  components: { ImporterField },

  props: ['field', 'resourceName'],

  data() {
    return { tabActive: this.field.defaultTabActive }
  }
}
</script>

