<template>
    <div>
        <div class="container mt-5 d-flex justify-content-center">
            <div class="card card-body shadow" v-if="!is_user_logged">
                <h1 class="text-center">SIGN IN</h1>
                <div class="alert alert-success" v-show="login_success">Logged In Successfully!</div>
                <div class="alert alert-danger" v-show="login_fail">{{ login_fail }}</div>
                <form @submit.prevent="loginForm" method="POST">
                    <div class="row">
                        <div class="col col-12">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" id="email" v-model="user_email" required>
                        </div>
                        <div class="col col-12 mt-2">
                            <label for="password">Password</label>
                            <input type="password" class="form-control" id="password" v-model="user_password" required>
                        </div>
                        <div class="col col-6 mt-4">
                            <!-- <router-link to="/register">No account? Register now!</router-link> -->
                        </div>
                        <div class="col col-6 mt-4 text-end">
                            <button class="btn btn-primary" type="submit">
                                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true" v-show="spinner"></span>
                                Log In
                            </button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="card card-body shadow" v-else>
                <div class="alert alert-primary">You are already Logged In.</div>
            </div>
        </div>
    </div>
</template>

<script>
import User from "../apis/User";
    export default {
        data() {
            return {
                is_user_logged: false,
                user_email: null,
                user_password: null,
                login_success: false,
                login_fail: false,
                spinner: false
            }
        },
        props: {
            appData: Object
        },
        created() {
            this.is_user_logged = this.$props.appData.is_user_logged;
        },
        methods: {
            loginForm() {
                var self = this;
                self.spinner = true;
                var params = {
                    email: self.user_email,
                    password: self.user_password
                }
                User.login(params).then((response) => {
                    if (response.data.result_code == '1') {
                        this.$root.$emit("isAuth", true);
                        sessionStorage.setItem("auth", '1');
                        sessionStorage.setItem("keyid", response.data.data);
                        // this.$router.push({ name: "users" });
                        window.location.href = '/';
                    } else {
                        this.login_fail = response.data.message;
                    }
                }).catch((err) => {
                    this.login_fail = err;
                }).finally(() => {
                    self.spinner = false;
                });
            }
        },
    }
</script>
