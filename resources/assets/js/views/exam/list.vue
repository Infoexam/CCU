<style lang="sass">
  .exam-list-exam-cover {
    a {
      display: block;
      margin: 10px 0;
    }

    img {
      width: 33%;
    }
  }
</style>

<template>
  <table class="bordered highlight centered">
    <thead>
      <tr>
        <th>Name</th>
        <th>Category</th>
        <th>Enable</th>
      </tr>
    </thead>

    <tbody>
      <tr v-for="exam in exams.data">
        <td class="exam-list-exam-cover">
          <a
            v-link="{ name: 'admin.exams.questions', params: { id: exam.id }}"
          >{{ exam.name }}</a>

          <img
            v-if="null !== exam.cover"
            :src="exam.cover"
          >
        </td>
        <td>{{ exam.category.name }}</td>
        <td><available-icon :available.once="exam.enable"></available-icon></td>
      </tr>
    </tbody>
  </table>

  <pagination class="center-align" :pagination.sync="exams"></pagination>
</template>

<script type="text/babel">
  import AvailableIcon from '../../components/icon/available.vue'
  import Cache from '../../components/cache'
  import Pagination from '../../components/pagination.vue'

  export default {
    data () {
      return {
        exams: {}
      }
    },

    watch: {
      exams () {
        Cache.setItem('exams', this.exams, 'session')
      }
    },

    components: {
      availableIcon: AvailableIcon,
      pagination: Pagination
    },

    created () {
      this.$http.get('exams').then(response => {
        this.exams = response.data
      })
    }
  }
</script>
