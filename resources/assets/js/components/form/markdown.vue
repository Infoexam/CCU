<template>
  <div class="row">
    <div class="input-field col-xs-12 col-sm-5">
      <textarea
        v-model="model"
        :id="textareaId"
        class="materialize-textarea validate"
        :maxlength="length"
        :length="length"
      ></textarea>

      <label
        :for="textareaId"
        class="active"
      >{{ label }}</label>
    </div>

    <div class="col-sm-6 col-sm-offset-1 hide-on-small-only">
      <span>{{ $t('form.preview') }}</span>

      <markdown :model="model"></markdown>
    </div>
  </div>
</template>

<script>
  import Markdown from '~/components/markdown.vue'
  import Uuid from 'node-uuid'

  export default {
    props: {
      model: {
        twoWay: true,
        required: true
      },

      length: {
        type: Number,
        required: true
      },

      label: {
        type: String,
        required: true
      },

      textareaId: {
        type: String
      }
    },

    components: {
      Markdown
    },

    created () {
      // The textareaId default value must assign here
      this.textareaId = this.textareaId || Uuid.v4()
    },

    ready () {
      const textarea = $(`#${this.textareaId}`)

      textarea.trigger('autoresize')
      textarea.characterCounter()
    }
  }
</script>
