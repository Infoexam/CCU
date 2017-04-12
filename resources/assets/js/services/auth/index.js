import Cache from '~/components/cache'
import QueryString from 'query-string'
import Toast from '~/components/toast'

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

      signOut (url) {
        this.user = null

        Cache.setItem('signIn', false)
        Cache.removeItem('user')

        window.location.href = url || '/'
      }
    },

    methods: {
      signIn (credentials, callable) {
        credentials.authorizing = true

        this.$http.post('auth/sign-in', credentials).then(response => {
          this.$emit('signIn', response.data.user)

          if ('function' === typeof callable) {
            callable(this.homeRoute())
          }
        }, response => {
          credentials.authorizing = false

          Toast.failed(this.$t(response.data.message))
        })
      },

      signOut () {
        this.$http.post('auth/sign-out').then(response => {
          this.$emit('signOut')
        }, response => {
          Toast.failed('Something went wrong.')
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
      const search = QueryString.parse(location.search)

      if ((Cache.getItem('signIn') && null !== Cache.getItem('user')) || (undefined !== search.oauth)) {
        this.user = Cache.getItem('user', {})

        this.$http.get('account/profile').then(response => {
          this.$emit('signIn', response.data.user)
        }, response => {
          this.$emit('signOut', '/sign-in')
        })
      }
    }
  })
}

if ('undefined' !== typeof window && window.Vue) {
  window.Vue.use(install)
}

export default install
