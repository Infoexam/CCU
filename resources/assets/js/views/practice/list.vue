<template>
  <div class="row full-width">
    <div class="col-xs-12">
      <blockquote>
        <h4>{{ $t('practice.precautions.heading') }}</h4>

        <pre>{{ $t('practice.precautions.content') }}</pre>
      </blockquote>
    </div>

    <div class="col-xs-12">
      <h4>學科</h4>
    </div>

    <div v-for="exam in exams.theories" class="col-xs-12 col-sm-4">
      <div @click="navigate(exam.name)" class="card hoverable cursor-pointer">
        <div class="card-image">
          <img :src="exam.cover" :alt="exam.name">
        </div>

        <div class="card-action">
          <a>{{ exam.name }}</a>
        </div>
      </div>
    </div>

    <div class="col-xs-12">
      <h4>術科</h4>
    </div>

    <div v-for="exam in exams.techs" class="col-xs-12 col-sm-4">
      <div @click="navigate(exam)" class="card hoverable cursor-pointer">
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
          response.data.theories.sort((a, b) => {
            return b.name.localeCompare(a.name)
          })

          response.data.techs.sort((a, b) => {
            return b.name.localeCompare(a.name)
          })

          return {
            exams: response.data
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
        if ('string' === typeof name) {
          this.$router.go({ name: 'practice.processing', params: { name }})
        } else {
          window.open(name.attachment, '_blank')
        }
      }
    }
  }
</script>
