<style src="../../sass/app.scss" lang="sass"></style>

<template>
  <div id="vuejs-app">
    <component :is="navbar"></component>

    <router-view></router-view>

    <layout-footer></layout-footer>
  </div>
</template>

<script>
  import LayoutFooter from './layout/footer.vue'
  import NavbarStudent from './layout/navbar/student.vue'
  import NavbarAdmin from './layout/navbar/admin.vue'

  export default {
    computed: {
      navbar () {
        const mapping = {
          '/': 'navbar-student',
          '/admin': 'navbar-admin'
        }

        return mapping[this.$route.matched[0].handler.path] || mapping['/']
      }
    },

    components: {
      LayoutFooter,
      NavbarStudent,
      NavbarAdmin
    }
  }
</script>
