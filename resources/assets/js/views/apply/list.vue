<style></style>

<template>
  <div class="full-width">
    <div>
      <h5>一般預約</h5>

      <table class="bordered highlight centered">
        <thead>
          <tr>
            <th>代碼</th>
            <th>類型</th>
            <th>人數</th>
            <th></th>
          </tr>
        </thead>

        <tbody>
          <tr v-for="listing in generals.data">
            <td>{{ listing.code }}</td>
            <td>
              <p>{{ i18n('listing', listing.subject.name) }}</p>
              <p>{{ i18n('apply', listing.apply_type.name) }}</p>
            </td>
            <td>{{ listing.applied_num }} / {{ listing.maximum_num }}</td>
            <td>
              <template v-if="id = hasApply(listing.code)">
                <delete-button :target="{ code: listing.code, id: id }" :text="'取消'"></delete-button>
              </template>

              <template v-else>
                <a
                  @click="apply(listing.code)"
                  class="waves-effect waves-light btn green"
                >預約</a>
              </template>
            </td>
          </tr>
        </tbody>
      </table>

      <pagination :pagination.sync="generals"></pagination>
    </div>

    <br>

    <div>
      <h5>統一預約</h5>

      <table class="bordered highlight centered">
        <thead>
          <tr>
            <th>代碼</th>
            <th>類型</th>
            <th>人數</th>
            <th></th>
          </tr>
        </thead>

        <tbody>
          <tr v-for="listing in unities.data">
            <td>{{ listing.code }}</td>
            <td>
              <p>{{ i18n('listing', listing.subject.name) }}</p>
              <p>{{ i18n('apply', listing.apply_type.name) }}</p>
            </td>
            <td>{{ listing.applied_num }} / {{ listing.maximum_num }}</td>
            <td>
              <template v-if="id = hasApply(listing.code)">
                <delete-button :target="{ code: listing.code, id: id }" :text="'取消'"></delete-button>
              </template>

              <template v-else>
                <a
                  @click="transform(hasUnity, listing.code)"
                  class="waves-effect waves-light btn orange"
                  :class="{'disabled': ! hasUnity}"
                >轉移</a>
              </template>
            </td>
          </tr>
        </tbody>
      </table>

      <pagination :pagination.sync="unities"></pagination>
    </div>
  </div>
</template>

<script>
  import DeleteButton from '~/components/button/delete.vue'
  import Moment from 'moment'
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
        this.fetch()
      }
    },

    data () {
      return {
        applies: {},

        generals: {
          data: []
        },

        unities: {
          data: []
        }
      }
    },

    computed: {
      hasUnity () {
        for (const listing of this.unities.data) {
          const id = this.hasApply(listing.code)

          if (false !== id) {
            return id
          }
        }

        return false
      }
    },

    methods: {
      fetch () {
        this.$http.get('account/applies').then(response => {
          const applies = {}

          for (const apply of response.data.applies || []) {
            if (0 > Moment().diff(apply.listing.ended_at)) {
              applies[apply.id] = apply.listing.code
            }
          }

          this.applies = applies
        })

        this.$http.get('listings?apply=1').then(response => {
          this.generals = response.data
        })

        this.$http.get('listings?apply=1&unity=1').then(response => {
          this.unities = response.data
        })
      },

      apply (code) {
        this.$http.post(`listings/${code}/applies`).then(response => {
          this.fetch()

          Toast.success('預約成功')
        }, response => {
          Toast.failed('預約失敗')
        })
      },

      transform (id, code) {
        this.$http.patch(`listings/${code}/applies/${id}`).then(response => {
          this.fetch()

          Toast.success('轉移成功')
        }, response => {
          Toast.failed('轉移失敗')
        })
      },

      destroy (info) {
        this.$http.delete(`listings/${info.code}/applies/${info.id}`).then(response => {
          this.fetch()

          Toast.success('刪除成功')
        }, response => {
          Toast.failed('刪除失敗')
        })
      },

      hasApply (code) {
        for (const key of Object.keys(this.applies)) {
          if (code === this.applies[key]) {
            return key
          }
        }

        return false
      },

      i18n (type, key) {
        return this.$t(`${type}.${key.replace(/-/g, '_')}`)
      }
    },

    components: {
      DeleteButton,
      Pagination
    }
  }
</script>
