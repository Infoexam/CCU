<style src="prismjs/themes/prism-okaidia.css"></style>

<template>
  <div>
    <vue-markdown
      :id="id"
      :source="model"
      :html="false"
      class="markdown-content"
    ></vue-markdown>
  </div>
</template>

<script>
  import Prism from 'prismjs'
  import Uuid from 'node-uuid'
  import VueMarkdown from 'vue-markdown'

  export default {
    props: {
      model: {
        type: String,
        required: true
      }
    },

    data () {
      return {
        id: 'markdown-' + Uuid.v4()
      }
    },

    events: {
      rendered () {
        for (const code of document.querySelectorAll(`#${this.id} code`)) {
          Prism.highlightElement(code)
        }

        for (const link of document.querySelectorAll(`#${this.id} a`)) {
          link.rel = 'noopener noreferrer'
          link.target = '_blank'
        }
      }
    },

    components: {
      VueMarkdown
    }
  }
</script>
