<template>
  <div v-show="validation.touched && validation.errors" class="input-field col s12">
    <ul class="collection">
      <li
        v-for="error in validation.errors"
        class="collection-item"
      >{{ $t(`form.validation.${error.validator}`, { attribute: $t(attribute + error.field) }) }}</li>
    </ul>
  </div>
</template>

<script type="text/babel">
  export default {
    props: {
      validation: {
        type: Object,
        required: true
      },

      attribute: {
        type: String,
        default: ''
      }
    }
  }
</script>
