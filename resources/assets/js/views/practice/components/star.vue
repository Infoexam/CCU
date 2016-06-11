<template>
  <i
    v-for="i in total"
    class="material-icons tiny"
  >{{ active > i ? 'star' : 'star_border' }}</i>
</template>

<script type="text/babel">
  export default {
    props: {
      total: {
        type: Number,
        required: true
      },

      active: {
        type: Number,
        required: true,
        validator (value) {
          return 0 <= value
        }
      }
    }
  }
</script>
