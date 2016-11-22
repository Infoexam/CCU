<template>
  <div class="full-width">
    <blockquote>
      <h5>考試注意事項</h5>

      <ol>
        <li>考試期間中，執行與考試無關的操作將視為結束考試</li>
        <li>考試期間中，重新整理網頁將視為結束考試</li>
      </ol>
    </blockquote>

    <table class="bordered centered">
      <thead>
        <tr>
          <th>場次</th>
          <th>教室</th>
          <th>測驗時間</th>
          <th></th>
        </tr>
      </thead>

      <tbody>
        <tr v-for="listing in listings">
          <td>{{ listing.code }}</td>
          <td>{{ listing.room }}</td>
          <td>{{ listing.duration }} 分鐘</td>
          <td>
            <a
              v-if="$auth.is('admin')"
              v-link="{ name: 'test.manage', params: { code: listing.code }}"
              class="waves-effect waves-light btn orange"
            >管理</a>

            <a
              v-else
              v-link="{ name: 'test.processing', params: { code: listing.code }, replace: true }"
              class="waves-effect waves-light btn green"
              :class="{'disabled': ! listing.started_at}"
            >開始</a>
          </td>
        </tr>

        <tr v-if="0 === listings.length">
          <td colspan="4">目前沒有進行中的考試</td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script>
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

        this.id = window.setInterval(this.fetch, 5000)
      }
    },

    data () {
      return {
        id: 0,
        listings: []
      }
    },

    methods: {
      fetch () {
        this.$http.get(`tests`).then(response => {
          this.listings = response.data.listings || []
        })
      }
    },

    beforeDestroy () {
      window.clearInterval(this.id)
    }
  }
</script>
