<template>
  <div class="row">
    <template v-for="image in images" track-by="uuid">
      <div class="col s12 m6">
        <input :id="image.uuid" :value="image.url">

        <button
          type="button"
          class="btn clipboard-btn"
          data-clipboard-target="#{{ image.uuid }}"
        ><i class="fa fa-clipboard" aria-hidden="true"></i></button>

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
</template>

<script type="text/babel">
  import Clipboard from 'clipboard'
  import Md5 from 'crypto-js/md5'

  export default {
    data () {
      return {
        images: []
      }
    },

    watch: {
      images () {
        $('.materialboxed').materialbox()

        if (0 < this.images.length) {
          new Clipboard('.clipboard-btn')
        }
      }
    },

    methods: {
      preprocess (images) {
        for (const image of images) {
          image.uuid = `image-${Md5(image.url).toString()}`
        }

        return images
      },

      upload () {
        const files = this.$els.image.files

        if (0 === files.length) {
          return
        }

        const data = new FormData()

        for (let i = 0; i < files.length; ++i) {
          data.append('image[]', files[i])
        }

        this.$http.post(`exams/${this.$route.params.id}/images`, data).then(response => {
          this.images = this.preprocess(response.data)
        })
      }
    },

    ready () {
      this.$http.get(`exams/${this.$route.params.id}/images`).then(response => {
        this.images = this.preprocess(response.data)
      })
    }
  }
</script>
