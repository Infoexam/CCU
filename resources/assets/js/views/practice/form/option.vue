<template>
  <div style="margin-top: .5rem; margin-bottom: -1rem;">
    <div v-for="o in option">
      <input
        v-model="o.check"
        :name="uuid"
        :type="multiple ? 'checkbox' : 'radio'"
        :id="o.hash"
        :value="true"
        :disabled="submitted"
        @change="update(o)"
      ><label :for="o.hash">{{ $t('form.option') }} {{ $index + 1 }}</label>

      <markdown :model="o.content" class="practice-option-content"></markdown>
    </div>
  </div>
</template>

<script>
  import Markdown from '~/components/markdown.vue'
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
            option.check = (o.hash === option.hash)
          }
        }
      }
    },

    components: {
      Markdown
    }
  }
</script>
