<template>
  <div class="row">
    <div class="col-xs-12">
      <ul class="tabs">
        <li class="tab col-xs-3"><a href="#from-question">題目</a></li>
        <li class="tab col-xs-3"><a href="#form-option">選項</a></li>
        <li class="tab col-xs-3"><a href="#form-explanation">解析</a></li>
        <li class="tab col-xs-3"><a href="#form-image">圖片</a></li>
      </ul>
    </div>

    <div id="from-question" class="col-xs-12">
      <form-question
        :id.sync="question_id"
        :uuid.sync="uuid"
        :content.sync="content"
      ></form-question>
    </div>

    <div id="form-option" class="col-xs-12">
      <form-option
        :difficulty-id.sync="difficulty_id"
        :multiple.sync="multiple"
        :option.sync="option"
      ></form-option>
    </div>

    <div id="form-explanation" class="col-xs-12">
      <form-explanation
        :explanation.sync="explanation"
      ></form-explanation>
    </div>

    <div id="form-image" class="col-xs-12">
      <form-image></form-image>
    </div>

    <submit :text="submitText" class="col-xs-12"></submit>
  </div>
</template>

<script>
  import FormQuestion from './question.vue'
  import FormOption from './option.vue'
  import FormExplanation from './explanation.vue'
  import FormImage from './image.vue'
  import Submit from '~/components/form/submit.vue'

  export default {
    props: {
      question_id: {
        twoWay: true,
        required: true
      },

      uuid: {
        twoWay: true,
        required: true
      },

      content: {
        twoWay: true,
        required: true
      },

      difficulty_id: {
        twoWay: true,
        required: true
      },

      multiple: {
        twoWay: true,
        required: true
      },

      option: {
        twoWay: true,
        required: true
      },

      explanation: {
        twoWay: true,
        required: true
      },

      create: {
        type: Boolean,
        default: false
      }
    },

    computed: {
      submitText () {
        return this.$t(`form.submit.${this.create ? 'create' : 'update'}`)
      }
    },

    components: {
      FormQuestion,
      FormOption,
      FormExplanation,
      FormImage,
      Submit
    }
  }
</script>
