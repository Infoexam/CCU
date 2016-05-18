<template>
  <div class="row">
    <div class="col s12">
      <blockquote>
        <h4>{{ $t('practice.precautions.heading') }}</h4>

        <pre>{{ $t('practice.precautions.content') }}</pre>
      </blockquote>
    </div>

    <template v-for="exam in exams">
      <div class="col s12 m4">
        <div class="card hoverable">
          <div class="card-image">
            <a
              v-link="{ name: 'practice.processing', params: { name: exam.name }}"
            ><img :src="exam.cover"></a>
          </div>

          <div class="card-action">
            <a
              v-link="{ name: 'practice.processing', params: { name: exam.name }}"
            >{{ exam.name }}</a>
          </div>
        </div>
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
      }
    },

    data () {
      return {
        exams: {}
      }
    },

    created () {
      this.$http.get('practice/exams').then(response => {
        this.exams = response.data.exams
      })
    }
  }
</script>
