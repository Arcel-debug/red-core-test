<template>
    <div class="container"> 
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="container-fluid">
                <h2 class="navbar-brand">Red Core Test</h2>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item" v-if="is_user_logged">
                            <router-link class="nav-link" aria-current="page" to="/">Users</router-link>
                        </li>
                        <li class="nav-item" v-if="is_user_logged">
                            <router-link class="nav-link" to="/roles">Roles</router-link>
                        </li>
                    </ul>

                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item" v-if="is_user_logged == false">
                            <router-link to="/login" class="nav-link">Log In</router-link>
                        </li>
                        <li class="nav-item" v-if="is_user_logged == false">
                            <a type="button" to="/login" class="nav-link" @click.prevent="showRegisterModal(0)">Register</a>
                        </li>
                        <li class="nav-item dropdown" v-if="is_user_logged">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle btn fs-875-rem" type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                {{logged_user_name}}
                            </a>

                            <div class="dropdown-menu dropdown-menu-left">
                                <button class="dropdown-item" v-on:click.prevent="authLogout()">
                                    Logout
                                </button>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
            <register ref="register_modal"/>
        </nav>

        <div class="container mt-3" v-if="auth_error_message">
            <div class="alert alert-danger">{{ auth_error_message }}</div>
        </div>

        <div class="mt-3">
            <transition name="moveInUp">
                <router-view :isAuthResult="is_user_logged" :appData="$data"></router-view>
            </transition>
        </div>
    </div>
</template>
 
<script>
import User from "./apis/User";
import Register from './components/Register.vue';
    export default {
        data() {
            return {
                is_user_logged: false,
                logged_user_keyid: 0,
                logged_user_name: null,
                logged_user_email: null,
                showModal: false,
                auth_error_message: null
            }
        },
        created() {
            if (User.auth()) {
                this.getUserData();
            }
        },
        methods: {
            getUserData() {
                this.axios.get('/api/user/get_user_details', {params: { keyid: sessionStorage.getItem('keyid')}} ).then((response) => {
                    this.is_user_logged = true;
                    this.logged_user_name = response.data.data.name;
                    this.logged_user_email = response.data.data.email;
                }).catch((err) => {
                    this.auth_error_message = err;
                });
            },
            authLogout() {
                var self = this;
                sessionStorage.setItem('auth', '0');
                sessionStorage.setItem('data', '0');
                window.location.href = '/login';
            },
            hideAlert() {
                const self = this;
                self.response_class = null;
                self.response_message = null;
            },
            showRegisterModal(user_id) {
                this.axios.get('/api/user/edit', {params: {keyid: user_id}}).then((response) => {
                    response.data.result_code == '1' ? this.$refs.register_modal.showModal(response.data.data)
                    : this.backend_response = "<div class='alert alert-danger'>"+response.data.message+"</div>";
                }).catch((err) => {
                    this.backend_response = "<div class='alert alert-danger'>"+err+"</div>";
                });
            },
        },
        components: {
            Register
        }
    }
</script>

<style lang="scss">
    .moveInUp-enter-active{
        animation: fadeIn 1s ease-in;
    }
    @keyframes fadeIn{
        0%{
            opacity: 0;
        }
        50%{
            opacity: 0.5;
        }
        100%{
            opacity: 1;
        }
    }
    .modal-mask {
        position: fixed;
        z-index: 9998;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        display: table;
        transition: opacity 0.3s ease;
    }

    .modal-wrapper {
        display: table-cell;
        vertical-align: middle;
    }

    .modal-container {
        width: 70%;
        margin: 0px auto;
        padding: 20px 30px;
        background-color: #fff;
        border-radius: 2px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.33);
        transition: all 0.3s ease;
        font-family: Helvetica, Arial, sans-serif;
        overflow-y: auto;
    }

    .modal-container-sm {
        width: 600px;
        height: 250px;
        margin: 0px auto;
        padding: 20px 30px;
        background-color: #fff;
        border-radius: 2px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.33);
        transition: all 0.3s ease;
        font-family: Helvetica, Arial, sans-serif;
        overflow-y: auto;
    }

    .modal-header h3 {
        margin-top: 0;
        color: #42b983;
    }

    .modal-body {
        margin: 20px 0;
    }

    .modal-default-button {
        float: right;
    }

    .modal-enter {
        opacity: 0;
    }

    .modal-leave-active {
        opacity: 0;
    }

    .modal-enter .modal-container,
    .modal-leave-active .modal-container {
        -webkit-transform: scale(1.1);
        transform: scale(1.1);
    }
    .fs-875-rem {
        font-size:.875rem;
    }
</style>