<style></style>

<template>
  <div class="full-width">
    <table class="bordered highlight centered">
      <thead>
        <tr>
          <th>代碼</th>
          <th>類型</th>
          <th>人數</th>
          <th>轉移</th>
        </tr>
      </thead>

      <tbody>
        <tr v-for="listing in listings.data">
          <td>{{ listing.code }}</td>
          <td>
            <p>{{ i18n('listing', listing.subject.name) }}</p>
            <p>{{ i18n('apply', listing.apply_type.name) }}</p>
          </td>
          <td>{{ listing.applied_num }} / {{ listing.maximum_num }}</td>
          <td>
            <a
              @click="apply(listing.code)"
              class="waves-effect waves-light btn orange"
            ><i class="material-icons">edit</i></a>
          </td>
        </tr>
      </tbody>
    </table>

    <pagination :pagination.sync="listings"></pagination>
  </div>
</template>

<script>
  import Pagination from '~/components/pagination.vue'
  import Toast from '~/components/toast'

  export default {
    route: {
      canActivate (transition) {
        if (transition.to.router.app.$auth.guest()) {
          transition.redirect({ name: 'signIn' })
        }

        transition.next()
      },

      data (transition) {
        return this.$http.get('listings?apply=1&unity=1').then(response => {
          return {
            listings: response.data
          }
        })
      }
    },

    data () {
      return {
        listings: {
          data: []
        }
      }
    },

    methods: {
      apply (code) {
        this.$http.patch(`listings/${code}/applies`).then(response => {
          Toast.success('轉移成功')
        }, response => {
          Toast.failed('轉移失敗')
        })
      },

      i18n (type, key) {
        return this.$t(`${type}.${key.replace(/-/g, '_')}`)
      }
    },

    components: {
      Pagination
    }
  }
</script>
