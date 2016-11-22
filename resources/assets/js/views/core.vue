<style src="../../sass/app.scss" lang="scss"></style>

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
    },

    ready () {
      console.log('%c資訊能力測驗系統徵工讀生，請電校內分機 14007', 'color: blue; font-size: x-large')
    }
  }
</script>
