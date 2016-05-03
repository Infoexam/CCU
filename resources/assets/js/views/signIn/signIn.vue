<template>
    <div class="row">
        <form @submit.prevent="signIn" class="col s12">
            <div class="row">
                <div class="input-field col s12">
                    <i class="material-icons prefix">account_circle</i>
                    <input
                        v-model="form.username"
                        id="username"
                        type="text"
                        class="validate"
                        autofocus
                        required
                    >
                    <label for="username">{{ $t("auth.username") }}</label>
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
                    <label for="password">{{ $t("auth.password") }}</label>
                </div>

                <div class="input-field col s12 center">
                    <button class="btn waves-effect waves-light" type="submit">
                        <span>{{ $t("auth.signIn") }}</span>
                        <i class="material-icons right">send</i>
                    </button>
                </div>
            </div>
        </form>
    </div>
</template>

<script type="text/babel">
    import Auth from '../../services/auth/index'

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
                this.$auth.signIn(this.form)
            }
        }
    }
</script>
