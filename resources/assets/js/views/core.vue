<style src="../../sass/app.scss" lang="sass"></style>

<template>
  <div id="vuejs-app">
    <component :is="navbar"></component>

    <router-view></router-view>

    <layout-footer></layout-footer>

    <progress
      :percent.sync="progressConfig.percent"
      :options="progressConfig.options"
    ></progress>
  </div>
</template>

<script>
  import LayoutFooter from './layout/footer.vue'
  import NavbarStudent from './layout/navbar/student.vue'
  import NavbarAdmin from './layout/navbar/admin.vue'
  import Progress from 'vue-progressbar/vue-progressbar.vue'

  export default {
    data () {
      return {
        progressConfig: {
          percent: 0,

          options: {
            show: true,
            canSuccess: true,
            color: '#29d',
            failedColor: 'red',
            height: '2px'
          }
        }
      }
    },

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
      NavbarAdmin,
      Progress
    },

    created () {
      this.$progress.setHolder(this.progressConfig)
    }
  }
</script>
