<template>
  <form @submit.prevent="update()">
    <form-component
      :began_at.sync="form.began_at"
      :duration.sync="form.duration"
      :room.sync="form.room"
      :applicable.sync="form.applicable"
      :paper_id.sync="form.paper_id"
      :exam.sync="form.exam"
      :apply_type_id.sync="form.apply_type_id"
      :subject_id.sync="form.subject_id"
      :maximum_num.sync="form.maximum_num"
    ></form-component>
  </form>
</template>

<script>
  import FormComponent from './components/form.vue'
  import Toast from '~/components/toast'

  export default {
    route: {
      data (transition) {
        return this.$http.get(`listings/${this.$route.params.code}`).then(response => {
          response.data.listing.began_at = response.data.listing.began_at.replace(/ /, 'T')

          this.form = response.data.listing
        })
      }
    },

    data () {
      return {
        form: {
          began_at: new Date().toJSON().slice(0, 16),
          duration: 90,
          room: '215',
          maximum_num: 60,
          apply_type_id: 0,
          subject_id: 0,
          applicable: false,
          paper_id: null
        }
      }
    },

    methods: {
      update () {
        this.$http.patch(`listings/${this.$route.params.code}`, this.form).then(response => {
          Toast.success('更新成功')

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
