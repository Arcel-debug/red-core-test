<template>
    <div>
        <div class="container" v-if="is_auth">
            <div class="card">
                <div class="card-header">
                    <h5 class="d-inline-block mt-2 nav-link-text">Users</h5>
                    <div class="d-inline-block float-end">
                        <ul class="list-inline">
                            <li class="list-inline-item"><button class="btn btn-primary" @click.prevent="showModal(0)">Add New</button></li>
                        </ul>
                    </div>
                </div>
                <div class="card-body table-responsive">
                    <div v-html="backend_response"></div>
                    <table class="table table-hover table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th class="alert alert-danger">Delete</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="user in users" :key="user.id">
                                <td>{{ user.id }}</td>
                                <td><span class='btn btn-primary btn-sm rounded' style='cursor: pointer;' @click="showModal(user.id)">{{user.name}}</span></td>
                                <td>{{ user.email }}</td>
                                <td>{{ user.role.role_name }}</td>
                                <td><button class="btn btn-danger btn-sm" @click="deleteRecord(user.id)"><i class='fas fa-trash'></i></button></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <user-add-form ref="user_add_form_modal" v-on:updateRecord="updateRecord"/>
            <user-edit-form ref="user_edit_form_modal" v-on:updateRecord="updateRecord"/>
        </div>
        <div v-else>
            <h3 class="text-center">Only Authenticated users can access this page</h3>
        </div>
    </div>
    
</template>

<script>
import User from "../../apis/User";
import UserEditForm from './UserEditForm.vue';
import UserAddForm from './UserAddForm.vue';
export default {
    data() {
        return {
            user: null,
            is_auth: false,
            users: [],
            backend_response: null
        }
    },
    mounted() {
        if (User.auth() && User.auth() == '1') {
            this.is_auth = true;
        } else {
            this.is_auth = false;
        }
        this.getUsers();
    },
    methods: {
        getUsers() {
            this.axios.get('/api/user/records').then((response) => {
                this.users = response.data.data;
            }).catch((err) => {
                this.backend_response = "<div class='alert alert-danger'>"+err+"</div>";
            })
        },
        showModal(user_id) {
            this.axios.get('/api/user/edit', {params: {keyid: user_id}}).then((response) => {
                if (response.data.data.id == '0') {
                    this.$refs.user_add_form_modal.showModal(response.data.data)
                } else {
                    response.data.result_code == '1' ? this.$refs.user_edit_form_modal.showModal(response.data.data)
                    : this.backend_response = "<div class='alert alert-danger'>"+response.data.message+"</div>";
                }
                
            }).catch((err) => {
                this.backend_response = "<div class='alert alert-danger'>"+err+"</div>";
            });
        },
        updateRecord() {
            this.getUsers();
        },
        deleteRecord(user_id) {
            this.axios.post('/api/user/delete', {params: {keyid: user_id}}).then((response) => {
                if (response.data.result_code == '1') {
                    this.backend_response = "<div class='alert alert-success'>"+response.data.message+"</div>";
                    this.getUsers();
                }
                else
                {
                    this.backend_response = "<div class='alert alert-danger'>"+response.data.message+"</div>";
                }
            }).catch((err) => {
                this.backend_response = "<div class='alert alert-danger'>"+err+"</div>";
            })
        },
    },
    components: {
        UserEditForm,
        UserAddForm
    }
}
</script>
