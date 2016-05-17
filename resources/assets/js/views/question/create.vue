<template>
  <div class="row">
    <form @submit.prevent="store()" :id="formId" class="col s12">
      <div class="row">
        <div class="col s12">
          <ul class="tabs">
            <li class="tab col s3"><a href="#from-question">題目</a></li>
            <li class="tab col s3"><a href="#form-option">選項</a></li>
            <li class="tab col s3"><a href="#form-explanation">解析</a></li>
            <li class="tab col s3"><a href="#form-image">圖片</a></li>
          </ul>
        </div>

        <div id="from-question" class="col s12">
          <form-question
            :id.sync="form.question.question_id"
            :uuid.sync="form.question.uuid"
            :content.sync="form.question.content"
          ></form-question>
        </div>

        <div id="form-option" class="col s12">
          <form-option
            :difficulty-id.sync="form.question.difficulty_id"
            :multiple.sync="form.question.multiple"
            :option.sync="form.option"
          ></form-option>
        </div>

        <div id="form-explanation" class="col s12">
          <form-explanation
            :explanation.sync="form.question.explanation"
          ></form-explanation>
        </div>

        <div id="form-image" class="col s12">
          <form-image></form-image>
        </div>

        <submit :text="$t('form.submit.create')"></submit>
      </div>
    </form>
  </div>
</template>

<script type="text/babel">
  import FormQuestion from './form/question.vue'
  import FormOption from './form/option.vue'
  import FormExplanation from './form/explanation.vue'
  import FormImage from './form/image.vue'
  import Submit from '../../components/form/submit.vue'
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
        this.$http.post(`exams/${this.$route.params.id}/questions`, this.form).then(response => {
          Toast.success('Success')

          this.$router.go({ name: 'admin.exams.questions', params: { id: this.$route.params.id }})
        }, response => {
          Toast.formRequestFailed(response)
        })
      }
    },

    components: {
      formQuestion: FormQuestion,
      formOption: FormOption,
      formExplanation: FormExplanation,
      formImage: FormImage,
      submit: Submit
    },

    ready () {
      $(`#${this.formId}`).find('.tabs').tabs()
    }
  }
</script>
