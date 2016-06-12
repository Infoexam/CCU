<template>
  <div class="row">
    <div class="input-field col s12">
      <input
        v-model="name"
        id="name"
        type="text"
        class="validate"
        maxlength="48"
        autofocus
        required
      >
      <label for="name" :class="{ active: ! create }">題庫名稱</label>
    </div>

    <div class="input-field col s12">
      <materialize-select
        :model.sync="category_id"
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
          v-model="enable"
          type="checkbox"
        >

        <span class="lever"></span>
      </label>
    </div>

    <div class="file-field input-field col s12">
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

    <submit :text="$t(`form.submit.${create ? 'create' : 'update'}`)"></submit>
  </div>
</template>

<script type="text/babel">
  import MaterializeSelect from '../../../components/form/select.vue'
  import Submit from '../../../components/form/submit.vue'

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

    components: {
      MaterializeSelect,
      Submit
    },

    created () {
      this.$http.get('categories/f/exam.category').then(response => {
        this.categories = response.data.categories
      })
    }
  }
</script>
