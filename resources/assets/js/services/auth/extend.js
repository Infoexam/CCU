import _Vue from '../../vueWithResource'
import Toast from '../toast/index'

let router

let auth = new _Vue({
  data() {
    return {
      user: null
    }
  },

  methods: {
    signIn(credentials, callable) {
      this.$progress.start()

      this.$http.post(`auth/sign-in`, credentials).then((response) => {
        this.$progress.finish()

        this.user = response.data.user

        router.go({ name: this.homeRoute() })
      }, (response) => {
        this.$progress.failed()

        let message = 422 === response.status
          ? this.$t('auth.failed')
          : response.data.message

        Toast.failed(message)
      })

      this.$progress.increase(35)
    },

    signOut() {
      this.$progress.start()

      this.$http.get(`auth/sign-out`).then((response) => {
        this.$progress.finish()

        this.user = null

        router.go({ name: 'home' })
      }, (response) => {
        this.$progress.failed()

        console.log('sign out failed');
      })
    },

    guest() {
      return null === this.user
    },

    is(role) {
      return null !== this.user && role === this.user.role
    },

    homeRoute() {
      if (this.guest()) {
        return 'home'
      }

      return this.is('admin') ? 'admin.exams' : 'home'
    }
  },

  init() {
    this.$http.get(`account/profile`).then((response) => {
      this.user = response.data.user
    }, (response) => {
      // not sign in, do nothing
    })
  }
})

export default function (Vue, externalRouter) {
  router = externalRouter

  Vue.prototype.$auth = Vue.auth = auth

  return Vue
}
