<template>
  <div>
    <section class="row middle-xs">
      <h3 class="col-xs-5">{{ $route.params.code }}</h3>
    </section>

    <ul class="collection">
      <li
        v-for="apply in applies"
        class="collection-item"
      >
        <div class="row middle-xs between-xs">
          <div class="col-xs">
            <span>{{ apply.user.username }} - {{ apply.user.name }}</span>
          </div>

          <div class="col-xs end-xs">
            <span v-if="! apply.result">缺考</span>

            <div v-else class="row middle-xs end-xs">
              <span style="margin-right: 1.5rem;">{{ apply.result.score }} 分</span>

              <div class="input-field inline" style="margin-right: 1.5rem; width: 4rem;">
                <input v-model="scores[$index]" :value="apply.result.score" type="number" min="0" max="100" class="validate">
              </div>

              <a
                @click="update($index)"
                class="waves-effect waves-light btn orange"
              >更新</a>
            </div>
          </div>
        </div>
      </li>
    </ul>
  </div>
</template>

<script>
  import DeleteButton from '~/components/button/delete.vue'
  import Toast from '~/components/toast'

  export default {
    route: {
      data (transition) {
        return this.$http.get(`gradings/${this.$route.params.code}`).then(response => {
          return {
            applies: response.data.applies
          }
        })
      }
    },

    data () {
      return {
        applies: [],
        scores: {}
      }
    },

    methods: {
      update (index) {
        if (! this.scores.hasOwnProperty(index)) {
          return
        } else if (! this.applies[index].result) {
          return Toast.failed('無法更新未到考之成績')
        }

        this.$http.patch(`gradings/${this.$route.params.code}`, { id: this.applies[index].id, score: this.scores[index] }).then(response => {
          this.applies[index].result.score = this.scores[index]

          Toast.success('更新成功')
        }, response => {
          Toast.failed('更新失敗')
        })
      }
    },

    components: {
      DeleteButton
    }
  }
</script>
