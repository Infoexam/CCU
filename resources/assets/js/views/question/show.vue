<template>
  <h4>題目</h4>

  <blockquote>
    <markdown :model="question.content"></markdown>
  </blockquote>

  <template v-for="option in question.options">
    <h5>選項 {{ $index + 1 }}</h5>

    <blockquote>
      <markdown :model="option.content"></markdown>
    </blockquote>
  </template>
</template>

<script type="text/babel">
  import Markdown from '../../components/markdown.vue'

  export default {
    route: {
      data (transition) {
        return this.$http.get(`exams/${this.$route.params.name}/questions/${this.$route.params.uuid}`).then(response => {
          return {
            question: response.data.question
          }
        })
      }
    },

    data () {
      return {
        question: {
          content: '',
          options: []
        }
      }
    },

    components: {
      Markdown
    }
  }
</script>
