<template>
  <div class="input-field">
    <markdown
      :model.sync="explanation"
      :length="5000"
      :label="'解析'"
    ></markdown>
  </div>
</template>

<script>
  import Markdown from '~/components/form/markdown.vue'

  export default {
    props: {
      explanation: {
        twoWay: true,
        type: String,
        required: true
      }
    },

    components: {
      markdown: Markdown
    }
  }
</script>
