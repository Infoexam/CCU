<template>
  <select :id="id">
    <option
      v-if="! model"
      value="" disabled selected
    >{{ prompt }}</option>

    <template v-for="option in options">
      <option
        :value="null === value ? option : option[value]"
        :selected.once="model === (null === value ? option : option[value])"
      >{{ null === key ? option : option[key] }}</option>
    </template>
  </select>

  <label v-if="label" :for="id">{{ label }}</label>
</template>

<script>
  import Uuid from 'node-uuid'

  export default {
    props: {
      model: {
        twoWay: true,
        required: true
      },

      id: {
        type: String
      },

      label: {
        type: String
      },

      key: {
        type: String,
        default: null
      },

      value: {
        type: String,
        default: null
      },

      options: {
        type: [Array, Object],
        required: true
      },

      prompt: {
        type: String,
        default: 'Choose your option'
      }
    },

    data () {
      return {
        binded: {
          model: false,
          options: false
        }
      }
    },

    watch: {
      model () {
        if (! this.binded.model && '' !== this.model) {
          this.init()

          this.binded.model = true
        }
      },

      options () {
        if (! this.binded.options) {
          this.init()

          this.binded.options = true
        }
      }
    },

    methods: {
      init () {
        const select = $(`#${this.id}`)

        if (this.binded.model || this.binded.options) {
          $(select).off('change', select.closest('div.select-wrapper').find('input[data-activates^="select-options"]'))

          select.material_select('destroy')
        }

        select.material_select()

        const target = select.closest('div.select-wrapper').find('input[data-activates^="select-options"]')

        $(select).on('change', target, () => {
          if (null === this.value) {
            this.model = target.val()

            return
          }

          const option = this.search(target.val())

          if (null !== option) {
            this.model = option[this.value]
          }
        })
      },

      search (key) {
        for (const option of this.options) {
          if (key === option[this.key]) {
            return option
          }
        }

        return null
      }
    },

    created () {
      // The id default value must assign here
      this.id = this.id || Uuid.v4()
    },

    ready () {
      if (! this.binded.options || ! this.binded.model) {
        this.init()
      }
    }
  }
</script>
