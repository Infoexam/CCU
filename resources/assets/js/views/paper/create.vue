<template>
  <form @submit.prevent="store()">
    <form-component
      v-ref:form-component
      :name.sync="form.name"
      :remark.sync="form.remark"
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
          name: '',
          remark: ''
        }
      }
    },

    methods: {
      store () {
        this.$http.post('papers', this.form).then(response => {
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
