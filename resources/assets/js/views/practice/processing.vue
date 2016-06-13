<template>
  <section class="center">
    <h3>{{ $route.params.name }} {{ $t('practice.heading') }}</h3>
  </section>

  <section v-if="submitted" class="center">
    <a v-link="{ name: 'practice' }">{{ $t('practice.back') }}</a>
    <span>{{ $t('practice.statistics.total', { num: statistics.total }) }}</span>
    <span>{{ $t('practice.statistics.correct', { num: statistics.correct }) }}</span>
    <span>{{ $t('practice.statistics.incorrect', { num: statistics.total - statistics.blank - statistics.correct }) }}</span>
    <span>{{ $t('practice.statistics.blank', { num: statistics.blank }) }}</span>
  </section>

  <section>
    <form @submit.prevent="submit()" class="user-select-none">
      <template v-for="question in questions">
        <section
          v-show="currentPage === Math.ceil(($index + 1) / perPage)"
          class="card"
          :style="{ borderLeft: submitted ? (question.correct ? '5px solid #4caf50' : '5px solid #f44336') : 'none' }"
        >
          <div class="card-content">
            <div class="card-title">
              <span>
                <available-icon
                  v-if="submitted"
                  :available.once="question.correct"
                ></available-icon>
              </span>

              <span>{{ $t('form.question', { num: $index + 1 }) }}</span>

              <span>
                <star-icon
                  :total="3"
                  :active="['easy', 'middle', 'hard'].indexOf(question.difficulty.name) + 1"
                ></star-icon>
              </span>
            </div>

            <div class="row" style="margin-top: 10px; margin-bottom: 5px;">
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
          </div>

          <div class="card-action">
            <form-option
              :option="question.options"
              :multiple="question.multiple"
              :submitted="submitted"
            ></form-option>
          </div>
        </section>
      </template>

      <pagination :current.sync="currentPage" :total="totalPage"></pagination>

      <submit v-if="! submitted"></submit>
    </form>
  </section>
</template>

<script type="text/babel">
  import AvailableIcon from '../../components/icon/available.vue'
  import FormOption from './form/option.vue'
  import Markdown from '../../components/markdown.vue'
  import Md5 from 'md5'
  import Pagination from './components/pagination.vue'
  import StarIcon from './components/star.vue'
  import Submit from '../../components/form/submit.vue'

  export default {
    route: {
      canActivate (transition) {
        const auth = transition.to.router.app.$auth

        if (auth.guest()) {
          transition.redirect({ name: 'signIn' })
        }

        transition.next()
      },

      data (transition) {
        return this.$http.get(`practice/${this.$route.params.name}/processing`).then(response => {
          this.preprocess(response.data.exam.questions)
        })
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

    computed: {
      totalPage () {
        return Math.ceil(this.statistics.total / this.perPage)
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

        this.judge()

        this.submitted = true

        window.scrollTo(0, window.scrollX)
      },

      judge () {
        for (const question of this.questions) {
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
        }
      }
    },

    components: {
      AvailableIcon,
      FormOption,
      Markdown,
      Pagination,
      StarIcon,
      Submit
    }
  }
</script>
