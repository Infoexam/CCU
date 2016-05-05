<template>
    <div class="row">
        <form @submit.prevent="store()" class="col s12">
            <div class="row">
                <div class="input-field col s12">
                    <input
                        v-model="form.name"
                        id="name"
                        type="text"
                        class="validate"
                        autofocus
                        required
                    >
                    <label for="name">題庫名稱</label>
                </div>

                <div class="input-field col s12">
                    <materialize-select
                        :model.sync="form.category_id"
                        :label="'題庫類型'"
                        :key="'name'"
                        :value="'id'"
                        :options="categories"
                    ></materialize-select>
                </div>

                <div class="switch col s12">
                    <label>
                        <span>啟用題庫</span>
                        <input
                            v-model="form.enable"
                            type="checkbox"
                        >
                        <span class="lever"></span>
                    </label>
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
    import materializeSelect from '../../components/form/select.vue'
    import toast from '../../components/toast'

    export default {
        data() {
            return {
                categories: [],

                form: {
                    name: '',
                    category_id: '',
                    enable: false
                }
            }
        },

        methods: {
            store() {
                this.$http.post(`exams`, this.form).then((response) => {
                    this.$router.go({ name: 'admin.exams' })
                }, (response) => {
                    toast.formRequestFailed(response)
                })
            }
        },

        components: {
            materializeSelect
        },

        created() {
            this.$http.get(`categories/f/exam.category`).then((response) => {
                this.categories = response.data.categories
            })
        }
    }
</script>
