<template>
    <div>
        <br><br>
        <a v-link="{ name: 'admin.exams' }">題庫列表</a>
        <a v-link="{ name: 'admin.exams.create' }">新增題庫</a>
        <br><br>
    </div>

    <router-view></router-view>
</template>

<script type="text/babel">
    export default {
        route: {
            canActivate(transition) {
//                let auth = transition.to.router.app.$auth
//
//                if (auth.guest()) {
//                    transition.redirect({name: 'signIn'})
//                } else if (! auth.is('admin')) {
//                    transition.abort('Permission denied.')
//                }

                transition.next()
            }
        }
    }
</script>
