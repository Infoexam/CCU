<template>
  <div>
    <div class="input-field">
      <input
        v-model="began_at"
        id="began_at"
        type="datetime-local"
        class="validate"
        required
      >
      <label for="began_at" class="active">開始時間</label>
    </div>

    <div class="input-field">
      <input
        v-model="duration"
        id="duration"
        type="number"
        class="validate"
        min="1"
        max="255"
        required
      >
      <label for="duration" class="active">測驗時長</label>
    </div>

    <div class="input-field">
      <materialize-select
        :model.sync="room"
        :label="'測驗教室'"
        :options="['214', '215', '216', '217']"
      ></materialize-select>
    </div>

    <div class="input-field">
      <input
        v-model="maximum_num"
        id="maximum_num"
        type="number"
        class="validate"
        min="1"
        max="255"
        required
      >
      <label for="maximum_num" class="active">人數限制</label>
    </div>

    <div class="input-field">
      <materialize-select
        :model.sync="apply_type_id"
        :label="'預約類型'"
        :key="'name'"
        :value="'id'"
        :options="applies"
      ></materialize-select>
    </div>

    <div v-if="create" class="input-field">
      <materialize-select
        :model.sync="subject_id"
        :label="'測驗類型'"
        :key="'name'"
        :value="'id'"
        :options="subjects"
      ></materialize-select>
    </div>

    <div v-if="paperable.includes(subject_id)">
      <div v-if="create" class="switch">
        <label>
          <span>隨機出題</span>
          <input v-model="auto_generate" type="checkbox">
          <span class="lever"></span>
        </label>
      </div>

      <div v-if="create && auto_generate">
        <label>題庫選擇</label>

        <div v-for="item in exams" class="input-field" style="margin: 0.6rem 0 0.6rem 1rem;">
          <input
            v-model="exam"
            type="checkbox"
            :id="'auto-generate-' + item.id"
            :value="item.id"
          >
          <label :for="'auto-generate-' + item.id" style="top: 0;">{{ item.name }}</label>
        </div>
      </div>

      <div v-else class="input-field">
        <materialize-select
          :model.sync="paper_id"
          :label="'試卷選擇'"
          :key="'name'"
          :value="'id'"
          :options="papers"
        ></materialize-select>
      </div>
    </div>

    <div class="switch">
      <label>
        <span>開放預約</span>
        <input v-model="applicable" type="checkbox">
        <span class="lever"></span>
      </label>
    </div>

    <submit :text="submitText"></submit>
  </div>
</template>

<script>
  import MaterializeSelect from '~/components/form/select.vue'
  import Submit from '~/components/form/submit.vue'

  export default {
    props: {
      began_at: {
        type: String,
        twoWay: true,
        required: true
      },

      duration: {
        type: [String, Number],
        twoWay: true,
        required: true
      },

      room: {
        type: String,
        twoWay: true,
        required: true
      },

      applicable: {
        type: Boolean,
        twoWay: true,
        required: true
      },

      auto_generate: {
        type: Boolean,
        twoWay: true,
        default: false
      },

      paper_id: {
        type: Number,
        twoWay: true
      },

      exam: {
        type: Array,
        twoWay: true
      },

      apply_type_id: {
        type: Number,
        twoWay: true,
        required: true
      },

      subject_id: {
        type: Number,
        twoWay: true,
        required: true
      },

      maximum_num: {
        type: [String, Number],
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
        applies: [],
        subjects: [],
        paperable: [],
        exams: [],
        papers: []
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
      this.$http.get('categories/f/exam.apply').then(response => {
        for (const category of response.data.categories) {
          category.name = this.$t('apply.' + category.name.replace(/-/g, '_'))
        }

        this.applies = response.data.categories
      })

      this.$http.get('categories/f/exam.subject').then(response => {
        for (const category of response.data.categories) {
          if (category.name.endsWith('theory')) {
            this.paperable.push(category.id)
          }

          category.name = this.$t('listing.' + category.name.replace(/-/g, '_'))
        }

        this.subjects = response.data.categories
      })

      this.$http.get('exams?listing=1').then(response => {
        this.exams = response.data.exams
      })

      this.$http.get('papers?listing=1').then(response => {
        this.papers = response.data.papers
      })
    }
  }
</script>
