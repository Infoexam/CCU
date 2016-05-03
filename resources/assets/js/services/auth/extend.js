import _Vue from 'vue'

let _data = new _Vue({
  data() {
    return {
      user: null
    }
  }
})

export default function (Vue, router) {
  Vue.auth = {
    me() {
      Vue.http.get(`account/profile`).then((response) => {
        _data.user = response.data.user
      }, (response) => {
        // not sign in
      })
    },

    signIn(credentials, redirect) {
      Vue.http.post(`auth/sign-in`, credentials).then((response) => {
        _data.user = response.data.user

        this._signInRedirect(redirect)
      }, (response) => {
        console.log('sign in failed');
      })
    },

    _signInRedirect(redirect = 'home') {
      router.go({name: redirect})
    },

    signOut() {
      Vue.http.get(`auth/sign-out`).then((response) => {
        _data.user = null
      }, (response) => {
        console.log('sign out failed');
      })
    },

    check() {
      return null !== _data.user
    },

    guest() {
      return ! this.check()
    },

    is(role) {
      return null !== _data.user && role === _data.user.role
    }
  }

  Vue.prototype.$auth = Vue.auth

  return Vue
}
