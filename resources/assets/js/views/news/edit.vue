<template>
  <form @submit.prevent="update()">
    <form-component
      :heading.sync="form.heading"
      :content.sync="form.content"
      :link.sync="form.link"
    ></form-component>
  </form>
</template>

<script>
  import FormComponent from './components/form.vue'
  import Toast from '~/components/toast'

  export default {
    route: {
      data (transition) {
        return this.$http.get(`news/${this.$route.params.name}`).then(response => {
          this.form = response.data.news
        })
      }
    },

    data () {
      return {
        form: {
          heading: '',
          content: '',
          link: '',
          is_announcement: false
        }
      }
    },

    methods: {
      update () {
        this.$http.patch(`news/${this.$route.params.name}`, this.form).then(response => {
          Toast.success('更新成功')

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
