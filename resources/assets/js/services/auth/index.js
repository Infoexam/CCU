import Vue from '../../vue'
import Toast from '../../components/toast'

let Router

let auth = new Vue({
  data() {
    return {
      user: null
    }
  },

  methods: {
    signIn(credentials) {
      this.$http.post(`auth/sign-in`, credentials).then((response) => {
        this.user = response.data.user

        Router.go({ name: this.homeRoute() })
      }, (response) => {
        let message = 422 === response.status
          ? this.$t('auth.failed')
          : response.data.message

        Toast.failed(message)
      })
    },

    signOut() {
      this.$http.get(`auth/sign-out`).then((response) => {
        this.user = null

        Router.go({ name: 'home' })
      }, (response) => {
        console.log('sign out failed');
      })
    },

    guest() {
      return null === this.user
    },

    is(...roles) {
      if (null === this.user) {
        return false
      }

      for (let role of roles) {
        if (role === this.user.role) {
          return true
        }
      }

      return false
    },

    homeRoute() {
      if (this.guest()) {
        return 'home'
      }

      return this.is('admin') ? 'admin.exams' : 'home'
    }
  },

  created() {
    this.$http.get(`account/profile`).then((response) => {
      this.user = response.data.user
    }, (response) => {
      // not sign in, do nothing
    })
  }
})

function install(externalVue, externalRouter) {
  Router = externalRouter

  externalVue.prototype.$auth = externalVue.auth = auth
}

if (typeof window !== 'undefined' && window.Vue) {
  window.Vue.use(install)
}

export default install
