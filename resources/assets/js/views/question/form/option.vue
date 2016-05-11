<template>
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
                    v-model="multiple"
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
</template>

<script type="text/babel">
    import Markdown from '../../../components/form/markdown.vue'
    import MaterializeSelect from '../../../components/form/select.vue'

    export default {
        props: {
            multiple: {
                twoWay: true,
                type: Boolean,
                required: true
            }
        },

        components: {
            markdown: Markdown,
            materializeSelect: MaterializeSelect
        }
    }
</script>
