<template>
  <div :class="[align]" class="input-field col s12">
    <button class="btn waves-effect waves-light" type="submit">
      <span>{{ text }}</span>

      <i class="material-icons right">send</i>
    </button>
  </div>
</template>

<script type="text/babel">
  import Vue from '../../vue'

  export default {
    props: {
      align: {
        type: String,
        default: 'center'
      },

      text: {
        type: String,
        default: Vue.t('form.submit.send')
      }
    }
  }
</script>
