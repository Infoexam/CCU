<template>
  <form @submit.prevent="store()">
    <div class="input-field">
      <textarea
        v-model="form.username"
        id="username"
        class="materialize-textarea"
        placeholder="一行一筆"
      ></textarea>
      <label for="username" class="active">學號</label>
    </div>

    <div class="input-field">
      <materialize-select
        :model.sync="form.department"
        :label="'系所預約（統一預約）'"
        :key="'remark'"
        :value="'id'"
        :options="departments"
      ></materialize-select>
    </div>

    <div class="flex middle-xs">
      <label style="margin-top: 18px; margin-bottom: 10px;">班別</label>

      <p style="margin-left: 0.5rem;">
        <input
          v-model="form.class"
          id="class-A"
          name="class"
          type="radio"
          value="A"
        >
        <label for="class-A">A</label>
      </p>

      <p style="margin-left: 1rem;">
        <input
          v-model="form.class"
          id="class-B"
          name="class"
          type="radio"
          value="B"
        >
        <label for="class-B">B</label>
      </p>
    </div>

    <div class="input-field">
      <p>
        <input
          v-model="form.unity"
          id="unity"
          type="checkbox"
        >
        <label for="unity">統一預約</label>
      </p>
    </div>

    <submit></submit>
  </form>
</template>

<script>
  import MaterializeSelect from '~/components/form/select.vue'
  import Submit from '~/components/form/submit.vue'
  import Toast from '~/components/toast'

  export default {
    route: {
      data (transition) {
        return this.$http.get('categories/f/user.department').then(response => {
          for (const category of response.data.categories) {
            if (['0000', '9999'].includes(category.name)) {
              response.data.categories.$remove(category)
            }
          }

          return {
            departments: response.data.categories
          }
        })
      }
    },

    data () {
      return {
        departments: [],

        form: {
          username: '',
          department: '',
          'class': 'A',
          unity: false
        }
      }
    },

    methods: {
      success (users) {
        Toast.success(users.splice(0, 10).reduce((acc, val) => `${acc}<br>${val}`, 'Success List'))

        setTimeout(this.success, 4300, users)
      },

      store () {
        this.$http.post(`listings/${this.$route.params.code}/applies`, this.form).then(response => {
          this.$router.go({ name: 'admin.listings.applies', params: { code: this.$route.params.code }})

          this.success(response.data)
        }, response => {
          Toast.formRequestFailed(response)
        })
      }
    },

    components: {
      MaterializeSelect,
      Submit
    }
  }
</script>
