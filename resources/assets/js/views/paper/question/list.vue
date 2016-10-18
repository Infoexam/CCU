<template>
  <section>
    <section class="row middle-xs">
      <h5 class="col-xs-8 col-sm-9" style="word-break: break-all;">{{ paper.name }}</h5>

      <div class="col-xs end-xs">
        <a
          v-link="{ name: 'admin.papers.questions.update', params: { name: paper.name }}"
          class="waves-effect waves-light btn orange inline-flex"
        ><i class="material-icons">edit</i></a>
      </div>
    </section>

    <div class="collection">
      <a
        v-for="question in paper.questions"
        @click="$broadcast('show-question', question.exam.name, question.uuid)"
        class="collection-item cursor-pointer"
      >{{ question.uuid }}</a>
    </div>

    <show></show>
  </section>
</template>

<script>
  import Show from '~/views/question/components/show.vue'

  export default {
    route: {
      data (transition) {
        return this.$http.get(`papers/${this.$route.params.name}/questions`).then(response => {
          return {
            paper: response.data.paper
          }
        })
      }
    },

    data () {
      return {
        paper: {}
      }
    },

    components: {
      Show
    }
  }
</script>
