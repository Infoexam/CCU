<template>
  <form @submit.prevent="store()" :id="formId">
    <form-component
      :question_id.sync="form.question.question_id"
      :uuid.sync="form.question.uuid"
      :content.sync="form.question.content"
      :difficulty_id.sync="form.question.difficulty_id"
      :multiple.sync="form.question.multiple"
      :option.sync="form.option"
      :explanation.sync="form.question.explanation"
      :create="true"
    ></form-component>
  </form>
</template>

<script>
  import FormComponent from './components/form.vue'
  import Toast from '../../components/toast'
  import Uuid from 'node-uuid'

  export default {
    data () {
      return {
        formId: Uuid.v4(),

        form: {
          question: {
            uuid: Uuid.v4(),
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
      store () {
        this.$http.post(`exams/${this.$route.params.name}/questions`, this.form).then(response => {
          Toast.success('新增成功')

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
