<template>
  <div class="row">
    <div v-for="o in option" class="col s12">
      <input
        v-model="o.check"
        :name="uuid"
        :type="multiple ? 'checkbox' : 'radio'"
        :id="o.hash"
        :value="true"
        :disabled="submitted"
        class="with-gap"
        @change="update(o)"
      ><label :for="o.hash">選項 {{ $index + 1 }}</label>

      <markdown :model="o.content"></markdown>
    </div>
  </div>
</template>

<script type="text/babel">
  import Markdown from '../../../components/markdown.vue'
  import Uuid from 'node-uuid'

  export default {
    props: {
      option: {
        type: Array,
        required: true
      },

      multiple: {
        type: Boolean,
        required: true
      },

      submitted: {
        type: Boolean,
        required: true
      }
    },

    data () {
      return {
        uuid: Uuid.v4()
      }
    },

    methods: {
      update (o) {
        if (! this.multiple) {
          for (const option of this.option) {
            option.check = o === option
          }
        }
      }
    },

    components: {
      markdown: Markdown
    }
  }
</script>
