<template>
  <span>
    <i
      :class="[available ? 'green-text' : 'red-text']"
      class="material-icons"
    >{{ available ? 'check' : 'close' }}</i>
  </span>
</template>

<script>
  export default {
    props: {
      available: {
        type: Boolean,
        required: true
      }
    }
  }
</script>
