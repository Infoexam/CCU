<template>
  <div id="question-modal" class="modal modal-fixed-footer" style="max-height: 80%; height: 80%; width: 80%;">
    <div class="modal-content">
      <div v-if="loading" class="row middle-xs center-xs" style="height: 100%;">
        <div class="col-xs">
          <div class="preloader-wrapper big active">
            <div class="spinner-layer spinner-blue-only">
              <div class="circle-clipper left"><div class="circle"></div></div>
              <div class="gap-patch"><div class="circle"></div></div>
              <div class="circle-clipper right"><div class="circle"></div></div>
            </div>
          </div>
        </div>
      </div>

      <template v-else>
        <h4 style="display: inline-block;">{{ question.uuid }}</h4>

        <span class="grey-text text-darken-1" style="display: inline-block; margin-left: .5rem;">
            <span>{{ $t('question.difficulty.' + question.difficulty.name) }}</span>
            <span> / </span>
            <span>{{ question.multiple ? '多選' : '單選' }}</span>
          </span>

        <hr>

        <p>題目</p>

        <blockquote style="margin-left: 1rem;">
          <markdown :model="question.content"></markdown>
        </blockquote>

        <template v-for="option in question.options">
          <p>選項 {{ $index + 1 }}</p>

          <blockquote style="margin-left: 1rem;">
            <markdown :model="option.content"></markdown>
          </blockquote>
        </template>

        <p>解析</p>

        <blockquote style="margin-left: 1rem;">
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
  import Markdown from '~/components/markdown.vue'

  export default {
    data () {
      return {
        question: {
          uuid: '',
          content: '',
          options: []
        },

        loading: true
      }
    },

    events: {
      'show-question': function (name, uuid) {
        this.loading = true

        const cache = {
          css: Object.assign({}, document.body.style),
          scrollY: window.scrollY
        }

        $('#question-modal').openModal({
          out_duration: 0,

          ready () {
            document.body.style.top = -cache.scrollY + 'px'
            document.body.style.position = 'fixed'
          },

          complete () {
            Object.assign(document.body.style, cache.css)

            window.scrollTo(0, cache.scrollY)
          }
        })

        this.$http.get(`exams/${name}/questions/${uuid}`).then(response => {
          this.question = response.data.question

          this.loading = false
        })
      }
    },

    components: {
      Markdown
    }
  }
</script>
