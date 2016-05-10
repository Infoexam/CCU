<template>
    <i
        :class="[available ? 'green-text' : 'red-text']"
        class="material-icons"
    >{{ available ? 'check' : 'close' }}</i>
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
