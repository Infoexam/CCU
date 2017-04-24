<template>
  <section>
    <div class="row middle-xs end-xs">
      <a
        v-link="{ name: 'admin.listings.create' }"
        class="waves-effect waves-light btn green inline-flex"
      >新增測驗</a>
    </div>

    <table class="bordered highlight centered">
      <thead>
        <tr>
          <th>代碼</th>
          <th>類型</th>
          <th>人數</th>
          <th>開放預約</th>
          <th>編輯</th>
        </tr>
      </thead>

      <tbody>
        <tr v-for="listing in listings.data">
          <td>
            <a v-link="{ name: 'admin.listings.applies', params: {code: listing.code }}">{{ listing.code }}</a>
          </td>
          <td>
            <p>{{ i18n('listing', listing.subject.name) }}</p>
            <p>{{ i18n('apply', listing.apply_type.name) }}</p>
          </td>
          <td>{{ listing.applied_num }} / {{ listing.maximum_num }}</td>
          <td>
            <available-icon :available="listing.applicable"></available-icon>
          </td>
          <td>
            <edit-button :edit="{ name: 'admin.listings.edit', params: { code: listing.code }}"></edit-button>
            <delete-button :target="listing"></delete-button>
          </td>
        </tr>
      </tbody>
    </table>

    <pagination :pagination.sync="listings"></pagination>
  </section>
</template>

<script>
  import AvailableIcon from '~/components/icon/available.vue'
  import DeleteButton from '~/components/button/delete.vue'
  import EditButton from '~/components/button/edit.vue'
  import Pagination from '~/components/pagination.vue'
  import Toast from '~/components/toast'

  export default {
    route: {
      data (transition) {
        return this.$http.get('listings').then(response => {
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
      i18n (type, key) {
        return this.$t(`${type}.${key.replace(/-/g, '_')}`)
      },

      destroy (listing) {
        this.$http.delete(`listings/${listing.code}`).then(response => {
          this.listings.data.$remove(listing)

          Toast.success('刪除成功')
        }, response => {
          if ('listingStarted' === response.data.message) {
            Toast.failed('無法刪除進行中或已結束的測驗')
          } else if ('listingApplied' === response.data.message) {
            Toast.failed('無法刪除已有使用者預約的測驗')
          } else {
            Toast.failed('刪除失敗')
          }
        })
      }
    },

    components: {
      AvailableIcon,
      DeleteButton,
      EditButton,
      Pagination
    }
  }
</script>
