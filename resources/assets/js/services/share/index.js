function install (Vue) {
  Vue.prototype.$share = Vue.share = new Vue({
    data () {
      return {
        navbar: {
          title: {
            admin: this.$t('infoexam')
          }
        }
      }
    }
  })
}

if ('undefined' !== typeof window && window.Vue) {
  window.Vue.use(install)
}

export default install
