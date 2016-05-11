<template>
    <table class="bordered highlight">
        <thead>
            <tr>
                <th>Name</th>
                <th>Category</th>
                <th>Enable</th>
            </tr>
        </thead>

        <tbody>
            <tr v-for="exam in exams.data">
                <td>
                    <a v-link="{ name: 'admin.exams.questions', params: { id: exam.id }}">{{ exam.name }}</a>
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
        data() {
            return {
                exams: {}
            }
        },

        watch: {
            exams() {
                Cache.setItem('exams', JSON.stringify(this.exams), 'session')
            }
        },

        components: {
            availableIcon: AvailableIcon,
            pagination: Pagination
        },

        created() {
            this.exams = Cache.getItem('exams', () => {
                return this.$http.get(`exams`).then((response) => {
                    this.exams = response.data
                })
            }, true)
        }
    }
</script>
