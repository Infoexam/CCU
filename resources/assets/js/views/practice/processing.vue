<template>
  <section class="full-width user-select-none">
    <section class="center">
      <a
        v-link="{ name: 'practice' }"
        class="btn-floating btn-large waves-effect waves-light left"
      ><i class="material-icons">reply</i></a>

      <h3>{{ $route.params.name }} {{ $t('practice.heading') }}</h3>
    </section>

    <div v-if="submitted" class="row practice-result">
      <div class="col s12 m4">
        <div class="card-panel green center">
          <i class="material-icons">panorama_fish_eye</i>
          <p>{{ $t('practice.statistics.correct') }}</p>
          <h1>{{ statistics.correct }}</h1>
        </div>
      </div>

      <div class="col s12 m4">
        <div class="card-panel red darken-3 center">
          <i class="material-icons">clear</i>
          <p>{{ $t('practice.statistics.incorrect') }}</p>
          <h1>{{ statistics.total - statistics.blank - statistics.correct }}</h1>
        </div>
      </div>

      <div class="col s12 m4">
        <div class="card-panel light-blue center">
          <p>{{ $t('practice.statistics.blank') }}</p>
          <h3>{{ statistics.blank }}</h3>
          <hr>
          <p>{{ $t('practice.statistics.total') }}</p>
          <h3>{{ statistics.total }}</h3>
        </div>
      </div>
    </div>

    <form @submit.prevent="submit()">
      <section
        v-for="question in questions"
        v-show="currentPage === Math.ceil(($index + 1) / perPage)"
        class="card"
        :style="{ borderLeft: submitted ? (question.correct ? '5px solid #4caf50' : '5px solid #f44336') : 'none' }"
      >
        <div class="card-content">
          <div class="card-title">
            <available-icon
              v-if="submitted"
              :available.once="question.correct"
            ></available-icon>

            <span>{{ $t('form.question', { num: $index + 1 }) }}</span>

            <star-icon
              :total="3"
              :active="['easy', 'middle', 'hard'].indexOf(question.difficulty.name) + 1"
            ></star-icon>

            <span
              v-if="question.multiple"
              style="font-size: 0.75rem; vertical-align: middle;"
            >({{ $t('practice.multiple') }})</span>

            <span
              v-if="question.explanation && submitted"
              :class="{ 'activator': question.explanation }"
              class="yellow-text text-darken-3 right cursor-pointer"
            ><i class="material-icons tiny">info</i> {{ $t('practice.explanation') }}</span>
          </div>

          <markdown
            :model="question.content"
            style="margin-top: .65rem; margin-bottom: .4rem;"
          ></markdown>
        </div>

        <div v-if="question.explanation" class="card-reveal">
          <span class="card-title">{{ $t('practice.explanation') }}<i class="material-icons right">close</i></span>

          <markdown
            :model="question.explanation"
          ></markdown>
        </div>

        <div class="card-action">
          <form-option
            :option="question.options"
            :multiple="question.multiple"
            :submitted="submitted"
          ></form-option>
        </div>
      </section>

      <pagination :current.sync="currentPage" :total="totalPage"></pagination>

      <submit v-if="! submitted"></submit>
    </form>
  </section>
</template>

<script>
  import AvailableIcon from '~/components/icon/available.vue'
  import FormOption from './form/option.vue'
  import Markdown from '~/components/markdown.vue'
  import Md5 from 'md5'
  import Pagination from './components/pagination.vue'
  import StarIcon from './components/star.vue'
  import Submit from '~/components/form/submit.vue'

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

        window.scrollTo(window.scrollX, 0)
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
    },

    ready () {
      document.title += ` - ${this.$route.params.name}`
    }
  }
</script>
