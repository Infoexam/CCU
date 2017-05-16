<template>
  <form @submit.prevent="store()">
    <form-component
      v-ref:form-component
      :name.sync="form.name"
      :category_id.sync="form.category_id"
      :enable.sync="form.enable"
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
          category_id: '',
          enable: false
        }
      }
    },

    methods: {
      store () {
        const data = new FormData()

        data.append('name', this.form.name)
        data.append('category_id', this.form.category_id)
        data.append('enable', this.form.enable ? 1 : 0)
        data.append('cover', this.$refs.formComponent.$els.cover.files[0])

        if (0 < this.$refs.formComponent.$els.attachment.files.length) {
          data.append('attachment', this.$refs.formComponent.$els.attachment.files[0])
        }

        this.$http.post('exams', data).then(response => {
          this.$router.go({ name: 'admin.exams' })
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
