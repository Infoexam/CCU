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
                    <div class="row">
                        <div class="input-field col s12">
                            <materialize-select
                                :model.sync="form.question.question_id"
                                :label="'題組'"
                                :key="'uuid'"
                                :value="'id'"
                                :options="groups"
                            ></materialize-select>
                        </div>

                        <div class="input-field col s12">
                            <input
                                v-model="form.question.uuid"
                                id="uuid"
                                type="text"
                                class="validate"
                                maxlength="36"
                                length="36"
                            >
                            <label for="uuid" class="active">代碼</label>
                        </div>

                        <div class="col s12">
                            <markdown
                                :model.sync="form.question.content"
                                :length="5000"
                                :label="'題目'"
                            ></markdown>
                        </div>
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
                    <div class="col s12">
                        <markdown
                            :model.sync="form.question.explanation"
                            :length="5000"
                            :label="'解析'"
                        ></markdown>
                    </div>
                </div>

                <div id="form-image" class="col s12">
                    <div class="row">
                        <template v-for="image in images">
                            <div class="col s12 m6">
                                <input :id="'image-' + $index" :value="image.url">

                                <button
                                    type="button"
                                    class="btn clipboard-btn"
                                    data-clipboard-target="#image-{{ $index }}"
                                >
                                    <i class="fa fa-clipboard" aria-hidden="true"></i>
                                </button>

                                <img
                                    :src="image.url"
                                    class="materialboxed"
                                    width="100%"
                                >
                            </div>
                        </template>
                    </div>

                    <div class="file-field input-field">
                        <div class="btn">
                            <span>Image</span>
                            <input
                                v-el:image
                                @change="upload()"
                                type="file"
                                accept="image/*"
                                multiple
                            >
                        </div>

                        <div class="file-path-wrapper">
                            <input class="file-path validate" type="text">
                        </div>
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
    import Clipboard from 'clipboard'
    import markdown from '../../components/form/markdown.vue'
    import materializeSelect from '../../components/form/select.vue'
    import toast from '../../components/toast'
    import uuid from 'node-uuid'

    export default {
        data() {
            return {
                difficulties: [],

                groups: {},

                images: [],

                formId: uuid.v4(),

                form: {
                    question: {
                        uuid: uuid.v4(),
                        content: '',
                        multiple: false,
                        difficulty_id: '',
                        explanation: '',
                        question_id: ''
                    },

                    option: [],

                    optionNum: 0
                }
            }
        },

        watch: {
            images() {
                $('.materialboxed').materialbox();
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
            },

            upload() {
                let files = this.$els.image.files

                if (0 === files.length) {
                    return
                }

                let data = new FormData()

                for (let i = 0; i < files.length; ++i) {
                    data.append('image[]', files[i])
                }

                this.$http.post(`exams/${this.$route.params.id}/images`, data).then((response) => {
                    this.images = response.data
                })
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
                this.groups = response.data.questions || []
            })

            this.$http.get(`exams/${this.$route.params.id}/images`).then((response) => {
                this.images = response.data
            })

            this.addOption()
        },

        ready() {
            $(`#${this.formId}`).find('.tabs').tabs()

            $('#uuid').characterCounter()

            if (this.images.length > 0) {
                new Clipboard('.clipboard-btn')
            }
        }
    }
</script>
