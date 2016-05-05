<template>
    <div v-html="model | marked"></div>
</template>

<script type="text/babel">
    import highlight from 'highlight.js'
    import marked from 'marked'
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
                required: true
            }
        },

        filters: {
            marked
        }
    }
</script>
