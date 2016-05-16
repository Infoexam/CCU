<template>
  <h2 style="display: inline">{{ exam.name }}</h2>

  <a
    v-link="{ name: 'admin.exams.questions.create', params: { id: exam.id }}"
    class="btn-floating btn-large waves-effect waves-light red right"
  ><i class="material-icons">add</i></a>

  <table class="bordered highlight">
    <thead>
      <tr>
        <th>UUID</th>
        <th>Difficulty</th>
      </tr>
    </thead>

    <tbody>
      <tr v-for="question in exam.questions">
        <td>
          <a v-link="{ name: 'admin.exams.questions.show', params: { id: exam.id, uuid: question.uuid }}">{{ question.uuid }}</a>
        </td>
        <td>{{ question.difficulty.name }}</td>
      </tr>
    </tbody>
  </table>
</template>

<script type="text/babel">
  export default {
    data () {
      return {
        exam: {}
      }
    },

    created () {
      this.$http.get(`exams/${this.$route.params.id}/questions`).then(response => {
        this.exam = response.data.exam
      })
    }
  }
</script>
