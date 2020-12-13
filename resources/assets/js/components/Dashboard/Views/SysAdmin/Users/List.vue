<template>
    <div class="row">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb breadcrumb-css">
                <li class="breadcrumb-item">
                    <router-link :to="{name: 'Overview'}">Home</router-link>
                </li>
                <li class="breadcrumb-item active" aria-current="page">List</li>
            </ol>
        </nav>
        <div class="col-md-12 card">
            <div class="card-header">
                <h4 class="card-title">
                    <router-link
                        :to="{name: 'UserCreate'}"
                        class="btn btn-outline btn-success">Add User
                    </router-link>
                </h4>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {}
    },
    methods: {},
    mounted() {
    }
}
</script>
