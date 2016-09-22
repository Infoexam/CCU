<template>
  <form @submit.prevent="store()">
    <form-component
      :began_at.sync="form.began_at"
      :duration.sync="form.duration"
      :room.sync="form.room"
      :maximum_num.sync="form.maximum_num"
      :apply_type_id.sync="form.apply_type_id"
      :subject_id.sync="form.subject_id"
      :applicable.sync="form.applicable"
      :auto_generate.sync="form.auto_generate"
      :paper_id.sync="form.paper_id"
      :exam.sync="form.exam"
      :create="true"
    ></form-component>
  </form>
</template>

<script>
  import FormComponent from './components/form.vue'
  import Toast from '~/components/toast'

  export default {
    data () {
      return {
        form: {
          began_at: new Date(new Date().getTime() + 86400000).toJSON().slice(0, 16),
          duration: 90,
          room: '215',
          maximum_num: 60,
          apply_type_id: 0,
          subject_id: 0,
          applicable: false,
          auto_generate: true,
          paper_id: null,
          exam: []
        }
      }
    },

    methods: {
      store () {
        this.$http.post('listings', this.form).then(response => {
          this.$router.go({ name: 'admin.listings' })
        }, response => {
          if ('listingConflict' === response.data.message) {
            Toast.failed('此測驗時段已有其他場次')
          } else {
            Toast.formRequestFailed(response)
          }
        })
      }
    },

    components: {
      FormComponent
    }
  }
</script>
