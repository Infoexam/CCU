<template>
  <i
    v-for="i in total"
    :class="[null === class ? '' : class]"
    class="material-icons"
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
      },

      class: {
        type: String,
        default: null
      }
    }
  }
</script>
