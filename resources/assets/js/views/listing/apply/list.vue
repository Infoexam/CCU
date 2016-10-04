<template>
  <section>
    <section class="row middle-xs">
      <h3 class="col-xs-5">{{ listing.code }}</h3>

      <div class="col-xs end-xs">
        <a
          v-link="{ name: 'admin.listings.applies.update', params: { name: listing.code }}"
          class="waves-effect waves-light btn green inline-flex"
        >預約</a>
      </div>
    </section>

    <ul class="collection">
      <li
        v-for="apply in listing.applies"
        class="collection-item"
      >
        <div class="row middle-xs between-xs">
          <div class="col-xs">
            <span>{{ apply.user.username }} - {{ apply.user.name }}</span>
          </div>
          <div class="col-xs end-xs">
            <span style="margin-right: 1rem;">
              <available-icon :available="null !== apply.paid_at"></available-icon>
              <span style="vertical-align: middle;">{{ null !== apply.paid_at ? '已' : '未' }}繳費</span>
            </span>

            <delete-button :target="apply"></delete-button>
          </div>
        </div>
      </li>
    </ul>
  </section>
</template>

<script>
  import AvailableIcon from '~/components/icon/available.vue'
  import DeleteButton from '~/components/button/delete.vue'
  import Toast from '~/components/toast'

  export default {
    route: {
      data (transition) {
        return this.$http.get(`listings/${this.$route.params.code}/applies`).then(response => {
          return {
            listing: response.data.listing
          }
        })
      }
    },

    data () {
      return {
        listing: {
          applies: []
        }
      }
    },

    methods: {
      destroy (apply) {
        this.$http.delete(`listings/${this.$route.params.code}/applies/${apply.id}`).then(response => {
          this.listing.applies.$remove(apply)

          Toast.success('刪除成功')
        }, response => {
          if ('applyUncancelable' === response.data.message) {
            Toast.failed('預約需於測驗開始一日前刪除')
          } else {
            Toast.failed('刪除失敗')
          }
        })
      }
    },

    components: {
      AvailableIcon,
      DeleteButton
    }
  }
</script>
