<template>
  <div class="row middle-xs full-width">
    <form @submit.prevent="signIn()" class="col-xs-12 col-sm-8 col-sm-offset-2">
      <div class="input-field">
        <i class="material-icons prefix">account_circle</i>
        <input
          v-model="form.username"
          id="username"
          type="text"
          class="validate"
          autofocus
          required
        >
        <label for="username">{{ $t("auth.username") }}</label>
      </div>

      <div class="input-field">
        <i class="material-icons prefix">lock</i>
        <input
          v-model="form.password"
          id="password"
          type="password"
          class="validate"
          required
        >
        <label for="password">{{ $t("auth.password") }}</label>
      </div>

      <submit :text="$t('auth.signIn')"></submit>
    </form>
  </div>
</template>

<script>
  import Submit from '~/components/form/submit.vue'

  export default {
    route: {
      canActivate (transition) {
        const auth = transition.to.router.app.$auth

        if (! auth.guest()) {
          transition.redirect({
            name: auth.homeRoute()
          })
        }

        transition.next()
      }
    },

    data () {
      return {
        form: {
          username: '',
          password: ''
        }
      }
    },

    methods: {
      signIn () {
        this.$auth.signIn(this.form, home => {
          this.$router.go({ name: home })
        })
      }
    },

    components: {
      Submit
    }
  }
</script>
