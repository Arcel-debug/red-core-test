<template>
    <div>
        <transition name="moveImageInUp" v-if="show_form">
            <div class="modal-mask">
                <div class="modal-wrapper">
                    <div class="modal-container">

                        <div class="clearfix">
                            <div class="float-start"><h2 class="text-center ">Register</h2></div>
                            <div class="float-end">
                                <button class="btn btn-danger btn-sm" @click.prevent="hideModal">&times;</button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div v-html="backend_response"></div>
                            <form @submit.prevent="registerForm" enctype="multipart/form-data" method="POST">
                                <div class="row">
                                    <div class="col-12">
                                        <label for="role_id" class="form-label">Role</label>
                                        <select v-model="role_id" id="role_id" class="form-select">
                                            <option :value="role.id" v-for="role in roles" :key="role.id">{{ role.role_name }}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-12">
                                        <label for="name" class="form-label">Name</label>
                                        <input type="text" class="form-control" placeholder="Name" v-model="user_name" required>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-12">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="email" class="form-control" placeholder="Email" v-model="user_email" required>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-12">
                                        <label for="password">Password</label>
                                        <input type="password" class="form-control" placeholder="Password" v-model="user_password" required>
                                        <small :class="password_rule">Minimum length: 8 characters</small>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-12">
                                        <label for="confirm_password">Confirm Password</label>
                                        <input type="password" class="form-control" placeholder="Confirm Password" v-model="user_confirm_password">
                                        <small :class="confirm_password_rule">Minimum length: 8 characters</small>
                                        <div class="alert alert-success py-0 mt-1" v-if="password_matched">Password Matched!</div>
                                        <div class="alert alert-danger py-0 mt-1" v-if="password_not_matched">Password Does Not Matched.</div>
                                    </div>
                                </div>
                        
                                <div class="row mt-4">
                                    <div class="col col-6">
                                        <!-- <router-link to="/login" class="float-start">Already have an account?</router-link> -->
                                    </div>
                                    <div class="col col-6">
                                        <button class="btn btn-primary float-end" :disabled="save_btn_disabled">Save</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </transition>
    </div>
</template>

<script>
import User from "../../apis/User";
export default {
    name: 'user_add_form',
    data() {
        return {
            user_id: 0,
            user_name: null,
            user_email: null,
            user_password: null,
            user_confirm_password: null,
            password_matched: false,
            password_not_matched: false,
            role_id: 0,
            backend_response: null,
            save_btn_disabled: true,
            password_rule: 'text-danger',
            confirm_password_rule: 'text-danger',
            roles: [],
            show_form: false
        }
    },
    watch: {
        user_password: function (val) {
            val.length > 8 ? this.password_rule = 'text-success' : this.password_rule = 'text-danger';
        },
        user_confirm_password: function (val) {
            val.length > 8 ? this.confirm_password_rule = 'text-success' : this.confirm_password_rule = 'text-danger';

            if (val == '') {
                this.password_matched = false;
                this.password_not_matched = false;
                this.save_btn_disabled = true;
            }
            else
            {
                if (this.user_password == val) {
                    this.password_matched = true;
                    this.password_not_matched = false;
                    this.save_btn_disabled = false;
                }
                else
                {
                    this.password_not_matched = true;
                    this.password_matched = false;
                    this.save_btn_disabled = true;
                }
            }
        }
    },
    mounted() {
        this.getRoles();
    },
    methods: {
        showModal(data) {
            this.show_form = true;
            
        },
        hideModal() {
            this.user_id = 0;
            this.user_name = null;
            this.user_email = null;
            this.user_password = '';
            this.user_confirm_password = '';
            this.role_id = 0;
            this.backend_response = "";
            this.show_form = false;
        },
        getRoles() {
            this.axios.get('/api/user/get_roles')
            .then((response) => {
                this.roles = response.data.data;
            }).catch((err) => {
                this.backend_response = err;
            });
        },
        registerForm() {
            const self = this;
            const form_data = new FormData();
            form_data.append('name', self.user_name);
            form_data.append('email', self.user_email);
            form_data.append('password', self.user_password);
            form_data.append('role_id', self.role_id);
            if (self.user_password == self.user_confirm_password && self.user_password.length > 8 && self.user_confirm_password.length > 8) {
                User.register(form_data).then((response) => {
                    if (response.data.result_code == '1') {
                        self.$emit('updateRecord');
                        self.hideModal();
                    } else {
                        self.backend_response = "<div class='alert alert-danger'>"+response.data.message+"</div>";
                    }
                    
                }).catch((err) => {
                    self.backend_response = "<div class='alert alert-danger'>"+err+"</div>";
                });
            }
        }
    },

}
</script>