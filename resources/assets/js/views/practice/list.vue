<template>
  <div class="row">
    <div class="col-xs-12">
      <blockquote>
        <h4>{{ $t('practice.precautions.heading') }}</h4>

        <pre>{{ $t('practice.precautions.content') }}</pre>
      </blockquote>
    </div>

    <template v-for="exam in exams">
      <div class="col-xs-12 col-sm-4">
        <a v-link="{ name: 'practice.processing', params: { name: exam.name }}">
          <div class="card hoverable">
            <div class="card-image">
              <img :src="exam.cover">
            </div>

            <div class="card-action">
              <a v-link="{ name: 'practice.processing', params: { name: exam.name }}">{{ exam.name }}</a>
            </div>
          </div>
        </a>
      </div>
    </template>
  </div>
</template>

<script type="text/babel">
  export default {
    route: {
      canActivate (transition) {
        const auth = transition.to.router.app.$auth

        if (auth.guest()) {
          transition.redirect({ name: 'signIn' })
        }

        transition.next()
      },

      data (transition) {
        return this.$http.get('practice/exams').then(response => {
          return {
            exams: response.data.exams
          }
        })
      }
    },

    data () {
      return {
        exams: {}
      }
    }
  }
</script>
