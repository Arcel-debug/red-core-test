<template>
    <div>
        <transition name="moveImageInUp" v-if="show_form">
            <div class="modal-mask">
                <div class="modal-wrapper">
                    <div class="modal-container">

                        <div class="clearfix">
                            <div class="float-start"><h2 class="text-center ">Update User</h2></div>
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
                        
                                <div class="row mt-4">
                                    <div class="col col-6">
                                        <!-- <router-link to="/login" class="float-start">Already have an account?</router-link> -->
                                    </div>
                                    <div class="col col-6">
                                        <button class="btn btn-primary float-end">Save</button>
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
    name: 'add_user_form',
    data() {
        return {
            user_id: 0,
            user_name: null,
            user_email: null,
            role_id: 0,
            backend_response: null,
            roles: [],
            show_form: false
        }
    },
    watch: {

    },
    mounted() {
        this.getRoles();
    },
    methods: {
        showModal(data) {
            this.user_id = data.id;
            if (data.id != '0') {
                this.user_name = data.name;
                this.user_email = data.email;
                this.role_id = data.role_id;
            }
            this.show_form = true;
            
        },
        hideModal() {
            this.user_id = 0;
            this.user_name = null;
            this.user_email = null;
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
            form_data.append('keyid', self.user_id);
            form_data.append('name', self.user_name);
            form_data.append('email', self.user_email);
            form_data.append('role_id', self.role_id);
            self.axios.post('/api/user/update', form_data).then((response) => {
                if (response.data.result_code == '1') {
                    self.backend_response = "<div class='alert alert-success'>"+response.data.message+"</div>";
                    self.$emit('updateRecord', response.data.data);
                    self.hideModal();
                } else {
                    self.backend_response = "<div class='alert alert-danger'>"+response.data.message+"</div>";
                }
                    
            }).catch((err) => {
                self.backend_response = "<div class='alert alert-danger'>"+err+"</div>";
            });
        }
    },

}
</script>