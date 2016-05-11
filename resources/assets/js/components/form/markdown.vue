<template>
    <div class="row">
        <div class="input-field col s12 m5">
            <textarea
                v-model="model"
                :id="textareaId"
                class="materialize-textarea validate"
                :maxlength="length"
                :length="length"
            ></textarea>

            <label
                :class="{ 'active': model.length > 0 }"
                :for="textareaId"
            >{{ label }}</label>
        </div>

        <div class="col m6 offset-m1 hide-on-small-only">
            <span>預覽</span>

            <markdown :model="model"></markdown>
        </div>
    </div>
</template>

<script type="text/babel">
    import Markdown from '../markdown.vue'
    import Uuid from 'node-uuid'

    export default {
        props: {
            model: {
                twoWay: true,
                required: true
            },

            length: {
                type: Number,
                required: true
            },

            label: {
                type: String,
                required: true
            },

            textareaId: {
                type: String,
                default: null
            }
        },

        components: {
            markdown: Markdown
        },

        created() {
            // The textareaId default value must assign here
            if (null === this.textareaId) {
                this.textareaId = Uuid.v4()
            }
        },

        ready() {
            let textarea = $(`#${this.textareaId}`)

            textarea.trigger('autoresize')
            textarea.characterCounter()
        }
    }
</script>
