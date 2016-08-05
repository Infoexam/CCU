<template>
  <form @submit.prevent="update()">
    <form-component
      v-ref:form-component
      :name.sync="form.name"
      :remark.sync="form.remark"
    ></form-component>
  </form>
</template>

<script>
  import FormComponent from './components/form.vue'
  import Toast from '~/components/toast'

  export default {
    route: {
      data (transition) {
        return this.$http.get(`papers/${this.$route.params.name}`).then(response => {
          this.form = response.data.paper
        })
      }
    },

    data () {
      return {
        form: {
          name: '',
          remark: ''
        }
      }
    },

    methods: {
      update () {
        this.$http.patch(`papers/${this.$route.params.name}`, this.form).then(response => {
          Toast.success('更新成功')

          this.$router.go({ name: 'admin.papers' })
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
