<template>
  <div class="row">
    <div class="input-field col s12">
      <materialize-select
        :model.sync="id"
        :label="'題組'"
        :key="'uuid'"
        :value="'id'"
        :options="groups"
      ></materialize-select>
    </div>

    <div class="input-field col s12">
      <input
        v-model="uuid"
        :id="uuidFieldId"
        type="text"
        class="validate"
        maxlength="36"
        length="36"
      >
      <label :for="uuidFieldId" class="active">代碼</label>
    </div>

    <div class="col s12">
      <markdown
        :model.sync="content"
        :length="5000"
        :label="'題目'"
      ></markdown>
    </div>
  </div>
</template>

<script type="text/babel">
  import Markdown from '../../../components/form/markdown.vue'
  import MaterializeSelect from '../../../components/form/select.vue'
  import Uuid from 'node-uuid'

  export default {
    props: {
      id: {
        twoWay: true,
        type: [Number, String],
        required: true
      },

      uuid: {
        twoWay: true,
        type: String,
        required: true
      },

      content: {
        twoWay: true,
        type: String,
        required: true
      }
    },

    data () {
      return {
        groups: [],

        uuidFieldId: Uuid.v4()
      }
    },

    components: {
      markdown: Markdown,
      materializeSelect: MaterializeSelect
    },

    created () {
      this.$http.get(`exams/${this.$route.params.id}/questions/groups`).then(response => {
        this.groups = response.data.questions || []
      })

      $(`#${this.uuidFieldId}`).characterCounter()
    }
  }
</script>
