<template>
  <div>
    <div class="input-field">
      <input
        v-model="name"
        id="name"
        type="text"
        class="validate"
        maxlength="16"
        length="16"
        required
      >
      <label for="name" :class="{ active: ! create }">試卷名稱</label>
    </div>

    <div class="input-field">
      <textarea
        v-model="remark"
        id="remark"
        class="materialize-textarea validate"
        maxlength="190"
        length="190"
      ></textarea>
      <label for="remark" :class="{ active: ! create }">備註</label>
    </div>

    <submit :text="$t(`form.submit.${create ? 'create' : 'update'}`)"></submit>
  </div>
</template>

<script>
  import Submit from '~/components/form/submit.vue'

  export default {
    props: {
      name: {
        twoWay: true,
        required: true
      },

      remark: {
        twoWay: true,
        required: true
      },

      create: {
        type: Boolean,
        default: false
      }
    },

    components: {
      Submit
    },

    ready () {
      $('#remark').trigger('autoresize')
      $('#name, #remark').characterCounter()
    }
  }
</script>
