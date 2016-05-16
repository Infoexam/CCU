import Cache from '../../components/cache'
import Toast from '../../components/toast'

function install (Vue) {
  Vue.prototype.$auth = Vue.auth = new Vue({
    data () {
      return {
        user: null
      }
    },

    events: {
      signIn (user) {
        this.user = user

        Cache.setItem('signIn', true)
      },

      signOut () {
        this.user = null

        Cache.setItem('signIn', false)
      }
    },

    methods: {
      signIn (credentials, callable) {
        this.$http.post('auth/sign-in', credentials).then(response => {
          this.$emit('signIn', response.data.user)

          if ('function' === typeof callable) {
            callable(this.homeRoute())
          }
        }, response => {
          Toast.failed(this.$t(response.data.message))
        })
      },

      signOut (callable) {
        this.$http.get('auth/sign-out').then(response => {
          this.$emit('signOut')

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
      if (false !== Cache.getItem('signIn')) {
        this.$http.get('account/profile').then(response => {
          this.$emit('signIn', response.data.user)
        }, response => {
          this.$emit('signOut')
        })
      }
    }
  })
}

if ('undefined' !== typeof window && window.Vue) {
  window.Vue.use(install)
}

export default install
