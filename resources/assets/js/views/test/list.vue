<template>
  <div class="full-width">
    <blockquote>
      <h5>測驗注意事項</h5>

      <pre>
總分 100 及格成績為 70 分，測驗時間長度為 60 分鐘。
測驗時間以系統時間為準，考題難易度配題為亂數出題。
考試時請遵守考場秩序與規則，請勿交談或到處走動。
如有問題可舉手發問，若電腦有問題請馬上反應。
待監考人員解說完，即可開始測驗。

測驗期間，禁止開啟其他程式或視窗，系統會自動關閉測驗！
一經發現異常行為者，以作弊論，該次測驗 0 分。
測驗後第二天公布成績，同學們可以到預約測驗系統查詢。
測驗時可以「上、下頁」換頁作檢查答案的動作，若檢查完成，請按下「完成作答」送出答案批改。
一旦送出答案，即完成評改，無法再作答，請同學注意！
測驗結束可自行離開測驗場地。
<!----></pre>
    </blockquote>

    <table class="bordered centered">
      <thead>
        <tr>
          <th>日期</th>
          <th>類型</th>
          <th>教室</th>
          <th>測驗時間</th>
          <th></th>
        </tr>
      </thead>

      <tbody>
        <tr v-for="listing in listings">
          <td>{{ listing.began_at }}</td>
          <td>
            <p>{{ i18n('listing', listing.subject.name) }}</p>
            <p>{{ i18n('apply', listing.apply_type.name) }}</p>
          </td>
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
      },

      i18n (type, key) {
        return this.$t(`${type}.${key.replace(/-/g, '_')}`)
      }
    },

    beforeDestroy () {
      window.clearInterval(this.id)
    }
  }
</script>
