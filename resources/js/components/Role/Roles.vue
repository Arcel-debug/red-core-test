<template>
    <div>
        <div class="container" v-if="is_auth">
            <div class="card">
                <div class="card-header">
                    <h5 class="d-inline-block mt-2 nav-link-text">Roles</h5>
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
                                <th>Role Name</th>
                                <th>Description</th>
                                <th class="alert alert-danger">Delete</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="role in roles" :key="role.id">
                                <td>{{ role.id }}</td>
                                <td><span class='btn btn-primary btn-sm rounded' style='cursor: pointer;' @click="showModal(role.id)"> {{role.role_name}}</span></td>
                                <td>{{ role.role_description }}</td>
                                <td><button class="btn btn-danger btn-sm" @click="deleteRecord(role.id)"><i class='fas fa-trash'></i></button></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <role-form ref="role_form_modal" v-on:updateRecord="updateRecord" />
        </div>
        <div v-else>
            <h3 class="text-center">Only Authenticated users can access this page</h3>
        </div>
    </div>
    
</template>

<script>
import User from "../..//apis/User";
import RoleForm from './RoleForm.vue';
export default {
    data() {
        return {
            is_auth: false,
            roles: [],
            backend_response: null,
        }
    },
    mounted() {
        if (User.auth() && User.auth() == '1') {
            this.is_auth = true;
        } else {
            this.is_auth = false;
        }
        this.getRoles();
    },
    methods: {
        getRoles() {
            this.axios.get('/api/role/records').then((response) => {
                this.roles = response.data.data;
            }).catch((err) => {
                this.backend_response = "<div class='alert alert-danger'>"+err+"</div>";
            })
        },
        showModal(role_id) {
            this.axios.get('/api/role/edit', {params: {keyid: role_id}}).then((response) => {
                if (response.data.result_code == '1') {
                    this.$refs.role_form_modal.showModal(response.data.data);
                }
                else
                {
                    this.backend_response = "<div class='alert alert-danger'>"+response.data.message+"</div>";
                }
                
            }).catch((err) => {
                this.backend_response = "<div class='alert alert-danger'>"+err+"</div>";
            }).finally(() => {
                this.spinner = false;
            });
        },
        updateRecord() {
            this.getRoles();
        },
        deleteRecord(role_id) {
            this.axios.post('/api/role/delete', {params: {keyid: role_id}}).then((response) => {
                if (response.data.result_code == '1') {
                    this.backend_response = "<div class='alert alert-success'>"+response.data.message+"</div>";
                    this.getRoles();
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
        RoleForm
    }
}
</script>
