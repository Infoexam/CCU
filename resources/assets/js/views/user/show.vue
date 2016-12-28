<style lang="scss" scoped></style>

<template>
  <div>
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
          <td colspan="2">分數：{{ user.passed_score || '-' }}</td>
        </tr>
      </tbody>
    </table>

    <br>

    <table class="bordered striped centered">
      <thead>
        <tr>
          <th>類型</th>
          <th>成績</th>
          <th></th>
          <th>免費次數</th>
          <th></th>
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
          <td>
            <a
              @click="credit(certificate.category_id)"
              class="waves-effect waves-light btn orange"
              :class="{'disabled': 0 > certificate.score}"
            >抵免</a>
          </td>
          <td>{{ certificate.free }}</td>
          <td>
            <a
              @click="extend(1, certificate.category_id)"
              class="waves-effect waves-light btn green"
              :class="{'disabled': certificate.free > 254}"
            >+1</a>

            <a
              @click="extend(-1, certificate.category_id)"
              class="waves-effect waves-light btn red"
              :class="{'disabled': certificate.free < 1}"
            >-1</a>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script>
  export default {
    route: {
      data (transition) {
        return this.$http.get(`users/${this.$route.params.username}`).then(response => {
          return {
            user: response.data.user
          }
        })
      }
    },

    data () {
      return {
        user: {
          certificates: [],
          department: {},
          grade: {}
        }
      }
    },

    methods: {
      extend (times, category) {
        this.$http.patch(`users/${this.$route.params.username}`, { times, category, type: 'free' }).then(response => {
          this.user = response.data.user
        })
      },

      credit (category) {
        this.$http.patch(`users/${this.$route.params.username}`, { category, type: 'credit' }).then(response => {
          this.user = response.data.user
        })
      }
    }
  }
</script>
