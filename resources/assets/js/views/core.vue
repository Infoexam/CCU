<style src="../../sass/app.scss" lang="sass"></style>

<template>
  <router-view></router-view>

  <layout-footer></layout-footer>

  <progress
    :percent.sync="progressConfig.percent"
    :options="progressConfig.options"
  ></progress>
</template>

<script type="text/babel">
  import LayoutFooter from './layout/footer.vue'
  import Progress from 'vue-progressbar/vue-progressbar.vue'

  export default {
    data () {
      return {
        progressConfig: {
          percent: 0,

          options: {
            show: true,
            canSuccess: true,
            color: 'rgb(255, 234, 0)',
            failedColor: 'red',
            height: '2px'
          }
        }
      }
    },

    components: {
      LayoutFooter,
      Progress
    },

    created () {
      this.$progress.setHolder(this.progressConfig)
    }
  }
</script>
