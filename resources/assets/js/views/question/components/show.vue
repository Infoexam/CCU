<style lang="scss" scoped>
  #question-modal {
    max-height: 80%;
    height: 80%;
    width: 80%;
  }

  blockquote {
    margin-left: 1rem;
  }

  .modal-header {
    border-bottom: solid 1px rgb(189, 189, 189);

    h4, h6 {
      display: inline-block;
    }
  }
</style>

<template>
  <div id="question-modal" class="modal modal-fixed-footer">
    <div class="modal-content">
      <loader v-if="loading"></loader>

      <template v-else>
        <div class="modal-header">
          <h4>{{ question.uuid }}</h4>

          <h6 class="grey-text text-darken-1">{{ info }}</h6>
        </div>

        <p>題目</p>

        <blockquote>
          <markdown :model="question.content"></markdown>
        </blockquote>

        <template v-for="option in question.options">
          <p>選項 {{ $index + 1 }}</p>

          <blockquote>
            <markdown :model="option.content"></markdown>
          </blockquote>
        </template>

        <p>解析</p>

        <blockquote>
          <markdown :model="question.explanation || '無'"></markdown>
        </blockquote>
      </template>
    </div>

    <div class="modal-footer">
      <a class="modal-action modal-close waves-effect waves-green btn-flat">關閉</a>
    </div>
  </div>
</template>

<script>
  import Loader from '~/components/loader.vue'
  import Markdown from '~/components/markdown.vue'

  export default {
    data () {
      return {
        question: {
          uuid: '',
          content: '',
          difficulty: {
            name: 'middle'
          },
          multiple: false,
          options: []
        },

        loading: true
      }
    },

    computed: {
      info () {
        return this.$t(`question.difficulty.${this.question.difficulty.name}`) + ` / ${this.question.multiple ? '多選' : '單選'}`
      }
    },

    events: {
      'show-question': function (name, uuid) {
        this.loading = true

        const cache = {
          css: document.body.getAttribute('style'),
          scrollY: window.scrollY
        }

        $('#question-modal').modal({
          ready () {
            document.body.style.top = -cache.scrollY + 'px'
            document.body.style.position = 'fixed'
          },

          complete () {
            if (null === cache.css) {
              document.body.removeAttribute('style')
            } else {
              document.body.setAttribute('style', cache.css)
            }

            window.scrollTo(0, cache.scrollY)
          }
        }).modal('open')

        this.$http.get(`exams/${name}/questions/${uuid}`).then(response => {
          this.question = response.data.question

          this.loading = false
        })
      }
    },

    components: {
      Loader,
      Markdown
    }
  }
</script>
