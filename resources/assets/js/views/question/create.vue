<template>
    <div class="row">
        <form @submit.prevent="store()" :id="formId" class="col s12">
            <div class="row">
                <div class="col s12">
                    <ul class="tabs">
                        <li class="tab col s4"><a href="#from-question">題目</a></li>
                        <li class="tab col s4"><a href="#form-option">選項</a></li>
                        <li class="tab col s4"><a href="#form-explanation">解析</a></li>
                    </ul>
                </div>

                <div id="from-question" class="col s12">
                    <div class="row">
                        <div class="input-field col s12">
                            <materialize-select
                                :model.sync="form.question.question_id"
                                :label="'題組'"
                                :key="'$index'"
                                :value="'$index'"
                                :options="groups"
                            ></materialize-select>
                        </div>

                        <markdown
                            :model.sync="form.question.content"
                            :length="1000"
                            :label="'題目'"
                        ></markdown>
                    </div>
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
                    </div>
                </div>

                <div id="form-explanation" class="col s12">
                    <div class="row">
                        <markdown
                            :model.sync="form.question.explanation"
                            :length="1000"
                            :label="'解析'"
                        ></markdown>
                    </div>
                </div>

                <div class="input-field col s12 center">
                    <button class="btn waves-effect waves-light" type="submit">
                        <span>新增</span>
                        <i class="material-icons right">send</i>
                    </button>
                </div>
            </div>
        </form>
    </div>
</template>

<script type="text/babel">
    import markdown from '../../components/form/markdown.vue'
    import materializeSelect from '../../components/form/select.vue'
    import uuid from 'node-uuid'

    let temp = `|   | Hi | sss | ccc  |
|---|----|-----|------|
| 1 | f  | ff  | fff  |
| 2 | ss | sss | sass |
| 3 | d  | ddd | dd   |

[this is a link](https://www.google.com.tw)

## User ##
- [x] List exam's questions

## General ##
- [ ] Optimize and refactor
- [x] Permission check`

    export default {
        data() {
            return {
                difficulties: [],
                groups: [],

                formId: uuid.v4(),

                form: {
                    question: {
                        content: temp,
                        multiple: false,
                        difficulty_id: '',
                        explanation: temp,
                        question_id: ''
                    }
                }
            }
        },

        methods: {
            store() {
                console.log('ok')
            }
        },

        components: {
            markdown,
            materializeSelect
        },

        created() {
            this.$http.get(`categories/f/exam.difficulty`).then((response) => {
                this.difficulties = response.data.categories
            })

            this.$http.get(`exams/${this.$route.params.id}/questions/groups`).then((response) => {
                this.groups = response.data
            })
        },

        ready() {
            $(`#${this.formId}`).find('.tabs').tabs()
        }
    }
</script>
