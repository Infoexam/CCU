<template>
  <div>
    <div class="input-field">
      <input
        v-model="heading"
        id="heading"
        type="text"
        class="validate"
        maxlength="190"
        length="190"
        required
      >
      <label for="heading" :class="{ active: heading }">標題</label>
    </div>

    <div class="input-field">
      <markdown
        :model.sync="content"
        :length="1000"
        :label="'內容'"
      ></markdown>
    </div>

    <div class="input-field">
      <input
        v-model="link"
        id="link"
        type="url"
        class="validate"
        maxlength="190"
        length="190"
      >
      <label for="link" :class="{ active: link }">相關連結</label>
    </div>

    <submit :text="submitText"></submit>
  </div>
</template>

<script>
  import Markdown from '~/components/form/markdown.vue'
  import MaterializeSelect from '~/components/form/select.vue'
  import Submit from '~/components/form/submit.vue'

  export default {
    props: {
      heading: {
        type: String,
        twoWay: true,
        required: true
      },

      link: {
        type: String,
        twoWay: true
      },

      content: {
        type: String,
        twoWay: true
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
      Markdown,
      MaterializeSelect,
      Submit
    },

    ready () {
      $('#heading').characterCounter()
      $('#link').characterCounter()
    }
  }
</script>
