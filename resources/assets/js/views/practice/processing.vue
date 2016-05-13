<style lang="sass">
  .exam-practice-should-disable-select {
    user-select: none;
  }
</style>

<template>
  <div class="row exam-practice-should-disable-select">
    <div v-if="submitted" class="col s12 center">
      <span>本次測驗共 {{ statistics.total }} 題</span>
      <span>正確 {{ statistics.correct }} 題</span>
      <span>錯誤 {{ statistics.total - statistics.blank - statistics.correct }} 題</span>
      <span>未作答 {{ statistics.blank }} 題</span>
    </div>

    <form @submit.prevent="submit()" class="col s12">
      <template v-for="question in questions">
        <div class="row">
          <div class="col s12">
            <h4>
              <available-icon
                v-if="submitted"
                :available.once="question.correct"
              ></available-icon>

              <span>第 {{* counter++ }} 題</span>
            </h4>
          </div>

          <!-- 題目 -->
          <markdown
            :model="question.content"
            class="col s12 m6"
          ></markdown>

          <!-- 解析 -->
          <markdown
            v-if="submitted"
            :model="question.explanation || ''"
            class="col m6 hide-on-small-only"
          ></markdown>

          <!-- 選項 -->
          <form-option
            :option="question.options"
            :multiple="question.multiple"
            :submitted="submitted"
            class="col s12"
          ></form-option>
        </div>
      </template>

      <div v-if="! submitted" class="row">
        <submit :text="'送出'"></submit>
      </div>
    </form>
  </div>
</template>

<script type="text/babel">
  import AvailableIcon from '../../components/icon/available.vue'
  import FormOption from './form/option.vue'
  import Markdown from '../../components/markdown.vue'
  import Md5 from 'md5'
  import Submit from '../../components/form/submit.vue'

  export default {
    data () {
      return {
        counter: 1,

        questions: [],

        submitted: false,

        statistics: {
          total: 0,
          correct: 0,
          blank: 0
        }
      }
    },

    methods: {
      preprocess (questions) {
        for (const question of questions) {
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

        this.statistics.total = this.counter - 1

        this.judge(this.questions)

        this.submitted = true
      },

      judge (questions) {
        for (const question of questions) {
          let blank = true
          let correct = true

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
      availableIcon: AvailableIcon,
      formOption: FormOption,
      markdown: Markdown,
      submit: Submit
    },

    created () {
      this.$http.get(`practice/${this.$route.params.id}/processing`).then(response => {
        this.preprocess(response.data.exam.questions)
      })
    }
  }
</script>
