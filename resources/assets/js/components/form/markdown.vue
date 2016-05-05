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

            <div v-html="model | marked"></div>
        </div>
    </div>
</template>

<script type="text/babel">
    import highlight from 'highlight.js'
    import marked from 'marked'
    import uuid from 'node-uuid'
    require('highlight.js/styles/github.css')

    marked.setOptions({
        breaks: true,

        highlight: (code, lang) => {
            return highlight.highlightAuto(code, [lang]).value
        }
    })

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
            }
        },

        data() {
            return {
                textareaId: uuid.v4()
            }
        },

        filters: {
            marked
        },

        ready() {
            let textarea = $(`#${this.textareaId}`)

            textarea.trigger('autoresize')
            textarea.characterCounter();
        }
    }
</script>
