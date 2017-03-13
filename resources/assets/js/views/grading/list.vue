<template>
  <div>
    <table class="bordered highlight centered">
      <thead>
        <tr>
          <th>代碼</th>
          <th>類型</th>
          <th>成績匯入</th>
        </tr>
      </thead>

      <tbody>
        <tr v-for="listing in listings.data">
          <td>
            <a v-link="{ name: 'admin.gradings.show', params: {code: listing.code }}">{{ listing.code }}</a>
          </td>
          <td>
            <p>{{ i18n('listing', listing.subject.name) }}</p>
            <p>{{ i18n('apply', listing.apply_type.name) }}</p>
          </td>
          <td>
            <div class="file-field input-field inline-flex">
              <div class="btn orange" style="height: 36px; line-height: 36px;">
                <span><i class="fa fa-upload" aria-hidden="true"></i></span>
                <input id="{{ 'f-' + listing.code }}" @change="upload(listing.code)" type="file" style="height: 36px;">
              </div>

              <div class="file-path-wrapper" style="visibility: hidden; width: 0; height: 0; padding: 0;">
                <input class="file-path validate" type="text">
              </div>
            </div>
          </td>
        </tr>
      </tbody>
    </table>

    <pagination :pagination.sync="listings"></pagination>
  </div>
</template>

<script>
  import Pagination from '~/components/pagination.vue'
  import Toast from '~/components/toast'

  export default {
    route: {
      data (transition) {
        return this.$http.get(`gradings`).then(response => {
          return {
            listings: response.data
          }
        })
      }
    },

    data () {
      return {
        listings: {}
      }
    },

    methods: {
      i18n (type, key) {
        return this.$t(`${type}.${key.replace(/-/g, '_')}`)
      },

      upload (code) {
        const file = document.querySelector(`#f-${code}`)

        if (1 === file.files.length) {
          const data = new FormData()

          data.append('file', file.files[0])

          this.$http.post(`gradings/${code}/import`, data).then(response => {
            Toast.success('匯入成功')
          }, response => {
            Toast.formRequestFailed(response)
          })

          file.value = ''
        }
      }
    },

    components: {
      Pagination
    }
  }
</script>
