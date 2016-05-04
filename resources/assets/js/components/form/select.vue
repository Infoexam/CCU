<template>
    <select id="{{ id }}">
        <option value="" disabled selected>Choose your option</option>

        <template v-for="option in options">
            <option value="{{ option[value] }}">{{ option[key] }}</option>
        </template>
    </select>

    <label v-if="label" for="{{ id }}">{{ label }}</label>
</template>

<script type="text/babel">
    import uuid from 'node-uuid'

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

            required: {
                type: Boolean,
                default: false
            },

            label: {
                type: String
            },

            key: {
                type: String,
                required: true
            },

            value: {
                type: String,
                required: true
            },

            options: {
                type: [Array, Object],
                required: true
            }
        },

        data() {
            return {
                _binded: false
            }
        },

        watch: {
            options() {
                let id = `#${this.id}`

                $(id).material_select();

                if (! this._binded) {
                    let _this = this

                    let target = $(id).closest('div.select-wrapper').find('input[data-activates^="select-options"]')

                    $(document).on('change', target, function () {
                        let option = _this.search(target.val())

                        if (null !== option) {
                            _this.model = option.id
                        }
                    })

                    this._binded = true
                }
            }
        },

        methods: {
            search(key) {
                for (let i = 0; i < this.options.length; ++i) {
                    if (this.options[i].name === key) {
                        return this.options[i];
                    }
                }

                return null
            }
        },

        created() {
            if (null === this.id) {
                this.id = uuid.v4()
            }
        }
    }
</script>
