<template>
    <div class="container">
        <router-view></router-view>
    </div>
</template>

<script type="text/babel">
    import Events from '../../events/events'

    export default {
        events: Events
    }
</script>
