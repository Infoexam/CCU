<template>
  <div class="row">
    <form @submit.prevent="store()" class="col s12">
      <div class="row">
        <div class="input-field col s12">
          <input
            v-model="form.name"
            id="name"
            type="text"
            class="validate"
            autofocus
            required
          >
          <label for="name">題庫名稱</label>
        </div>

        <div class="input-field col s12">
          <materialize-select
            :model.sync="form.category_id"
            :label="'題庫類型'"
            :key="'name'"
            :value="'id'"
            :options="categories"
          ></materialize-select>
        </div>

        <div class="switch col s12">
          <label>
            <span>啟用題庫</span>
            <input
              v-model="form.enable"
              type="checkbox"
            >
            <span class="lever"></span>
          </label>
        </div>

        <div class="file-field input-field col s12">
          <div class="btn">
            <span>封面相片</span>

            <input
              v-el:cover
              type="file"
              accept="image/*"
            >
          </div>

          <div class="file-path-wrapper">
            <input class="file-path validate" type="text">
          </div>
        </div>

        <submit :text="$t('form.submit.create')"></submit>
      </div>
    </form>
  </div>
</template>

<script type="text/babel">
  import MaterializeSelect from '../../components/form/select.vue'
  import Submit from '../../components/form/submit.vue'
  import Toast from '../../components/toast'

  export default {
    data () {
      return {
        categories: [],

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
        data.append('cover', this.$els.cover.files[0])

        this.$http.post('exams', data).then(response => {
          this.$router.go({ name: 'admin.exams' })
        }, response => {
          Toast.formRequestFailed(response)
        })
      }
    },

    components: {
      materializeSelect: MaterializeSelect,
      submit: Submit
    },

    created () {
      this.$http.get('categories/f/exam.category').then(response => {
        this.categories = response.data.categories
      })
    }
  }
</script>
