<template>
  <div>
    <div class="input-field">
      <materialize-select
        :model.sync="difficultyId"
        :label="'難度'"
        :key="'name'"
        :value="'id'"
        :options="difficulties"
      ></materialize-select>
    </div>

    <div class="switch">
      <label>
        <span>多選</span>
        <input v-model="multiple" type="checkbox">
        <span class="lever"></span>
      </label>
    </div>

    <div class="input-field">
      <label style="position: relative;">答案</label>

      <template v-for="item in option">
        <input
          v-model="item.answer"
          :id="`option-${$index}-answer`"
          type="checkbox"
        >
        <label
          :for="`option-${$index}-answer`"
          style="margin-left: .5rem; padding-left: 30px;"
        >選項 {{ $index + 1 }}</label>
      </template>

      <a
        @click="option.push({ content: '', answer: false })"
        class="waves-effect waves-light btn green right"
        style="padding: 0 0.6rem !important;"
      ><i class="material-icons">add</i></a>
    </div>

    <div
      class="input-field"
      style="margin-top: 2.4rem; max-height: 600px; overflow-x: hidden; overflow-y: scroll;"
    >
      <template v-for="item in option">
        <div class="row">
          <div class="col-xs-2 col-sm-1">
            <a
              @click="option.$remove(item)"
              class="waves-effect waves-light btn red"
              style="padding: 0 0.6rem !important;"
            ><i class="material-icons">clear</i></a>
          </div>

          <div class="col-xs-10 col-sm-11">
            <markdown
              :model.sync="item.content"
              :length="1000"
              :label="`選項 ${$index + 1}`"
            ></markdown>
          </div>
        </div>
      </template>
    </div>
  </div>
</template>

<script>
  import Markdown from '~/components/form/markdown.vue'
  import MaterializeSelect from '~/components/form/select.vue'

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
        difficulties: []
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
    }
  }
</script>
