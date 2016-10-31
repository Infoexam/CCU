<style lang="scss" scoped>
  @media only screen and (min-width: 993px) {
    main {
      padding-left: 250px;
    }
  }
</style>

<template>
  <main>
    <div class="container" style="padding-top: 1rem;">
      <router-view></router-view>
    </div>
  </main>
</template>

<script>
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

    ready () {
      $('footer').addClass('page-footer-admin')
      $('.container').addClass('container-admin')
    },

    beforeDestroy () {
      $('footer').removeClass('page-footer-admin')
      $('.container-admin').removeClass('container-admin')
    }
  }
</script>
