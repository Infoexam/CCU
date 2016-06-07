<template>
  <main class="container">
    <router-view></router-view>
  </main>

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
            color: '#29d',
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
