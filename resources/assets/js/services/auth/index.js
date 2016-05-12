import Toast from '../../components/toast'

function install (Vue) {
  Vue.prototype.$auth = Vue.auth = new Vue({
    data () {
      return {
        user: null
      }
    },

    methods: {
      signIn (credentials, callable) {
        this.$http.post('auth/sign-in', credentials).then(response => {
          this.user = response.data.user

          if ('function' === typeof callable) {
            callable(this.homeRoute())
          }
        }, response => {
          const message = 422 === response.status
            ? this.$t('auth.failed')
            : response.data.message

          Toast.failed(message)
        })
      },

      signOut (callable) {
        this.$http.get('auth/sign-out').then(response => {
          this.user = null

          if ('function' === typeof callable) {
            callable()
          }
        }, response => {
          console.log('sign out failed')
        })
      },

      guest () {
        return null === this.user
      },

      is (...roles) {
        if (null === this.user) {
          return false
        }

        return roles.includes(this.user.role)
      },

      homeRoute () {
        if (this.guest()) {
          return 'home'
        }

        return this.is('admin') ? 'admin.exams' : 'home'
      }
    },

    created () {
      this.$http.get('account/profile').then(response => {
        this.user = response.data.user
      }, response => {
        // not sign in, do nothing
      })
    }
  })
}

if ('undefined' !== typeof window && window.Vue) {
  window.Vue.use(install)
}

export default install
