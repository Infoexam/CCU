<template>
  <div :class="[align]" class="input-field">
    <button
      :class="{ disabled: disabled || invalid }"
      class="btn waves-effect waves-light"
      type="submit"
      :disabled="disabled || invalid"
    >
      <span>{{ text || $t('form.submit.send') }}</span>

      <i class="material-icons right">send</i>
    </button>
  </div>
</template>

<script>
  export default {
    props: {
      align: {
        type: String,
        default: 'center'
      },

      text: {
        type: String
      },

      disabled: {
        type: Boolean,
        default: false
      },

      validation: {
        type: Object
      }
    },

    computed: {
      invalid () {
        return this.validation && this.validation.invalid
      }
    }
  }
</script>
