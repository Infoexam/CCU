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

    let renderer = new marked.Renderer()

    renderer.image = function (href, title, text) {
        let out = `<img width="100%" src="${href}" alt="${text}"`

        if (title) {
            out += ` title="${title}"`
        }

        out += this.options.xhtml ? '/>' : '>'

        return out
    }

    renderer.link = function(href, title, text) {
        if (this.options.sanitize) {
            try {
                let prot = decodeURIComponent(unescape(href))
                    .replace(/[^\w:]/g, '')
                    .toLowerCase()
            } catch (e) {
                return ''
            }

            if (prot.indexOf('javascript:') === 0 || prot.indexOf('vbscript:') === 0) {
                return ''
            }
        }

        let out = `<a href="${href}" target="_blank"`

        if (title) {
            out += ` title="${title}"`
        }

        out += `>${text}</a>`

        return out
    }

    marked.setOptions({
        renderer: renderer,

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
            textarea.characterCounter()
        }
    }
</script>
