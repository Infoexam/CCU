<style lang="sass">
  .exam-practice-should-disable-select {
    user-select: none;
  }

  .exam-practice-icon-vertical-middle {
    vertical-align: middle;
  }
</style>

<template>
    <header class="center">
      <h3>{{ $route.params.name }} 題庫練習</h3>
    </header>

    <article v-if="submitted" class="center">
      <a v-link="{ name: 'practice' }">回練習頁面</a>
      <span>{{ $t('practice.statistics.total', { num: statistics.total }) }}</span>
      <span>{{ $t('practice.statistics.correct', { num: statistics.correct }) }}</span>
      <span>{{ $t('practice.statistics.incorrect', { num: statistics.total - statistics.blank - statistics.correct }) }}</span>
      <span>{{ $t('practice.statistics.blank', { num: statistics.blank }) }}</span>
    </article>

    <form @submit.prevent="submit()" class="exam-practice-should-disable-select">
      <template v-for="question in questions">
        <article class="card" v-show="currentPage === Math.ceil(($index + 1) / perPage)">
          <section class="card-content">
            <div class="card-title">
              <span class="exam-practice-icon-vertical-middle">
                <available-icon
                  v-if="submitted"
                  :available.once="question.correct"
                ></available-icon>
              </span>

              <span class="exam-practice-icon-vertical-middle">
                <star-icon
                  :total="3"
                  :active="['easy', 'middle', 'hard'].indexOf(question.difficulty.name) + 1"
                ></star-icon>
              </span>

              <span>第 {{ $index + 1 }} 題</span>
            </div>

            <div class="row">
              <markdown
                :model="question.content"
                class="col s12 m6"
              ></markdown>

              <markdown
                v-if="submitted && question.explanation"
                :model="question.explanation"
                class="col m6 hide-on-small-only"
              ></markdown>
            </div>
          </section>

          <section class="card-action">
            <form-option
              :option="question.options"
              :multiple="question.multiple"
              :submitted="submitted"
            ></form-option>
          </section>
        </article>
      </template>

      <section class="center">
        <ul class="pagination">
          <template v-for="i in Math.ceil(this.statistics.total / this.perPage)">
            <li class="waves-effect">
              <a @click="currentPage = i + 1" class="cursor-pointer">{{ i + 1 }}</a>
            </li>
          </template>
        </ul>
      </section>

      <div v-if="! submitted" class="row">
        <submit :text="'送出'"></submit>
      </div>
    </form>
</template>

<script type="text/babel">
  import AvailableIcon from '../../components/icon/available.vue'
  import FormOption from './form/option.vue'
  import Markdown from '../../components/markdown.vue'
  import Md5 from 'md5'
  import StarIcon from '../../components/icon/star.vue'
  import Submit from '../../components/form/submit.vue'

  export default {
    route: {
      canActivate (transition) {
        const auth = transition.to.router.app.$auth

        if (auth.guest()) {
          transition.redirect({ name: 'signIn' })
        }

        transition.next()
      }
    },

    data () {
      return {
        questions: [],

        submitted: false,

        statistics: {
          total: 0,
          correct: 0,
          blank: 0
        },

        perPage: 10,
        currentPage: 1
      }
    },

    methods: {
      preprocess (questions) {
        for (const question of questions) {
          // Ensure the number of questions is not more than 50
          if (50 < ++this.statistics.total) {
            --this.statistics.total

            return
          }

          for (const option of question.options) {
            option.hash = Md5(option.id)

            option.check = false
          }

          question.correct = false

          const temp = JSON.parse(JSON.stringify(question))

          delete temp.questions

          this.questions.push(temp)

          if (question.hasOwnProperty('questions') && 0 < question.questions.length) {
            this.preprocess(question.questions)
          }
        }
      },

      submit () {
        if (this.submitted) {
          return
        }

        this.judge(this.questions)

        this.submitted = true

        window.scrollTo(0, window.scrollX)
      },

      judge (questions) {
        for (const question of questions) {
          let blank = true // 未作答
          let correct = true // 正確

          for (const option of question.options) {
            if (blank && false !== option.check) {
              blank = false
            }

            correct = correct && option.answer === option.check
          }

          if (blank) {
            ++this.statistics.blank
          } else if (correct) {
            ++this.statistics.correct

            question.correct = true
          }

          if (question.hasOwnProperty('questions') && 0 < question.questions.length) {
            this.judge(question.questions)
          }
        }
      }
    },

    components: {
      AvailableIcon,
      FormOption,
      Markdown,
      StarIcon,
      Submit
    },

    created () {
      this.$http.get(`practice/${this.$route.params.name}/processing`).then(response => {
        this.preprocess(response.data.exam.questions)
      })
    }
  }
</script>
