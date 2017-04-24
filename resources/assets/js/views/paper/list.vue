<template>
  <section>
    <div class="row middle-xs end-xs">
      <a
        v-link="{ name: 'admin.papers.create' }"
        class="waves-effect waves-light btn green inline-flex"
      >新增試卷</a>
    </div>

    <table class="bordered highlight centered">
      <thead>
        <tr>
          <th>試卷</th>
          <th>備註</th>
          <th>題目數</th>
          <th>編輯</th>
        </tr>
      </thead>

      <tbody>
        <tr v-for="paper in papers.data">
          <td>
            <a
              v-link="{ name: 'admin.papers.questions', params: { name: paper.name }}"
            >{{ paper.name }}</a>
          </td>
          <td>
            <p v-if="paper.remark" class="article left-align">{{ paper.remark }}</p>
            <span v-else>-</span>
          </td>
          <td>{{ paper.questions_count }}</td>
          <td>
            <edit-button :edit="{ name: 'admin.papers.edit', params: { name: paper.name }}"></edit-button>
            <delete-button :target="paper"></delete-button>
          </td>
        </tr>
      </tbody>
    </table>

    <pagination :pagination.sync="papers"></pagination>
  </section>
</template>

<script>
  import DeleteButton from '~/components/button/delete.vue'
  import EditButton from '~/components/button/edit.vue'
  import Pagination from '~/components/pagination.vue'
  import Toast from '~/components/toast'

  export default {
    route: {
      data (transition) {
        return this.$http.get('papers').then(response => {
          return {
            papers: response.data
          }
        })
      }
    },

    data () {
      return {
        papers: {
          data: []
        }
      }
    },

    methods: {
      destroy (exam) {
        this.$http.delete(`papers/${exam.name}`).then(response => {
          this.papers.data.$remove(exam)

          Toast.success('刪除成功')
        }, response => {
          if ('nonEmptyListing' === response.data.message) {
            Toast.failed('該試卷已用於測驗中，無法刪除')
          } else {
            Toast.failed('刪除失敗')
          }
        })
      }
    },

    components: {
      DeleteButton,
      EditButton,
      Pagination
    }
  }
</script>
