<template>
    <div class="row">
        <form class="col s12">
            <div class="row">
                <div class="input-field col s12">
                    <i class="material-icons prefix">account_circle</i>
                    <input
                        v-model="form.username"
                        id="Username"
                        type="text"
                        class="validate"
                        required
                    >
                    <label for="Username">Username</label>
                </div>

                <div class="input-field col s12">
                    <i class="material-icons prefix">lock</i>
                    <input
                        v-model="form.password"
                        id="password"
                        type="password"
                        class="validate"
                        required
                    >
                    <label for="password">Password</label>
                </div>
            </div>
        </form>
    </div>
</template>

<script type="text/babel">
    export default {
        data() {
            return {
                form: {
                    username: '',
                    password: ''
                }
            }
        },

        methods: {
            signIn() {
                this.$http.post('auth/sign-in').then((response) => {

                })
            }
        }
    }
</script>
