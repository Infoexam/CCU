<template>
  <div class="full-width">
    <h4>預約確認查詢</h4>

    <table class="bordered striped">
      <tbody>
        <tr>
          <td>姓名：{{ apply.user.name }}</td>
          <td>學號：{{ apply.user.username }}</td>
        </tr>
        <tr>
          <td>場次：{{ apply.listing.code }}</td>
          <td>時間地點：{{ apply.listing.began_at }} {{ apply.listing.room }}</td>
        </tr>
        <tr>
          <td>類型：{{ i18n('listing', apply.listing.subject.name) }}</td>
          <td>已繳費：<available-icon :available="!!apply.paid_at"></available-icon></td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script>
  import AvailableIcon from '~/components/icon/available.vue'

  export default {
    route: {
      data (transition) {
        this.$http.get(`account/apply?token=${this.$route.query.token}&checksum=${this.$route.query.checksum}`).then(response => {
          this.apply = response.data.apply
        }, response => {
          //
        })
      }
    },

    data () {
      return {
        apply: {
          listing: {},
          user: {}
        }
      }
    },

    methods: {
      i18n (type, key) {
        return this.$t(`${type}.${key.replace(/-/g, '_')}`)
      }
    },

    components: {
      AvailableIcon
    }
  }
</script>
