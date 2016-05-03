<template>
    <h1>Here is Left Nav</h1>
    <a v-link="{ name: 'admin.exams.create' }">exam create</a>

    <router-view></router-view>
</template>

<script type="text/babel">
    export default {
        route: {
            canActivate(transition) {
//                let auth = transition.to.router.app.$auth
//
//                if (auth.guest()) {
//                    transition.redirect({name: 'auth.signIn'})
//                } else if (! auth.is('admin')) {
//                    transition.abort('Permission denied.')
//                }

                transition.next()
            }
        }
    }
</script>
