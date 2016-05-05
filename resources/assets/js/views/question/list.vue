<template>
    <h2 style="display: inline">{{ exam.name }}</h2>

    <a
        v-link="{ name: 'admin.exams.questions.create', params: { id: exam.id }}"
        class="btn-floating btn-large waves-effect waves-light red right"
    ><i class="material-icons">add</i></a>

    <table class="bordered highlight">
        <thead>
            <tr>
                <th>UUID</th>
                <th>Difficulty</th>
                <th></th>
            </tr>
        </thead>

        <tbody>
            <tr v-for="question in exam.questions">
                <td>
                    <a v-link="{ name: 'admin.exams.questions.show', params: { id: exam.id, uuid: question.uuid }}">{{ question.uuid }}</a>
                </td>
                <td>{{ question.difficulty.name }}</td>
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
