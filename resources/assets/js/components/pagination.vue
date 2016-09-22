<template>
  <section class="center">
    <ul class="pagination user-select-none">
      <li :class="prevPageClass">
        <a @click.prevent="prevPage()"><i class="material-icons">chevron_left</i></a>
      </li>

      <template v-for="page in pages" track-by="$index">
        <li v-if="-1 === page">...</li>

        <li
          v-else
          :class="[page === pagination.current_page ? 'active' : 'waves-effect']"
        ><a @click.prevent="changePage(page)">{{ page }}</a></li>
      </template>

      <li :class="nextPageClass">
        <a @click.prevent="nextPage()"><i class="material-icons">chevron_right</i></a>
      </li>
    </ul>
  </section>
</template>

<script>
  import Md5 from 'md5'
  import QueryString from 'query-string'
  import Url from 'url'

  export default {
    props: {
      pagination: {
        twoWay: true,
        type: Object,
        required: true
      },

      failed: {
        type: Function,
        default: null
      }
    },

    data () {
      return {
        cache: {}
      }
    },

    computed: {
      pages () {
        const currentPage = this.pagination.current_page
        const lastPage = this.pagination.last_page
        const pages = []
        let arr = [1]
        let prev = 1

        if (isNaN(currentPage) || isNaN(lastPage)) {
          return arr
        }

        arr.push(currentPage - 1, currentPage, currentPage + 1, lastPage)

        // Remove duplicate
        arr = Array.from(new Set(
          arr.filter(val => {
            return 0 < val && val <= lastPage
          })
        ))

        for (const element of arr) {
          if (1 < element - prev) {
            pages.push(-1)
          }

          pages.push(element)

          prev = element
        }

        return pages
      },

      prevPageClass () {
        return [
          (null !== this.pagination.prev_page_url)
            ? 'waves-effect'
            : 'disabled'
        ]
      },

      nextPageClass () {
        return [
          (null !== this.pagination.next_page_url)
            ? 'waves-effect'
            : 'disabled'
        ]
      },

      baseUrl () {
        if (this.pagination.per_page >= this.pagination.total) {
          return null
        }

        const url = Url.parse(this.pagination.prev_page_url || this.pagination.next_page_url)

        return {
          search: url.search,
          pathname: url.pathname
        }
      }
    },

    methods: {
      prevPage () {
        if (null !== this.pagination.prev_page_url) {
          this._sendRequest(this.pagination.prev_page_url)
        }
      },

      nextPage () {
        if (null !== this.pagination.next_page_url) {
          this._sendRequest(this.pagination.next_page_url)
        }
      },

      changePage (page) {
        if (null === this.baseUrl || page === this.pagination.current_page) {
          return
        }

        const parsed = QueryString.parse(this.baseUrl.search)

        parsed.page = page

        this._sendRequest(`${this.baseUrl.pathname}?${QueryString.stringify(parsed)}`)
      },

      _sendRequest (url) {
        const hash = Md5(url)

        if (this.cache.hasOwnProperty(hash)) {
          this.pagination = this.cache[hash]

          return
        }

        this.$http.get(url).then(response => {
          this.pagination = this.cache[hash] = response.data
        }, response => {
          if (null !== this.failed) {
            this.failed(response)
          }
        })
      }
    }
  }
</script>
