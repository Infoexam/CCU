<style lang="scss" scoped></style>

<template>
  <div></div>
</template>

<script>
  export default {
    route: {
      canActivate (transition) {
        if (transition.to.router.app.$auth.guest()) {
          transition.redirect({ name: 'signIn' })
        }

        transition.next()
      },

      data (transition) {
        return this.$http.get('listings').then(response => {
          return {
            listings: response.data
          }
        })
      }
    }
  }
</script>
