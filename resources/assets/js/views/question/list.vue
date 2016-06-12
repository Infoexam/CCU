<template>
  <section style="position: relative;">
    <h3 style="display: inline-block;">{{ exam.name }}</h3>

    <a
      v-link="{ name: 'admin.exams.questions.create', params: { name: exam.name }}"
      class="waves-effect waves-light btn green"
      style="position: absolute; right: 0; bottom: 1.168rem;"
    ><i class="material-icons">add</i></a>
  </section>

  <section>
    <table class="bordered highlight centered">
      <thead>
        <tr>
          <th>題目代號</th>
          <th>編輯</th>
        </tr>
      </thead>

      <tbody>
        <tr v-for="question in exam.questions">
          <td>
            <a
              v-link="{ name: 'admin.exams.questions.show', params: { name: exam.name, uuid: question.uuid }}"
            >{{ question.uuid }}</a>
          </td>
          <td>
            <action-button
              :edit="{ name: 'admin.exams.questions.edit', params: { name: exam.name, uuid: question.uuid }}"
              :destroy="question"
            ></action-button>
          </td>
        </tr>
      </tbody>
    </table>
  </section>
</template>

<script type="text/babel">
  import ActionButton from '../../components/actionButton.vue'
  import Toast from '../../components/toast'

  export default {
    route: {
      data (transition) {
        return this.$http.get(`exams/${this.$route.params.name}/questions`).then(response => {
          return {
            exam: response.data.exam
          }
        })
      }
    },

    data () {
      return {
        exam: {}
      }
    },

    methods: {
      destroy (question) {
        this.$http.delete(`exams/${this.$route.params.name}/questions/${question.uuid}`).then(response => {
          this.exam.questions.$remove(question)

          Toast.success('刪除成功')
        }, response => {
          Toast.failed('刪除失敗')
        })
      }
    },

    components: {
      ActionButton
    }
  }
</script>
