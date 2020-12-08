<template>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <form>
                    <div class="card-header">
                        <h4 class="card-title">
                            Profile Information
                        </h4>
                    </div>
                    <div class="card-content">
                        <div class="form-group">
                            <label for="name" class="control-label">Name</label>
                            <input type="text"
                                   disabled
                                   name="name"
                                   id="name"
                                   class="form-control"
                                   v-model="user.name">
                        </div>
                        <div class="form-group">
                            <label for="email" class="control-label">Email</label>
                            <input type="email"
                                   disabled
                                   name="email"
                                   id="email"
                                   class="form-control"
                                   v-model="user.email">
                        </div>
                        <div class="form-group">
                            <label for="phone" class="control-label">Phone</label>
                            <input type="text"
                                   disabled
                                   name="phone"
                                   id="phone"
                                   class="form-control"
                                   v-model="user.phone">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>
<script>
    let userObj = {
        name: '',
        email: '',
        phone: ''
    };
    export default {
        data: function () {
            return {
                editData: {},
                roles: [],
                user: userObj,
            }
        },
        methods: {
            getUserDetail() {
                axios.get(this.$env.BACKEND_API + 'auth/profile')
                    .then((response) => {
                        this.user = response.data.user;
                    })
                    .catch((error) => {
                        console.log(error.data);
                    });
            },
            getError: function (fieldName) {
                return this.errors.first(fieldName)
            },
        },
        mounted: function () {
            this.getUserDetail()
        }
    }
</script>
<style>
</style>
