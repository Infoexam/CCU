import _Vue from '../../vueWithResource'

let router

let auth = new _Vue({
  data() {
    return {
      user: null
    }
  },

  methods: {
    signIn(credentials, callable) {
      this.$http.post(`auth/sign-in`, credentials).then((response) => {
        this.user = response.data.user

        router.go({ name: this.homeRoute() })
      }, (response) => {
        console.log('sign in failed');
      })
    },

    signOut() {
      this.$http.get(`auth/sign-out`).then((response) => {
        this.user = null

        router.go({ name: 'home' })
      }, (response) => {
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
      
      return this.is('admin') ? 'admin' : 'home'
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
