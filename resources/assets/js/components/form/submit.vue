<template>
  <div :class="[align]" class="input-field">
    <button
      :class="{ disabled: disabled }"
      class="btn waves-effect waves-light"
      type="submit"
      :disabled="disabled"
    >
      <span>{{ text || $t('form.submit.send') }}</span>

      <i class="material-icons right">send</i>
    </button>
  </div>
</template>

<script type="text/babel">
  export default {
    props: {
      align: {
        type: String,
        default: 'center'
      },

      text: {
        type: String
      },

      validation: {
        type: Object
      }
    },

    computed: {
      disabled () {
        return this.validation && this.validation.invalid
      }
    }
  }
</script>
