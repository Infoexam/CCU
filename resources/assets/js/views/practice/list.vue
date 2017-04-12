<template>
  <div class="row full-width">
    <div class="col-xs-12">
      <blockquote>
        <h4>{{ $t('practice.precautions.heading') }}</h4>

        <pre>{{ $t('practice.precautions.content') }}</pre>
      </blockquote>
    </div>

    <div v-for="exam in exams" class="col-xs-12 col-sm-4">
      <div @click="navigate(exam.name)" class="card hoverable cursor-pointer">
        <div class="card-image">
          <img :src="exam.cover" :alt="exam.name">
        </div>

        <div class="card-action">
          <a>{{ exam.name }}</a>
        </div>
      </div>
    </div>
  </div>
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
        return this.$http.get('practice/exams').then(response => {
          response.data.exams.sort((a, b) => {
            return b.name.localeCompare(a.name)
          })

          return {
            exams: response.data.exams
          }
        })
      }
    },

    data () {
      return {
        exams: []
      }
    },

    methods: {
      navigate (name) {
        this.$router.go({ name: 'practice.processing', params: { name }})
      }
    }
  }
</script>
