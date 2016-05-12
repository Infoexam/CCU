<template>
  <template v-if="available">
    <h3>題目</h3>

    <blockquote>
      <markdown :model="question.content"></markdown>
    </blockquote>

    <template v-for="option in question.options">
      <h3>選項 {{ $index + 1 }}</h3>

      <blockquote>
        <markdown :model="option.content"></markdown>
      </blockquote>
    </template>
  </template>
</template>

<script type="text/babel">
  import markdown from '../../components/markdown.vue'

  export default {
    data () {
      return {
        question: {}
      }
    },

    computed: {
      available () {
        return 0 < Object.keys(this.question).length
      }
    },

    methods: {
    },

    components: {
      markdown
    },

    created () {
      this.$http.get(`exams/${this.$route.params.id}/questions/${this.$route.params.uuid}`).then(response => {
        this.question = response.data.question
      })
    }
  }
</script>
