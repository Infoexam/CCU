<style lang="scss" scoped></style>

<template>
  <div class="full-width">
    <h5>個人資訊</h5>

    <table class="bordered striped">
      <tbody>
        <tr>
          <td>學號：{{ user.username }}</td>
          <td>姓名：{{ user.name }}</td>
        </tr>
        <tr>
          <td>系所：{{ user.department.remark }}</td>
          <td>班別：{{ user.class }}</td>
        </tr>
        <tr>
          <td>年級：{{ user.grade.name }}</td>
          <td>信箱：{{ user.email }}</td>
        </tr>
        <tr>
          <td>測驗：{{ user.passed_at ? '已通過' : '未通過' }}</td>
          <td colspan="2">分數：{{ (user.passed_score >= 0 && user.passed_score) || (user.passed_score == -999 && '全抵免') || '-' }}</td>
        </tr>
      </tbody>
    </table>

    <br>

    <h5>檢定狀態</h5>

    <table class="bordered striped centered">
      <thead>
        <tr>
          <th>類型</th>
          <th>成績</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="certificate in user.certificates">
          <td>{{ $t('exam.' + certificate.category.name) }}</td>
          <td>
            <span v-if="null === certificate.score">-</span>
            <template v-else>
              <span v-if="0 > certificate.score">抵免</span>
              <span v-else v-text="certificate.score"></span>
            </template>
          </td>
        </tr>
      </tbody>
    </table>

    <br>

    <h5>歷史測驗</h5>

    <table class="bordered striped centered">
      <thead>
        <tr>
          <th>場次</th>
          <th>類型</th>
          <th>成績</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="apply in user.applies">
          <td>{{ apply.listing.code }}</td>
          <td>{{ i18n('listing', apply.listing.subject.name) }}</td>
          <td v-if="ended(apply.listing.ended_at)">
            <span v-if="! apply.result">缺考</span>
            <template v-else>
              <span v-if="null === apply.result.score">-</span>
              <div v-else>
                <span>{{ apply.result.score }}</span>
                <br>
                <a
                  v-else
                  @click="log(apply.id)"
                  class="cursor-pointer"
                >閱卷結果</a>
              </div>
            </template>
          </td>
          <td v-else>-</td>
        </tr>
        <tr v-if="! user.applies.length">
          <td colspan="3">尚無紀錄</td>
        </tr>
      </tbody>
    </table>

    <log-modal :log="logHTML"></log-modal>
  </div>
</template>

<script>
  import LogModal from './components/log-modal.vue'
  import Moment from 'moment'

  export default {
    route: {
      data (transition) {
        return this.$http.get(`users/${this.$auth.user.username}`).then(response => {
          return {
            user: response.data.user
          }
        })
      }
    },

    data () {
      return {
        user: {
          applies: [],
          certificates: [],
          department: {},
          grade: {}
        },

        logHTML: ''
      }
    },

    methods: {
      log (id) {
        this.$http.get(`account/log/${id}`).then(response => {
          this.logHTML = response.data

          $('#test-result').modal().modal('open')
        }, response => {
          //
        })
      },

      ended (time) {
        return 0 < Moment().diff(time)
      },

      i18n (type, key) {
        return this.$t(`${type}.${key.replace(/-/g, '_')}`)
      }
    },

    components: {
      LogModal
    }
  }
</script>
