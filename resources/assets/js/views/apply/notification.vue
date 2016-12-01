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
          <td>時間：{{ apply.listing.began_at }}</td>
        </tr>
        <tr>
          <td>類型：{{ i18n('listing', apply.listing.subject.name) }}</td>
          <td>地點：電算中心 {{ apply.listing.room }}</td>
        </tr>
        <tr>
          <td colspan="2">費用：{{ !! apply.paid_at ? '已繳費或免費' : '未繳費' }}</td>
        </tr>
      </tbody>
    </table>

    <p class="center">若您有任何問題，請儘速來信 infoexam@ccu.edu.tw 或電校內分機：14007</p>
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
