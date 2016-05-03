<template>
    <progress
        :percent.sync="progressConfig.percent"
        :options="progressConfig.options"
    ></progress>

    <div class="container">
        <router-view></router-view>
    </div>
</template>

<script type="text/babel">
    import events from '../../events/events'
    import progress from 'vue-progressbar/vue-progressbar.vue'

    export default {
        data() {
            return {
                progressConfig: {
                    percent: 0,

                    options: {
                        show: true,
                        canSuccess: true,
                        color: '#29d',
                        failedColor: 'red',
                        height: '2px'
                    }
                }
            }
        },

        events,

        components: {
            progress
        },

        created () {
            this.$progress.setHolder(this.progressConfig)

            this.$progress.start()
        },

        ready () {
            this.$progress.finish()
        }
    }
</script>
