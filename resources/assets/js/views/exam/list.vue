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
                <td>{{ exam.name }}</td>
                <td>{{ exam.category.name }}</td>
                <td>{{ exam.enable }}</td>
            </tr>
        </tbody>
    </table>

    <pagination class="center-align" :pagination.sync="exams"></pagination>
</template>

<script type="text/babel">
    import pagination from '../../components/pagination.vue'

    export default {
        data() {
            return {
                exams: {}
            }
        },

        components: {
            pagination
        },

        created() {
            this.$http.get(`exams`).then((response) => {
                this.exams = response.data
            });
        }
    }
</script>
