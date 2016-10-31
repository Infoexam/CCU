<style lang="scss" scoped>
  .collapsible p {
    padding: 0 1.5rem;
    margin: 1rem 0 !important;
  }
</style>

<template>
  <form @submit.prevent="store()">
    <ul class="collapsible" data-collapsible="expandable">
      <li v-for="exam in exams">
        <template v-if="exam.questions.length > 0">
          <div class="collapsible-header">{{ exam.name }}</div>

          <div class="collapsible-body">
            <p v-for="question in exam.questions">
              <input
                v-model="form.question"
                :id="question.uuid"
                :value="question.id"
                type="checkbox"
              >
              <label :for="question.uuid">{{ question.uuid }}</label>
            </p>
          </div>
        </template>
      </li>
    </ul>

    <submit></submit>
  </form>
</template>

<script>
  import Submit from '~/components/form/submit.vue'
  import Toast from '~/components/toast'

  export default {
    route: {
      data () {
        return Promise.all([
          this.$http.get(`papers/${this.$route.params.name}/questions/all`),
          this.$http.get(`papers/${this.$route.params.name}/questions/show`)
        ]).then(([exams, paper]) => {
          this.form.question = paper.data

          return {
            exams: exams.data.exams
          }
        })
      }
    },

    data () {
      return {
        exams: [],

        form: {
          question: []
        }
      }
    },

    methods: {
      store () {
        this.$http.post(`papers/${this.$route.params.name}/questions`, this.form).then(response => {
          this.$router.go({ name: 'admin.papers.questions', params: { name: this.$route.params.name }})
        }, response => {
          Toast.formRequestFailed(response)
        })
      }
    },

    components: {
      Submit
    },

    ready () {
      $('form .collapsible').collapsible()
    }
  }
</script>
