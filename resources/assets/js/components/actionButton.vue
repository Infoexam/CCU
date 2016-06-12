<template>
  <a
    v-link="edit"
    class="waves-effect waves-light btn orange"
  ><i class="material-icons">edit</i></a>

  <a
    @click="$parent.destroy(destroy)"
    class="waves-effect waves-light btn red"
  ><i class="material-icons">delete</i></a>
</template>

<script type="text/babel">
  export default {
    props: {
      edit: {
        type: Object,
        required: true
      },

      destroy: {
        required: true
      }
    }
  }
</script>
