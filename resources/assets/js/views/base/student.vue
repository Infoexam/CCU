<template>
  <navbar></navbar>

  <main class="container">
    <router-view></router-view>
  </main>
</template>

<script type="text/babel">
  import Navbar from '../layout/navbar/student.vue'

  export default {
    components: {
      Navbar
    }
  }
</script>
