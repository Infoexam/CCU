<template>
  <section style="position: relative;">
    <h3 style="display: inline-block;">{{ exam.name }}</h3>

    <div style="position: absolute; right: 0; bottom: 1.168rem;">
      <div style="display: inline-flex;">
        <div class="file-field input-field">
          <div class="btn orange" style="height: 36px; line-height: 36px;">
            <span>匯入</span>
            <input v-el:file @change="upload()" type="file" style="height: 36px;">
          </div>

          <div class="file-path-wrapper" style="visibility: hidden; width: 0; height: 0; padding: 0;">
            <input class="file-path validate" type="text">
          </div>
        </div>
      </div>

      <a
        v-link="{ name: 'admin.exams.questions.create', params: { name: exam.name }}"
        class="waves-effect waves-light btn green"
        style="display: inline-flex; margin-top: -21px;"
      ><i class="material-icons">add</i></a>
    </div>
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
      upload () {
        if (1 === this.$els.file.files.length) {
          const data = new FormData()

          data.append('file', this.$els.file.files[0])

          this.$http.post(`exams/${this.$route.params.name}/questions/import`, data).then(response => {
            for (const item of response.data) {
              this.exam.questions.push(item)
            }

            Toast.success('匯入成功')
          }, response => {
            Toast.formRequestFailed(response)
          })

          this.$els.file.value = ''
        }
      },

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
