<template>
  <section>
    <section class="row middle-xs">
      <h3 class="col-xs-5">{{ exam.name }}</h3>

      <div class="col-xs end-xs">
        <div class="file-field input-field inline-flex">
          <div class="btn orange" style="height: 36px; line-height: 36px;">
            <span>匯入</span>
            <input v-el:file @change="upload()" type="file" style="height: 36px;">
          </div>

          <div class="file-path-wrapper" style="visibility: hidden; width: 0; height: 0; padding: 0;">
            <input class="file-path validate" type="text">
          </div>
        </div>

        <a
          v-link="{ name: 'admin.exams.questions.create', params: { name: exam.name }}"
          class="waves-effect waves-light btn green inline-flex"
          style="margin-top: -3px;"
        ><i class="material-icons">add</i></a>
      </div>
    </section>

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
              @click="$broadcast('show-question', $route.params.name, question.uuid)"
              class="cursor-pointer"
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

    <show></show>
  </section>
</template>

<script>
  import ActionButton from '~/components/actionButton.vue'
  import Show from './components/show.vue'
  import Toast from '~/components/toast'

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
          if ('nonEmptyPaper' === response.data.message) {
            Toast.failed('該題目已用於試卷中，無法刪除')
          } else {
            Toast.failed('刪除失敗')
          }
        })
      }
    },

    components: {
      ActionButton,
      Show
    }
  }
</script>
