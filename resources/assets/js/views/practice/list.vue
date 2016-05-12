<template>
  <div class="row">
    <template v-for="exam in exams">
      <div class="col s12 m4">
        <a v-link="{ name: 'practice.processing', params: { id: exam.id }}">
          <div class="card-panel center-align hoverable teal">
            <span class="white-text">{{ exam.name }}</span>
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
