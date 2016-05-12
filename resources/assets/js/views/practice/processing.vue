<template>
  <div class="row">
    <form @submit.prevent="submit()" class="col s12">
      <template v-for="question in exam.questions">
        <div class="row">
          <div class="col s12">
            <h5>第 {{* counter++ }} 題</h5>

            <markdown :model="question.content"></markdown>
          </div>

          <form-option
            :question="question"
            :option="question.options"
          ></form-option>
          <!--<template v-for="option in question.options">-->
            <!--<div class="col s12">-->
              <!--<input-->
                <!--:name="`question-${question.id}`"-->
                <!--:type="question.multiple ? 'checkbox' : 'radio'"-->
                <!--:id="`option-${option.id}`"-->
                <!--class="with-gap"-->
              <!--&gt;-->
              <!--<label :for="`option-${option.id}`">-->
                <!--<markdown :model="option.content"></markdown>-->
              <!--</label>-->
            <!--</div>-->
          <!--</template>-->

          <div v-if="question.questions.length > 0" class="col s12">
            <div class="row">
              <template v-for="q in question.questions">
                <div class="col s12">
                  <h5>第 {{* counter++ }} 題</h5>

                  <markdown :model="q.content"></markdown>
                </div>

                <template v-for="o in q.options">
                  <div class="col s12">
                    <input
                      :name="`question-${q.id}`"
                      :type="q.multiple ? 'checkbox' : 'radio'"
                      :id="`option-${o.id}`"
                      class="with-gap"
                    >
                    <label :for="`option-${o.id}`"><markdown :model="o.content"></markdown></label>
                  </div>
                </template>
              </template>
            </div>
          </div>
        </div>
      </template>

      <div class="row">
        <div class="input-field col s12 center">
          <button class="btn waves-effect waves-light" type="submit">
            <span>送出</span>
            <i class="material-icons right">send</i>
          </button>
        </div>
      </div>
    </form>
  </div>
</template>

<script type="text/babel">
  import FormOption from './form/option.vue'
  import Markdown from '../../components/markdown.vue'

  export default {
    data () {
      return {
        counter: 1,

        exam: {}
      }
    },

    methods: {
      submit () {
        alert('submit~')
      }
    },

    components: {
      formOption: FormOption,
      markdown: Markdown
    },

    created () {
      this.$http.get(`practice/${this.$route.params.id}/processing`).then(response => {
        this.exam = response.data.exam
      })
    }
  }
</script>
