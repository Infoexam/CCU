<template>
  <section>
    <table class="bordered highlight centered">
      <thead>
        <tr>
          <th>題庫</th>
          <th>類別</th>
          <th>啟用</th>
          <th>編輯</th>
        </tr>
      </thead>

      <tbody>
        <tr v-for="exam in exams.data">
          <td>
            <a v-link="{ name: 'admin.exams.questions', params: { id: exam.id }}">
              <span style="display: block; margin: 10px 0;">{{ exam.name }}</span>

              <img v-if="exam.cover" :src="exam.cover" style="max-width: 196px;">
            </a>
          </td>
          <td>{{ exam.category.name }}</td>
          <td><available-icon :available.once="exam.enable"></available-icon></td>
          <td>
            <a
              v-link="{ name: 'admin.exams.edit', params: { id: exam.id }}"
              class="waves-effect waves-light btn orange"
            ><i class="material-icons">update</i></a>

            <a
              @click="destroy(exam)"
              class="waves-effect waves-light btn red"
            ><i class="material-icons">delete</i></a>
          </td>
        </tr>
      </tbody>
    </table>
  </section>

  <pagination :pagination.sync="exams"></pagination>
</template>

<script type="text/babel">
  import AvailableIcon from '../../components/icon/available.vue'
  import Pagination from '../../components/pagination.vue'
  import Toast from '../../components/toast'

  export default {
    route: {
      data (transition) {
        return this.$http.get('exams').then(response => {
          return {
            exams: response.data
          }
        })
      }
    },

    data () {
      return {
        exams: {}
      }
    },

    methods: {
      destroy (exam) {
        this.$http.delete(`exams/${exam.id}`).then(response => {
          this.exams.data.$remove(exam)

          Toast.success('刪除成功')
        }, response => {
          Toast.failed('刪除失敗')
        })
      }
    },

    components: {
      AvailableIcon,
      Pagination
    }
  }
</script>
