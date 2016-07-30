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
            <a @click="show(question.uuid)" class="cursor-pointer">{{ question.uuid }}</a>
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

    <div id="question-modal" class="modal modal-fixed-footer" style="max-height: 80%; height: 80%; width: 80%;">
      <div class="modal-content">
        <div v-if="loading" class="row middle-xs center-xs" style="height: 100%;">
          <div class="col-xs">
            <div class="preloader-wrapper big active">
              <div class="spinner-layer spinner-blue-only">
                <div class="circle-clipper left"><div class="circle"></div></div>
                <div class="gap-patch"><div class="circle"></div></div>
                <div class="circle-clipper right"><div class="circle"></div></div>
              </div>
            </div>
          </div>
        </div>

        <template v-else>
          <h4 style="display: inline-block;">{{ question.uuid }}</h4>

          <span class="grey-text text-darken-1" style="display: inline-block; margin-left: .5rem;">
            <span>{{ $t('question.difficulty.' + question.difficulty.name) }}</span>
            <span> / </span>
            <span>{{ question.multiple ? '多選' : '單選' }}</span>
          </span>

          <hr>

          <p>題目</p>

          <blockquote style="margin-left: 1rem;">
            <markdown :model="question.content"></markdown>
          </blockquote>

          <template v-for="option in question.options">
            <p>選項 {{ $index + 1 }}</p>

            <blockquote style="margin-left: 1rem;">
              <markdown :model="option.content"></markdown>
            </blockquote>
          </template>

          <p>解析</p>

          <blockquote style="margin-left: 1rem;">
            <markdown :model="question.explanation || '無'"></markdown>
          </blockquote>
        </template>
      </div>

      <div class="modal-footer">
        <a class="modal-action modal-close waves-effect waves-green btn-flat">關閉</a>
      </div>
    </div>
  </section>
</template>

<script>
  import ActionButton from '~/components/actionButton.vue'
  import Markdown from '~/components/markdown.vue'
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
        exam: {},

        question: {
          uuid: '',
          content: '',
          options: []
        },

        loading: true
      }
    },

    methods: {
      show (uuid) {
        this.loading = true

        const cache = {
          css: Object.assign({}, document.body.style),
          scrollY: window.scrollY
        }

        $('#question-modal').openModal({
          out_duration: 0,

          ready () {
            document.body.style.top = -cache.scrollY + 'px'
            document.body.style.position = 'fixed'
          },

          complete () {
            Object.assign(document.body.style, cache.css)

            window.scrollTo(0, cache.scrollY)
          }
        })

        this.$http.get(`exams/${this.$route.params.name}/questions/${uuid}`).then(response => {
          this.question = response.data.question

          this.loading = false
        })
      },

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
      ActionButton,
      Markdown
    }
  }
</script>
