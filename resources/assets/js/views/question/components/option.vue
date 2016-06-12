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
      <label style="position: relative; left: 0;">答案</label>

      <template v-for="i in counter">
        <input
          v-model="option[i].answer"
          :id="`option-${i + 1}-answer`"
          type="checkbox"
        >
        <label :for="`option-${i + 1}-answer`">選項 {{ i + 1 }}</label>
      </template>

      <a
        @click="addOption()"
        class="waves-effect waves-light btn green right"
      ><i class="material-icons">add</i></a>
    </div>

    <div
      class="input-field col s12"
      style="margin-top: 1.4rem; max-height: 600px; overflow-y: scroll"
    >
      <template v-for="i in counter">
        <div class="input-field">
          <markdown
            :model.sync="option[i].content"
            :length="1000"
            :label="`選項 ${i + 1}`"
          ></markdown>
        </div>
      </template>
    </div>
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

        counter: 0,

        binded: false
      }
    },

    watch: {
      option () {
        if (! this.binded) {
          this.counter = this.option.length

          this.binded = true
        }
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

      if (this.$parent.create) {
        this.addOption()
      }
    }
  }
</script>
