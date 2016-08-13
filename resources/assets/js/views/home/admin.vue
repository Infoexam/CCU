<template>
  <div>
    <table class="bordered highlight centered">
      <thead>
        <tr>
          <th>User</th>
          <th>Target</th>
          <th>Type</th>
          <th>Time</th>
        </tr>
      </thead>

      <tbody>
        <tr v-for="revision in revisions.data">
          <td>{{ revision.user.username || 'system' }}</td>
          <td>{{ revision.revisionable_type }}</td>
          <td>{{ type(revision.key) }}</td>
          <td>{{ revision.created_at }}</td>
        </tr>
      </tbody>
    </table>

    <pagination :pagination.sync="revisions"></pagination>
  </div>
</template>

<script>
  import Pagination from '~/components/pagination.vue'

  export default {
    route: {
      data (transition) {
        return this.$http.get('revisions').then(response => {
          return {
            revisions: response.data
          }
        })
      }
    },

    data () {
      return {
        revisions: {}
      }
    },

    methods: {
      type (key) {
        switch (key) {
          case 'created_at':
            return 'Create'
          case 'deleted_at':
            return 'Delete'
          default:
            return 'Update'
        }
      }
    },

    components: {
      Pagination
    }
  }
</script>
