<template>
  <div class="row">
    <div v-for="o in option" class="col s12">
      <input
        :name="`question-${question.id}`"
        :type="question.multiple ? 'checkbox' : 'radio'"
        :id="id"
        class="with-gap"
      >
      <label :for="id">
        <markdown :model="o.content"></markdown>
      </label>
    </div>
  </div>
</template>

<script type="text/babel">
  import Markdown from '../../../components/markdown.vue'
  import Uuid from 'node-uuid'

  export default {
    props: {
      question: {
        required: true
      },

      option: {
        type: Array,
        required: true
      }
    },

    data () {
      return {
        id: Uuid.v4()
      }
    },

    components: {
      markdown: Markdown
    }
  }
</script>
