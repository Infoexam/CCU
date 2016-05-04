<template>
    <template v-if="available">
        <i class="material-icons green-text">check</i>
    </template>

    <template v-else>
        <i class="material-icons red-text">close</i>
    </template>
</template>

<script type="text/babel">
    export default {
        props: {
            available: {
                type: Boolean,
                required: true
            }
        }
    }
</script>
