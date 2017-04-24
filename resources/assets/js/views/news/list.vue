<template>
  <section>
    <div class="row middle-xs end-xs">
      <a
        v-link="{ name: 'admin.news.create' }"
        class="waves-effect waves-light btn green inline-flex"
      >新增訊息</a>
    </div>

    <table class="bordered highlight centered">
      <thead>
        <tr>
          <th>標題</th>
          <th>發佈於</th>
          <th>編輯</th>
        </tr>
      </thead>

      <tbody>
        <tr v-for="n in news.data">
          <td>
            <a @click="show(n)" class="cursor-pointer">{{ n.heading }}</a>
          </td>
          <td>{{ n.created_at }}</td>
          <td>
            <edit-button :edit="{ name: 'admin.news.edit', params: { name: n.url }}"></edit-button>
            <delete-button :target="n"></delete-button>
          </td>
        </tr>

        <tr v-if="! news.data.length">
          <td colspan="3">尚無訊息</td>
        </tr>
      </tbody>
    </table>

    <pagination :pagination.sync="news"></pagination>

    <div id="news-modal" class="modal modal-fixed-footer" style="width: 80%;">
      <div class="modal-content">
        <h4>{{ n.heading }}</h4>

        <blockquote>
          <markdown :model="n.content"></markdown>
        </blockquote>

        <template v-if="n.link">
          <span>相關連結：</span>
          <a :href="n.link" target="_blank">{{ n.link }}</a>
        </template>

        <p class="right-align">發佈於：{{ n.created_at }}</p>
      </div>

      <div class="modal-footer">
        <a class="modal-action modal-close waves-effect waves-green btn-flat">關閉</a>
      </div>
    </div>
  </section>
</template>

<script>
  import AvailableIcon from '~/components/icon/available.vue'
  import DeleteButton from '~/components/button/delete.vue'
  import EditButton from '~/components/button/edit.vue'
  import Markdown from '~/components/markdown.vue'
  import Pagination from '~/components/pagination.vue'
  import Toast from '~/components/toast'

  export default {
    route: {
      data (transition) {
        return this.$http.get('news').then(response => {
          return {
            news: response.data
          }
        })
      }
    },

    data () {
      return {
        news: {
          data: []
        },

        n: {
          heading: '',
          content: '',
          link: null
        }
      }
    },

    methods: {
      show (n) {
        this.n = n

        $('#news-modal').modal().modal('open')
      },

      destroy (n) {
        this.$http.delete(`news/${n.url}`).then(response => {
          this.news.data.$remove(n)

          Toast.success('刪除成功')
        }, response => {
          Toast.failed('刪除失敗')
        })
      }
    },

    components: {
      AvailableIcon,
      DeleteButton,
      EditButton,
      Markdown,
      Pagination
    }
  }
</script>
