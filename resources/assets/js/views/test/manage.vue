<style lang="scss" scoped></style>

<template>
  <div class="full-width">
    <div>
      <h4>測驗資訊</h4>

      <table class="bordered striped">
        <tbody>
          <tr>
            <td>場次：{{ listing.code }}</td>
            <td>到考人數：{{ listing.tested_num }} / {{ listing.applied_num }}</td>
            <td>
              <a
                :href="'/api/tests/' + listing.code + '/manage/check-in'"
                target="_blank"
                class="waves-effect waves-light btn teal darken-1 right"
              >簽到單</a>
            </td>
          </tr>
          <tr>
            <td>類型：{{ i18n('listing', listing.subject.name) }}</td>
            <td>{{ i18n('apply', listing.apply_type.name) }}</td>
            <td>
              <a
                :href="'/api/tests/' + listing.code + '/manage/pc2'"
                target="_blank"
                class="waves-effect waves-light btn teal darken-1 right"
              >術科名單</a>
            </td>
          </tr>
          <tr>
            <td>開始時間：{{ listing.started_at || '尚未開始' }}</td>
            <td>結束時間：{{ listing.started_at ? listing.ended_at : '-' }}</td>
            <td>時長：{{ listing.duration }} 分鐘</td>
          </tr>
          <tr>
            <td>
              <a
                v-if="! listing.started_at"
                @click="start()"
                class="waves-effect waves-light btn green"
              >開始測驗</a>
            </td>
            <td></td>
            <td>
              <span>延長測驗：</span>

              <a
                @click="extend(5)"
                class="waves-effect waves-light btn green"
                :class="{'disabled': listing.duration > 250}"
              >+5 分鐘</a>

              <a
                @click="extend(-5)"
                class="waves-effect waves-light btn red"
                :class="{'disabled': listing.duration < 6}"
              >-5 分鐘</a>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <br>

    <div>
      <h4>考生</h4>

      <table class="bordered centered">
        <thead>
          <tr>
            <th>姓名</th>
            <th>學號</th>
            <th>狀態</th>
            <th></th>
          </tr>
        </thead>

        <tbody>
          <tr v-for="apply in listing.applies">
            <td>{{ apply.user.name }}</td>
            <td>{{ apply.user.username }}</td>
            <td>
              <template v-if="apply.result">
                <div v-if="apply.result.submitted_at">
                  <span class="green-text">已繳交</span>
                </div>

                <span
                  v-else
                  class="blue-text"
                >作答中</span>
              </template>

              <span
                v-else
                class="red-text"
              >未測驗</span>
            </td>

            <td>
              <span
                v-if="apply.result"
                @click="redo(apply.id)"
                class="tooltipped"
                style="cursor: pointer;"
                data-tooltip="重新作答"
              >
                <i class="fa fa-repeat" aria-hidden="true"></i>
              </span>
            </td>
          </tr>

          <tr v-if="0 === listing.applies.length">
            <td colspan="4">無報考學生</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script>
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
        this.info()
      }
    },

    data () {
      return {
        listing: {
          applies: [],
          subject: {},
          apply_type: {}
        },

        ids: {
          info: -1
        }
      }
    },

    watch: {
      listing () {
        $('span.tooltipped').tooltip()
      }
    },

    methods: {
      info () {
        this.$http.get(`tests/${this.$route.params.code}/manage`).then(response => {
          this.listing = response.data.listing
        })
      },

      start () {
        this.$http.patch(`tests/${this.$route.params.code}/manage/start`).then(response => {
          this.info()
        })
      },

      extend (minutes) {
        this.$http.patch(`tests/${this.$route.params.code}/manage/extend`, { minutes }).then(response => {
          this.info()

          if (0 <= minutes) {
            minutes = `+${minutes}`

            Toast.success(`測驗時間已 ${minutes} 分鐘`)
          } else {
            Toast.failed(`測驗時間已 ${minutes} 分鐘`)
          }
        })
      },

      redo (id) {
        this.$http.patch(`tests/${this.$route.params.code}/manage/redo`, { id }).then(response => {
          this.info()

          Toast.success('操作成功')
        })
      },

      i18n (type, key) {
        if (! key) {
          return ''
        }

        return this.$t(`${type}.${key.replace(/-/g, '_')}`)
      }
    },

    ready () {
      this.ids.info = window.setInterval(this.info, 5000)
    },

    beforeDestroy () {
      if (-1 !== this.ids.info) {
        window.clearInterval(this.ids.info)
      }

      $('span.tooltipped').tooltip('remove')
    }
  }
</script>
