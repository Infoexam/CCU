<template>
  <div>
    <div class="input-field">
      <input
        v-model="name"
        id="name"
        type="text"
        class="validate"
        maxlength="48"
        length="48"
        required
      >
      <label for="name" :class="{ active: ! create }">題庫名稱</label>
    </div>

    <div class="input-field">
      <materialize-select
        :model.sync="category_id"
        :label="'題庫類型'"
        :key="'name'"
        :value="'id'"
        :options="categories"
      ></materialize-select>
    </div>

    <div class="switch">
      <label>
        <span>啟用題庫</span>
        <input v-model="enable" type="checkbox">
        <span class="lever"></span>
      </label>
    </div>

    <div class="file-field input-field">
      <div class="btn">
        <span>封面相片</span>

        <input
          v-el:cover
          type="file"
          accept="image/*"
          :required="create"
        >
      </div>

      <div class="file-path-wrapper">
        <input class="file-path validate" type="text">
      </div>
    </div>

    <div class="file-field input-field">
      <div class="btn">
        <span>附件檔案</span>

        <input
          v-el:attachment
          type="file"
        >
      </div>

      <div class="file-path-wrapper">
        <input class="file-path validate" type="text" placeholder="僅支援壓縮黨">
      </div>
    </div>

    <submit :text="submitText"></submit>
  </div>
</template>

<script>
  import MaterializeSelect from '~/components/form/select.vue'
  import Submit from '~/components/form/submit.vue'

  export default {
    props: {
      name: {
        twoWay: true,
        required: true
      },

      category_id: {
        twoWay: true,
        required: true
      },

      enable: {
        twoWay: true,
        required: true
      },

      create: {
        type: Boolean,
        default: false
      }
    },

    data () {
      return {
        categories: []
      }
    },

    computed: {
      submitText () {
        return this.$t(`form.submit.${this.create ? 'create' : 'update'}`)
      }
    },

    components: {
      MaterializeSelect,
      Submit
    },

    created () {
      this.$http.get('categories/f/exam.category').then(response => {
        for (const category of response.data.categories) {
          category.name = this.$t('exam.' + category.name)
        }

        this.categories = response.data.categories
      })
    },

    ready () {
      $('#name').characterCounter()
    }
  }
</script>
