<template>
  <div class="full-width">
    <div>
      <blockquote>
        <h5>預約注意事項</h5>

        <pre style="white-space: pre-wrap;">
規則：測驗開始前24小時以前皆可自行變更或取消測驗場次，
     學科、術科每次僅能預約ㄧ場，
     無故缺考者，取消本週內該科目預約資格。

成績：學科成績於次日公佈，公佈後未通過者可再預約其他場次。
     術科成績於次週公佈，公佈後有疑義請於ㄧ個月內申請復查。

費用：大一大二前第一次自行預約者免費參加。
     自費參加者每場費用 NTD.200 元，測驗前ㄧ日至電算中心櫃台繳納。
     繳費後到考才會計算，未到考該次繳費收據當學期內仍有效。

規則：大二學生統一預約測驗免費，如遇衝堂狀況不克於排定日期參加者，
     請自行變更到其他「衝堂補考」場次，原排定場次將取消無法恢復。

類別：
電腦應用能力 (非資工必考，及格70分)
學科 : 文書編輯、電子試算表、簡報軟體、計算機概論、網際網路、資訊安全、資訊應用 (隨機抽考50題)
術科 : 文書編輯、電子試算表、簡報軟體 (三類共15題，各類隨機抽考1題共3題)

電腦軟體能力 (資工必考，其他科系自由選考，及格70分)
學科 : Linux系統管理、Linux網路管理 (隨機抽考50題)
術科 : 程式設計 (4題對2題，每題得分40分，錯誤乙次倒扣2分)
<!--  --></pre>
      </blockquote>
    </div>

    <div>
      <h5>自行預約</h5>

      <table class="bordered highlight centered">
        <thead>
          <tr>
            <th>日期</th>
            <th>類型</th>
            <th>人數</th>
            <th></th>
          </tr>
        </thead>

        <tbody>
          <tr v-for="listing in generals.data">
            <td>{{ listing.began_at }}</td>
            <td>
              <p>{{ i18n('listing', listing.subject.name) }}</p>
              <p>{{ i18n('apply', listing.apply_type.name) }}</p>
            </td>
            <td>{{ listing.applied_num }} / {{ listing.maximum_num }}</td>
            <td>
              <template v-if="id = hasApply(listing.code)">
                <delete-button
                  @mouseover="isOver = true"
                  @mouseleave="isOver = false"
                  :target="{ code: listing.code, id: id }"
                  :text="isOver ? '取　消' : '已預約'"
                ></delete-button>
              </template>

              <a
                v-else
                @click="apply(listing.code)"
                class="waves-effect waves-light btn green"
              >預　約</a>
            </td>
          </tr>

          <tr v-if="0 === generals.data.length">
            <td colspan="4">尚無測驗</td>
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
            <th>日期</th>
            <th>類型</th>
            <th>人數</th>
            <th></th>
          </tr>
        </thead>

        <tbody>
          <template v-for="listing in unities.data">
            <tr>
              <td>{{ listing.began_at }}</td>
              <td>
                <p>{{ i18n('listing', listing.subject.name) }}</p>
                <p>{{ i18n('apply', listing.apply_type.name) }}</p>
              </td>
              <td>{{ listing.applied_num }} / {{ listing.maximum_num }}</td>
              <td>
                <a
                  v-if="hasApply(listing.code)"
                  class="waves-effect waves-light btn red disabled"
                >已預約</a>

                <a
                  v-else
                  @click="transform(hasUnity, listing.code)"
                  class="waves-effect waves-light btn orange"
                  :class="{'disabled': ('unity' === listing.apply_type.name || ! hasUnity || listing.subject_id !== applies[hasUnity].subject_id)}"
                >轉　移</a>
              </td>
            </tr>
          </template>

          <tr v-if="0 === unities.data.length">
            <td colspan="4">尚無測驗</td>
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
        },

        isOver: false
      }
    },

    computed: {
      hasUnity () {
        for (const key of Object.keys(this.applies)) {
          if (['unity', 'makeup'].includes(this.applies[key].type)) {
            return key
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
              applies[apply.id] = {
                code: apply.listing.code,
                type: apply.listing.apply_type.name,
                subject_id: apply.listing.subject_id
              }
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
          Toast.failed(`預約失敗: ${response.data.message}`)
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
          if (code === this.applies[key].code) {
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
