<template>
    <div class="row">
        <form @submit.prevent="store()" :id="formId" class="col s12">
            <div class="row">
                <div class="col s12">
                    <ul class="tabs">
                        <li class="tab col s3"><a href="#from-question">題目</a></li>
                        <li class="tab col s3"><a href="#form-option">選項</a></li>
                        <li class="tab col s3"><a href="#form-explanation">解析</a></li>
                        <li class="tab col s3"><a href="#form-image">圖片</a></li>
                    </ul>
                </div>

                <div id="from-question" class="col s12">
                    <form-question
                        :id.sync="form.question.question_id"
                        :uuid.sync="form.question.uuid"
                        :content.sync="form.question.content"
                    ></form-question>
                </div>

                <div id="form-option" class="col s12">
                    <div class="row">
                        <div class="input-field col s12">
                            <materialize-select
                                :model.sync="form.question.difficulty_id"
                                :label="'難度'"
                                :key="'name'"
                                :value="'id'"
                                :options="difficulties"
                            ></materialize-select>
                        </div>

                        <div class="switch col s12">
                            <label>
                                <span>多選</span>
                                <input
                                    v-model="form.question.multiple"
                                    type="checkbox"
                                >
                                <span class="lever"></span>
                            </label>
                        </div>

                        <div class="input-field col s12">
                            <label>答案</label>

                            <template v-for="i in form.optionNum">
                                <input
                                    v-model="form.option[i].answer"
                                    :id="`option-${i+1}-answer`"
                                    type="checkbox"
                                >
                                <label :for="`option-${i+1}-answer`">選項 {{ i + 1 }}</label>
                            </template>
                        </div>

                        <template v-for="i in form.optionNum">
                            <div class="col s12"><br></div>

                            <div class="col s12" style="max-height: 350px; overflow-y: scroll">
                                <markdown
                                    :model.sync="form.option[i].content"
                                    :length="1000"
                                    :label="'選項 ' + (i + 1)"
                                ></markdown>
                            </div>
                        </template>

                        <div class="col s12"><br></div>

                        <a
                            @click="addOption()"
                            class="btn-floating btn-large waves-effect waves-light red"
                        ><i class="material-icons">add</i></a>
                    </div>
                </div>

                <div id="form-explanation" class="col s12">
                    <form-explanation
                        :explanation.sync="form.question.explanation"
                    ></form-explanation>
                </div>

                <div id="form-image" class="col s12">
                    <form-image></form-image>
                </div>

                <submit :text="$t('form.submit.create')"></submit>
            </div>
        </form>
    </div>
</template>

<script type="text/babel">
    import markdown from '../../components/form/markdown.vue'
    import materializeSelect from '../../components/form/select.vue'
    import toast from '../../components/toast'
    import Uuid from 'node-uuid'

    import FormQuestion from './form/question.vue'
    import FormExplanation from './form/explanation.vue'
    import FormImage from './form/image.vue'
    import Submit from '../../components/form/submit.vue'

    export default {
        data() {
            return {
                difficulties: [],

                formId: Uuid.v4(),

                form: {
                    question: {
                        uuid: Uuid.v4(),
                        content: '',
                        multiple: false,
                        difficulty_id: '',
                        explanation: '',
                        question_id: 0
                    },

                    option: [],

                    optionNum: 0
                }
            }
        },

        methods: {
            store() {
                this.$http.post(`exams/${this.$route.params.id}/questions`, this.form).then((response) => {
                    toast.success('Success')
                }, (response) => {
                    toast.formRequestFailed(response)
                })
            },

            addOption() {
                this.form.option.push({ content: '', answer: false })

                ++this.form.optionNum
            }
        },

        components: {
            markdown,
            materializeSelect,
            formQuestion: FormQuestion,
            formExplanation: FormExplanation,
            formImage: FormImage,
            submit: Submit
        },

        created() {
            this.$http.get(`categories/f/exam.difficulty`).then((response) => {
                this.difficulties = response.data.categories
            })

            this.addOption()
        },

        ready() {
            $(`#${this.formId}`).find('.tabs').tabs()

            $('#uuid').characterCounter()
        }
    }
</script>
