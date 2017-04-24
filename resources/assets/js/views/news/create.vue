<template>
  <form @submit.prevent="store()">
    <form-component
      :heading.sync="form.heading"
      :content.sync="form.content"
      :link.sync="form.link"
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
          heading: '',
          content: '',
          link: null,
          is_announcement: false
        }
      }
    },

    methods: {
      store () {
        this.$http.post('news', this.form).then(response => {
          this.$router.go({ name: 'admin.news' })
        }, response => {
          Toast.formRequestFailed(response)
        })
      }
    },

    components: {
      FormComponent
    }
  }
</script>
