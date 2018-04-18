<template>
  <div class="row middle-xs">
    <div v-for="item in navigation" class="col-xs-12 col-sm-4">
      <a v-link="item.link">
        <div class="card hoverable">
          <div class="card-image">
            <img :src="imgSrc(item.title)" :alt="item.title">
          </div>

          <div class="card-action">
            <a v-link="item.link">{{ $t(item.title + '.title') }}</a>
          </div>
        </div>
      </a>
    </div>

    <div class="full-width">
      <h5><i class="fa fa-bullhorn fa-fw" aria-hidden="true"></i> 訊息</h5>

      <table class="bordered highlight centered">
        <thead>
          <tr>
            <th>標題</th>
            <th>發佈於</th>
          </tr>
        </thead>

        <tbody>
          <tr v-for="n in news.data">
            <td style="text-align: left;">
              <a @click="show(n)" class="cursor-pointer">{{ n.heading }}</a>
            </td>
            <td>{{ n.created_at }}</td>
          </tr>

          <tr v-if="! news.data.length">
            <td colspan="2">尚無訊息</td>
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
    </div>
  </div>
</template>

<script>
  import Markdown from '~/components/markdown.vue'
  import Pagination from '~/components/pagination.vue'

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
        navigation: [
          { link: { name: 'practice' }, title: 'practice' },
          { link: { name: 'apply' }, title: 'apply' },
          { link: { name: 'test' }, title: 'test' }
        ],

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
      imgSrc (title) {
        return `/assets/images/${title}.png`
      },

      show (n) {
        this.n = n

        $('#news-modal').modal().modal('open')
      }
    },

    components: {
      Markdown,
      Pagination
    }
  }
</script>
