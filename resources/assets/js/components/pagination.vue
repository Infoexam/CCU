<style lang="sass">
    .vue-materializecss-pagination-noselect {
        user-select: none;
    }
</style>

<template>
    <ul class="pagination vue-materializecss-pagination-noselect">
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
</template>

<script type="text/babel">
    import url from 'url'
    import queryString from 'query-string'

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

        computed: {
            pages() {
                let arr = [1, 2]
                let currentPage = this.pagination.current_page
                let lastPage = this.pagination.last_page

                arr.push(currentPage - 1, currentPage, currentPage + 1, lastPage - 1, lastPage)

                arr = arr.sort().filter(function(item, pos, self) {
                    return (item >= 1) && (item <= lastPage) && (self.indexOf(item) === pos)
                })

                let pages = []
                let prev = 1;

                for (let index in arr) {
                    if (arr.hasOwnProperty(index)) {
                        if (arr[index] - prev > 1) {
                            pages.push(-1)
                        }

                        pages.push(arr[index])

                        prev = arr[index]
                    }
                }

                return pages
            },

            prevPageClass() {
                return [
                    (null !== this.pagination.prev_page_url)
                        ? 'waves-effect'
                        : 'disabled'
                ]
            },

            nextPageClass() {
                return [
                    (null !== this.pagination.next_page_url)
                        ? 'waves-effect'
                        : 'disabled'
                ]
            },

            baseUrl() {
                if (this.pagination.per_page >= this.pagination.total) {
                    return null
                }

                let _url = url.parse(this.pagination.prev_page_url || this.pagination.next_page_url)

                return {
                    search: _url.search,
                    pathname: _url.pathname
                }
            }
        },

        methods: {
            prevPage() {
                if (null !== this.pagination.prev_page_url) {
                    this._sendRequest(this.pagination.prev_page_url)
                }
            },

            nextPage() {
                if (null !== this.pagination.next_page_url) {
                    this._sendRequest(this.pagination.next_page_url)
                }
            },

            changePage(page) {
                if (null === this.baseUrl || page === this.pagination.current_page) {
                    return
                }

                let parsed = queryString.parse(this.baseUrl.search)

                parsed.page = page

                this._sendRequest(`${this.baseUrl.pathname}?${queryString.stringify(parsed)}`)
            },

            _sendRequest(url) {
                this.$http.get(url).then((response) => {
                    this.pagination = response.data
                }, (response) => {
                    if (null !== this.failed) {
                        this.failed(response)
                    }
                })
            }
        }
    }
</script>
