<template>
  <form @submit.prevent="update()" :id="formId">
    <form-component
      :question_id.sync="form.question.question_id"
      :uuid.sync="form.question.uuid"
      :content.sync="form.question.content"
      :difficulty_id.sync="form.question.difficulty_id"
      :multiple.sync="form.question.multiple"
      :option.sync="form.option"
      :explanation.sync="form.question.explanation"
    ></form-component>
  </form>
</template>

<script>
  import FormComponent from './components/form.vue'
  import Toast from '~/components/toast'
  import Uuid from 'node-uuid'

  export default {
    route: {
      data (transition) {
        return this.$http.get(`exams/${this.$route.params.name}/questions/${this.$route.params.uuid}`).then(response => {
          const question = JSON.parse(JSON.stringify(response.data.question))

          this.form.option = question.options

          question.explanation = question.explanation || ''
          question.question_id = question.question_id || ''

          delete question.difficulty
          delete question.options

          this.form.question = question
        })
      }
    },

    data () {
      return {
        formId: Uuid.v4(),

        form: {
          question: {
            uuid: '',
            content: '',
            multiple: false,
            difficulty_id: '',
            explanation: '',
            question_id: ''
          },

          option: []
        }
      }
    },

    methods: {
      update () {
        this.$http.patch(`exams/${this.$route.params.name}/questions/${this.$route.params.uuid}`, this.form).then(response => {
          Toast.success('更新成功')

          this.$router.go({ name: 'admin.exams.questions', params: { name: this.$route.params.name }})
        }, response => {
          Toast.formRequestFailed(response)
        })
      }
    },

    components: {
      FormComponent
    },

    ready () {
      $(`#${this.formId}`).find('.tabs').tabs()
    }
  }
</script>
