<template>
  <div style="margin-top: .5rem; margin-bottom: -1rem;">
    <div v-for="o in option">
      <input
        :name="uuid"
        :type="multiple ? 'checkbox' : 'radio'"
        :id="o.hash"
        :value="o.id"
      ><label :for="o.hash">{{ $t('form.option') }} {{ $index + 1 }}</label>

      <markdown :model="o.content" class="practice-option-content"></markdown>
    </div>
  </div>
</template>

<script>
  import Markdown from '~/components/markdown.vue'

  export default {
    props: {
      uuid: {
        type: String,
        required: true
      },

      option: {
        type: Array,
        required: true
      },

      multiple: {
        type: Boolean,
        required: true
      }
    },

    components: {
      Markdown
    }
  }
</script>
