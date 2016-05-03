<template>
    <router-view></router-view>
</template>

<script>
    import Vue from 'vue'

    export default {
        route: {
            canActivate(transition) {
                if (Vue.auth.guest()) {
                    transition.redirect({name: 'auth.signIn'})
                } else if (! Vue.auth.is('admin')) {
                    transition.abort('Permission denied.')
                }

                transition.next()
            }
        }
    }
</script>
