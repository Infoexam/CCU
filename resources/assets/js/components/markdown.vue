<template>
  <div v-html="model | marked"></div>
</template>

<script type="text/babel">
  import Highlight from 'highlight.js'
  import Marked from 'marked'
  require('highlight.js/styles/github.css')

  const renderer = new Marked.Renderer()

  renderer.image = function (href, title, text) {
    let out = `<img width="100%" src="${href}" alt="${text}"`

    if (title) {
      out += ` title="${title}"`
    }

    out += this.options.xhtml ? '/>' : '>'

    return out
  }

  renderer.link = function (href, title, text) {
    if (this.options.sanitize) {
      try {
        const prot = decodeURIComponent(decodeURI(href))
          .replace(/[^\w:]/g, '')
          .toLowerCase()

        if (prot.includes('javascript:') || prot.includes('vbscript:')) {
          return ''
        }
      } catch (e) {
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

  Marked.setOptions({
    renderer: renderer,

    breaks: true,

    highlight: (code, lang) => {
      return Highlight.highlightAuto(code, [lang]).value
    }
  })

  export default {
    props: {
      model: {
        required: true
      }
    },

    filters: {
      marked: Marked
    }
  }
</script>
