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
        Cache.setItem('user', user)
      },

      signOut () {
        this.user = null

        Cache.setItem('signIn', false)
        Cache.removeItem('user')
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
          console.log('Sign out failed.')
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
        if (this.guest() || ! this.is('admin')) {
          return 'home'
        }

        return 'admin.exams'
      }
    },

    created () {
      if (Cache.getItem('signIn') && null !== Cache.getItem('user')) {
        this.user = Cache.getItem('user')

        this.$http.get('account/profile').then(response => {
          this.$emit('signIn', response.data.user)
        }, response => {
          this.$emit('signOut')

          window.location.href = '/sign-in'
        })
      }
    }
  })
}

if ('undefined' !== typeof window && window.Vue) {
  window.Vue.use(install)
}

export default install
