<template>
    <h2>{{ exam.name }}</h2>

    <a v-link="{ name: 'admin.exams.questions.create', params: { id: exam.id }}">+</a>

    <table class="bordered highlight">
        <thead>
            <tr>
                <th>Name</th>
                <th>Category</th>
                <th>Enable</th>
            </tr>
        </thead>

        <tbody>
            <tr v-for="question in exam.questions">
                <td></td>
                <td></td>
                <td></td>
            </tr>
        </tbody>
    </table>
</template>

<script type="text/babel">
    import cache from '../../components/cache'

    export default {
        data() {
            return {
                exam: {}
            }
        },

        methods: {
        },

        ready() {
            let _id = this.$route.params.id

            let exam = cache.getItem(`questions-${_id}`, () => {
                return this.$http.get(`exams/${_id}/questions`).then((response) => {
                    this.exam = response.data.exam
                })
            })

            if (exam && 'function' !== typeof exam.then) {
                this.exam = JSON.parse(exam)
            }
        }
    }
</script>
