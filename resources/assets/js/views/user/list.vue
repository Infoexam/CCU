<template>
  <div>
    <div class="row">
      <form @submit.prevent="search" class="col s12">
        <div class="row">
          <div class="input-field col s12">
            <i class="material-icons prefix">search</i>
            <input v-model="keyword" @keyup="search" id="keyword" type="text">
            <label for="keyword">關鍵字</label>
          </div>
        </div>
      </form>
    </div>

    <table class="bordered highlight centered">
      <thead>
        <tr>
          <th>學號</th>
          <th>姓名</th>
          <th>系所</th>
        </tr>
      </thead>

      <tbody>
        <tr v-for="user in users.data">
          <td>
            <a v-link="{ name: 'admin.users.show', params: { username: user.username }}" v-text="user.username"></a>
          </td>
          <td>{{ user.name }}</td>
          <td>{{ user.department.remark }}</td>
        </tr>
      </tbody>
    </table>

    <pagination :pagination.sync="users"></pagination>
  </div>
</template>

<script>
  import Pagination from '~/components/pagination.vue'

  export default {
    data () {
      return {
        keyword: '',

        users: {
          data: []
        }
      }
    },

    methods: {
      search () {
        this.$http.get('users/search', { keyword: this.keyword }).then(response => {
          this.users = response.data
        })
      }
    },

    components: {
      Pagination
    }
  }
</script>
