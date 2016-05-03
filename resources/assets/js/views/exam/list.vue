<template>
    <div></div>
</template>

<script type="text/babel">
    export default {
        data() {
            return {
                categories: []
            }
        },

        created() {
            this.$http.get(`categories/f/exam.category`).then((response) => {
                this.categories = response.data.categories
            });
        }
    }
</script>
