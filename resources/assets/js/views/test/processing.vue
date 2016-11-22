<style lang="scss" scoped>
  .question-content {
    margin-top: .65rem;
    margin-bottom: .4rem;
  }
</style>

<template>
  <div class="flex-column middle-xs full-width user-select-none" :class="{'center-xs': submitted}">
    <div v-if="submitted">
      <p>考試結束，請務必登出及關閉電腦</p>
    </div>

    <template v-else>
      <div v-if="-1 !== remaining" class="center" style="position: fixed; right: 2rem; top: 5rem; z-index: 1;">
        <span>剩餘時間</span>
        <br>
        <span :class="{'red-text': remaining < 300}">{{ remainingHuman }}</span>
      </div>

      <section class="flex center-xs middle-xs">
        <h4>{{ $route.params.code }} 考試</h4>
      </section>

      <loader v-if="$loadingRouteData"></loader>

      <template v-else>
        <form @submit.prevent="confirm()" class="full-width">
          <section
            v-for="question in questions"
            v-show="currentPage === Math.ceil(($index + 1) / perPage)"
            class="card"
          >
            <div class="card-content">
              <div class="card-title">
                <span>{{ $t('form.question', { num: $index + 1 }) }}</span>

                <span
                  v-if="question.multiple"
                  style="font-size: 0.75rem; vertical-align: middle;"
                >({{ $t('practice.multiple') }})</span>
              </div>

              <markdown
                :model="question.content"
                class="question-content"
              ></markdown>
            </div>

            <div class="card-action">
              <form-option
                :uuid="question.uuid"
                :option="question.options"
                :multiple="question.multiple"
              ></form-option>
            </div>
          </section>

          <pagination :current.sync="currentPage" :total="totalPage"></pagination>

          <submit></submit>
        </form>
      </template>

      <div id="submit-confirm" class="modal">
        <div class="modal-content left-align">
          <h5>確定要送出答案？</h5>
          <p>一經送出後及無法更改，請確認是否所有題目皆完成作答。</p>
        </div>
        <div class="modal-footer">
          <a
            @click="submit"
            class="modal-action modal-close waves-effect btn-flat red darken-1"
          >確定</a>
          <a class="modal-action modal-close waves-effect btn-flat">取消</a>
        </div>
      </div>
    </template>
  </div>
</template>

<script>
  import FormOption from './form/option.vue'
  import LeftPad from 'left-pad'
  import Loader from '~/components/loader.vue'
  import Markdown from '~/components/markdown.vue'
  import Md5 from 'md5'
  import Pagination from './components/pagination.vue'
  import Submit from '~/components/form/submit.vue'
  import Toast from '~/components/toast'

  export default {
    route: {
      canActivate (transition) {
        if (transition.to.router.app.$auth.guest()) {
          transition.redirect({ name: 'signIn' })
        }

        transition.next()
      },

      data (transition) {
        return this.$http.get(`tests/${this.$route.params.code}`).then(response => {
          this.preprocess(response.data.questions)
        }, response => {
          if (409 === response.status) {
            Toast.failed('重複登入，請向監考官反應')
          } else if (404 === response.status) {
            Toast.failed('此考試不存在，如有疑問，請向監考官反應')
          } else {
            console.log(response)
          }

          this.$router.replace({ name: 'test' })
        })
      }
    },

    data () {
      return {
        questions: [],

        total: 0,

        perPage: 10,
        currentPage: 1,

        submitted: false,

        remaining: -1,

        ids: {
          timing: -1,
          countdown: -1,
          blur: 0
        }
      }
    },

    computed: {
      remainingHuman () {
        let time = this.remaining

        const hours = Math.floor(time / 3600)

        time -= hours * 3600

        const minutes = Math.floor(time / 60)

        time -= minutes * 60

        return `${LeftPad(hours, 2, '0')} 時 ${LeftPad(minutes, 2, '0')} 分 ${LeftPad(time, 2, '0')} 秒`
      },

      totalPage () {
        return Math.ceil(this.total / this.perPage)
      }
    },

    methods: {
      preprocess (questions) {
        for (const question of questions) {
          for (const option of question.options) {
            option.hash = Md5(option.id)
          }
        }

        this.total = questions.length

        this.questions = questions
      },

      confirm () {
        $(`#submit-confirm`).modal().modal('open')
      },

      submit () {
        this.$http.post(`tests`, new FormData(document.querySelector('form'))).then(response => {
          this.submitted = true

          this.removeListener()
        }, response => {
          //
        })
      },

      timing () {
        this.$http.get(`tests/${this.$route.params.code}/timing`).then(response => {
          const r = this.remaining

          this.remaining = response.data

          if (-1 === r) {
            this.ids.countdown = window.setInterval(this.countdown, 1000)
          }
        })
      },

      countdown () {
        --this.remaining

        if (0 === this.remaining) {
          window.clearInterval(this.ids.timing)
          window.clearInterval(this.ids.countdown)

          this.ids.timing = this.ids.countdown = -1

          this.submit()
        }
      },

      blur () {
        this.submit()
      },

      removeListener () {
        if (-1 !== this.ids.timing) {
          window.clearInterval(this.ids.timing)
        }

        if (-1 !== this.ids.countdown) {
          window.clearInterval(this.ids.countdown)
        }

        if (-1 !== this.ids.blur) {
          window.removeEventListener('blur', this.blur)

          this.ids.blur = -1
        }

        document.oncontextmenu = null
      }
    },

    components: {
      FormOption,
      Loader,
      Markdown,
      Pagination,
      Submit
    },

    ready () {
      this.timing()

      this.ids.timing = window.setInterval(this.timing, 5000)

      window.addEventListener('blur', this.blur)

      document.oncontextmenu = () => false
    },

    beforeDestroy () {
      this.removeListener()
    }
  }
</script>
