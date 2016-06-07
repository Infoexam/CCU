<template>
  <div class="row" style="margin-top: 8%;">
    <validator name="validation">
      <form @submit.prevent="signIn()" class="col s12" novalidate>
        <div class="row">
          <div class="input-field col s12">
            <i class="material-icons prefix">account_circle</i>
            <input
              v-model="form.username"
              id="username"
              type="text"
              autofocus
              v-validate:username="form.validation.username"
            >
            <label for="username">{{ $t("auth.username") }}</label>
          </div>

          <div class="input-field col s12">
            <i class="material-icons prefix">lock</i>
            <input
              v-model="form.password"
              id="password"
              type="password"
              v-validate:password="form.validation.password"
            >
            <label for="password">{{ $t("auth.password") }}</label>
          </div>

          <form-errors :validation="$validation" :attribute="'auth.'"></form-errors>

          <submit :text="$t('auth.signIn')" :validation="$validation"></submit>
        </div>
      </form>
    </validator>
  </div>
</template>

<script type="text/babel">
  import FormErrors from '../../components/form/errors.vue'
  import Submit from '../../components/form/submit.vue'

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
          password: '',

          validation: {
            username: {
              required: { rule: true, message: 'invalid' }
            },

            password: {
              required: { rule: true, message: 'invalid' }
            }
          }
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
      FormErrors,
      Submit
    }
  }
</script>
