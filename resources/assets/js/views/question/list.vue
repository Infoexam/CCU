<template>
    <div></div>
</template>

<script type="text/babel">
    import cache from '../../components/cache'

    export default {
        data() {
            return {
            }
        },

        methods: {
        },

        ready() {
            cache.getItem(`questions`)
            console.log(this.$route.params.id)
        }
    }
</script>
