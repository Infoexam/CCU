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
    import availableIcon from '../../components/icon/available.vue'
    import cache from '../../components/cache'
    import pagination from '../../components/pagination.vue'

    export default {
        data() {
            return {
                exams: {}
            }
        },

        watch: {
            exams() {
                cache.setItem('exams', JSON.stringify(this.exams), 'session')
            }
        },

        components: {
            availableIcon,
            pagination
        },

        created() {
            let exams = cache.getItem('exams', () => {
                return this.$http.get(`exams`).then((response) => {
                    this.exams = response.data
                })
            })

            if (exams && 'function' !== typeof exams.then) {
                this.exams = JSON.parse(exams)
            }
        }
    }
</script>
