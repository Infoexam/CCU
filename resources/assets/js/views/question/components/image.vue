<template>
  <div class="file-field input-field">
    <div class="btn">
      <span>上傳圖片</span>

      <input
        v-el:image
        @change="upload()"
        type="file"
        accept="image/*"
        multiple
      >
    </div>

    <div class="file-path-wrapper">
      <input v-el:image-wrapper class="file-path validate" type="text">
    </div>
  </div>

  <br>

  <div class="row">
    <template v-for="image in images" track-by="uuid">
      <div class="col s12 m6">
        <input :id="image.uuid" :value="image.url" style="width: calc(100% - 6rem)">

        <button
          type="button"
          class="btn clipboard-btn"
          data-clipboard-target="#{{ image.uuid }}"
        ><i class="fa fa-clipboard" aria-hidden="true"></i></button>

        <img
          :src="image.url"
          class="materialboxed"
        >
      </div>
    </template>
  </div>
</template>

<script type="text/babel">
  import Clipboard from 'clipboard'
  import Md5 from 'md5'
  import Toast from '../../../components/toast'

  let clipboard = null

  export default {
    data () {
      return {
        images: [],

        binded: false
      }
    },

    watch: {
      images () {
        $('.materialboxed').materialbox()

        if (0 < this.images.length) {
          clipboard = new Clipboard('.clipboard-btn')

          if (! this.binded) {
            clipboard.on('success', function (e) {
              Toast.success('Copied!')

              e.clearSelection()
            })

            clipboard.on('error', function (e) {
              Toast.help('Press ⌘-C to copy')
            })

            this.binded = true
          }
        }
      }
    },

    methods: {
      preprocess (images) {
        for (const image of images) {
          image.uuid = `image-${Md5(image.url)}`
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

        this.$http.post(`exams/${this.$route.params.name}/images`, data).then(response => {
          this.images = this.preprocess(response.data)

          this.$els.imageWrapper.value = ''
        })
      }
    },

    ready () {
      this.$http.get(`exams/${this.$route.params.name}/images`).then(response => {
        this.images = this.preprocess(response.data)
      })
    },

    beforeDestroy () {
      if (null !== clipboard) {
        clipboard.destroy()
      }
    }
  }
</script>
