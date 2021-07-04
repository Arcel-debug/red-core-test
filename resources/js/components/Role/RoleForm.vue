<template>
    <div>
        <transition name="moveImageInUp" v-if="show_form">
            <div class="modal-mask">
                <div class="modal-wrapper">
                    <div class="modal-container">

                        <div class="clearfix">
                            <div class="float-start"><h2 class="text-center ">Role</h2></div>
                            <div class="float-end">
                                <button class="btn btn-danger btn-sm" @click.prevent="hideModal">&times;</button>
                            </div>
                        </div>

                        <div class="card-body">
                            <div v-html="backend_response"></div>
                            <form @submit.prevent="submitForm" enctype="multipart/form-data" method="POST">
                                <div class="row">
                                    <div class="col col-12">
                                        <label for="role_name" class="form-label">Role Name</label>
                                        <input type="text" class="form-control" placeholder="Role name" v-model="role_name" required>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col col-12">
                                        <label for="role_description" class="form-label">Role Description</label>
                                        <textarea type="text" class="form-control" placeholder="Role description" v-model="role_description" required></textarea>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-12">
                                        <button type="submit" class="btn btn-primary">
                                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true" v-show="spinner"></span>
                                            Save
                                        </button>
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
export default {
    name: 'role_form',
    data() {
        return {
            role_id: 0,
            role_name: 0,
            role_description: null,
            show_form: false,
            backend_response: "",
            spinner: false,
        }
    },
    methods: {
        showModal(data) {
            this.role_id = data.id;
            this.role_name = data.role_name;
            this.role_description = data.role_description;
            this.show_form = true;
        },
        hideModal() {
            this.backend_response = "";
            this.show_form = false;
        },
        submitForm() {
            const self = this;
            self.spinner = true;
            const form_data = new FormData();
            form_data.append('keyid', self.role_id);
            form_data.append('role_name', self.role_name);
            form_data.append('role_description', self.role_description);
            self.axios.post('/api/role/update', form_data).then((response) => {
                if (response.data.result_code == '1') {
                    self.$emit('updateRecord', response.data.data);
                    self.hideModal();
                } else {
                    self.backend_response = "<div class='alert alert-danger'>"+response.data.message+"</div>";
                }
            }).catch((err) => {
                self.backend_response = "<div class='alert alert-danger'>"+err+"</div>";
            }).finally(() => {
                self.spinner = false;
            });
        },
    },
}
</script>