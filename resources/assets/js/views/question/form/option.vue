<style lang="sass">
  .question-form-answer-label {
    position: relative !important;
    left: 0 !important;
  }

  .question-form-option-content {
    margin-top: 20px;
    max-height: 350px;
    overflow-y: scroll;
  }
</style>

<template>
  <div class="row">
    <div class="input-field col s12">
      <materialize-select
        :model.sync="difficultyId"
        :label="'難度'"
        :key="'name'"
        :value="'id'"
        :options="difficulties"
      ></materialize-select>
    </div>

    <div class="switch col s12">
      <label>
        <span>多選</span>

        <input
          v-model="multiple"
          type="checkbox"
        >

        <span class="lever"></span>
      </label>
    </div>

    <div class="input-field col s12">
      <label class="question-form-answer-label">答案</label>

      <template v-for="i in counter">
        <input
          v-model="option[i].answer"
          :id="`option-${i + 1}-answer`"
          type="checkbox"
        >
        <label :for="`option-${i + 1}-answer`">選項 {{ i + 1 }}</label>
      </template>
    </div>

    <template v-for="i in counter">
      <div class="col s12 question-form-option-content">
        <markdown
          :model.sync="option[i].content"
          :length="1000"
          :label="`選項 ${i + 1}`"
        ></markdown>
      </div>
    </template>

    <div class="col s12"><br></div>

    <a
      @click="addOption()"
      class="btn-floating btn-large waves-effect waves-light red"
    ><i class="material-icons">add</i></a>
  </div>
</template>

<script type="text/babel">
  import Markdown from '../../../components/form/markdown.vue'
  import MaterializeSelect from '../../../components/form/select.vue'

  export default {
    props: {
      difficultyId: {
        twoWay: true,
        type: [Number, String],
        required: true
      },

      multiple: {
        twoWay: true,
        type: Boolean,
        required: true
      },

      option: {
        twoWay: true,
        type: Array,
        required: true
      }
    },

    data () {
      return {
        difficulties: [],

        counter: 0
      }
    },

    methods: {
      addOption () {
        this.option.push({ content: '', answer: false })

        ++this.counter
      }
    },

    components: {
      markdown: Markdown,
      materializeSelect: MaterializeSelect
    },

    created () {
      this.$http.get('categories/f/exam.difficulty').then(response => {
        this.difficulties = response.data.categories
      })

      this.addOption()
    }
  }
</script>
