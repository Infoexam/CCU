<style lang="sass" scoped>
  @media only screen and (min-width: 993px) {
    main {
      padding-left: 250px;
    }
  }
</style>

<style lang="sass">
  @media only screen and (min-width: 993px) {
    .admin-page-footer {
      padding-left: 250px;
    }

    .admin-container {
      width: 85%;
    }
  }
</style>

<template>
  <navbar></navbar>

  <main>
    <div class="container" style="padding-top: 1rem;">
      <router-view></router-view>
    </div>
  </main>
</template>

<script type="text/babel">
  import Navbar from '../layout/navbar/admin.vue'

  export default {
    route: {
      canActivate (transition) {
        const auth = transition.to.router.app.$auth

        if (auth.guest()) {
          transition.redirect({ name: 'signIn' })
        } else if (! auth.is('admin')) {
          transition.abort('Permission denied.')
        }

        transition.next()
      }
    },

    components: {
      Navbar
    },

    ready () {
      $('footer').addClass('admin-page-footer')
      $('.container').addClass('admin-container')
    },

    beforeDestroy () {
      $('footer').removeClass('admin-page-footer')
      $('.admin-container').removeClass('admin-container')
    }
  }
</script>
