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
                type: String,
                default: null
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

        data() {
            return {
                _binded: false
            }
        },

        watch: {
            options() {
                let select = $(`#${this.id}`)

                select.material_select();

                if (! this._binded) {
                    let _this = this

                    let target = select.closest('div.select-wrapper').find('input[data-activates^="select-options"]')

                    $(select).on('change', target, function () {
                        if (null === _this.value) {
                            _this.model =  target.val()

                            return
                        }

                        let option = _this.search(target.val())

                        if (null !== option) {
                            _this.model = option[_this.value]
                        }
                    })

                    this._binded = true
                }
            }
        },

        methods: {
            search(key) {
                for (let option of this.options) {
                    if (key === option[this.key]) {
                        return option
                    }
                }

                return null
            }
        },

        created() {
            // The id default value must assign here
            if (null === this.id) {
                this.id = Uuid.v4()
            }
        }
    }
</script>
