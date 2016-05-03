<template>
    <h1>前台</h1>

    <a v-if="$auth.guest()" v-link="{ name: 'auth.signIn' }">登入</a>
    <a v-else @click="$auth.signOut()">登出</a>
    <a v-link="{ name: 'admin.exams' }">後台</a>
    <a v-link="{ name: 'auth.signIn' }">登入</a>
</template>

<script type="text/babel">
    export default {
        //
    }
</script>
