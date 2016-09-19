<template>
  <div class="inline-block">
    <a
      v-link="edit"
      class="waves-effect waves-light btn orange"
    ><i class="material-icons">edit</i></a>
  </div>
</template>

<script>
  export default {
    props: {
      edit: {
        type: Object,
        required: true
      }
    }
  }
</script>
