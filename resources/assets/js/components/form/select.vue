<template>
  <select :id="id">
    <option value="" disabled selected>{{ prompt }}</option>

    <template v-for="option in options">
      <option
        value="{{ null === value ? option : option[value] }}"
      >{{ null === key ? option : option[key] }}</option>
    </template>
  </select>

  <label v-if="label" :for="id">{{ label }}</label>
</template>

<script type="text/babel">
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
        _binded: false
      }
    },

    watch: {
      options () {
        const select = $(`#${this.id}`)

        select.material_select()

        if (! this._binded) {
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

          this._binded = true
        }
      }
    },

    methods: {
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
    }
  }
</script>
