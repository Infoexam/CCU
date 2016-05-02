<template>
    <div class="container">
        <router-view></router-view>
    </div>
</template>

<script type="text/babel">
    import Events from '../../events/events'

    export default {
        data() {
            return {
                user: null
            }
        },

        events: Events,

        ready() {
            this.$http.get(`account/profile`).then((response) => {
                this.user = response.data
            }, (response) => {
                this.user = null;
            })
        }
    }
</script>
