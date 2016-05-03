<template>
    <router-view></router-view>
</template>

<script>
    export default {
        route: {
            canActivate(transition) {
                let auth = transition.to.router.app.$auth
                
                if (auth.guest()) {
                    transition.redirect({name: 'auth.signIn'})
                } else if (! auth.is('admin')) {
                    transition.abort('Permission denied.')
                }

                transition.next()
            }
        }
    }
</script>
