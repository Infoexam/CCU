<template>
  <div class="row">
    <form @submit.prevent="update()" class="col s12">
      <form-component
        v-ref:form-component
        :name.sync="form.name"
        :category_id.sync="form.category_id"
        :enable.sync="form.enable"
      ></form-component>
    </form>
  </div>
</template>

<script type="text/babel">
  import FormComponent from './components/form.vue'
  import Toast from '../../components/toast'

  export default {
    route: {
      data (transition) {
        return this.$http.get(`exams/${this.$route.params.id}`).then(response => {
          this.form = response.data.exam
        })
      }
    },

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
      update () {
        const data = new FormData()

        data.append('_method', 'PATCH')
        data.append('name', this.form.name)
        data.append('category_id', this.form.category_id)
        data.append('enable', this.form.enable ? 1 : 0)

        if (this.$refs.formComponent.$els.cover.files.length > 0) {
          data.append('cover', this.$refs.formComponent.$els.cover.files[0])
        }

        this.$http.post(`exams/${this.$route.params.id}`, data).then(response => {
          Toast.success('更新成功')

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
