<template>
    <div class="user">
        <div class="photo">
            <img :src="avatar"/>
        </div>
        <div class="info">
            <a data-toggle="collapse" @click="toggleMenu" href="javascript:void(0)">
        <span>
        {{ user.name }}
        <b class="caret"></b>
        </span>
            </a>
            <div class="clearfix"></div>
            <div style="margin-left: 3em;">
                <el-collapse-transition>
                    <ul class="nav" v-show="!isClosed">
                        <li>
                            <a href="javascript:void(0)" @click="profilePage">
                                <span class="sidebar-normal">My Profile</span>
                            </a>
                        </li>
                        <li>
                            <a href="javascript:void(0)" @click="profileEditPage">
                                <!--<span class="sidebar-mini">Ep</span>-->
                                <span class="sidebar-normal">Edit Profile</span>
                            </a>
                        </li>
                        <li>
                            <a href="javascript:void(0)" @click="passwordChange">
                                <!--<span class="sidebar-mini">Ep</span>-->
                                <span class="sidebar-normal">Change Password</span>
                            </a>
                        </li>
                        <li>
                            <a href="javascript:void(0)" @click="logoutUser">
                                <span class="text-danger">
                                    <!--<span class="sidebar-mini">L</span>-->
                                    <span class="sidebar-normal">Log Out</span>
                                </span>
                            </a>
                        </li>
                    </ul>
                </el-collapse-transition>
            </div>
        </div>
    </div>
</template>

<script>
    import CollapseTransition from 'element-ui/lib/transitions/collapse-transition'
    import EventBus from "../../../EventBus";

    export default {
        components: {
            [CollapseTransition.name]: CollapseTransition
        },

        data() {
            return {
                isClosed: true,
                avatar: ''
            }
        },

        computed: {
            user() {
                return this.$store.getters.user;
            }
        },

        methods: {
            toggleMenu() {
                this.isClosed = !this.isClosed
            },
            logoutUser() {
                axios.get(this.$env.BACKEND_API + 'auth/user/logout')
                .then(res => {
                    if (res) {
                        this.$localStorage.clear();
                        this.$router.push({name: 'login'});
                    }
                })
                .catch(err => {
                    console.log(err);
                })
            },
            profileEditPage() {
                this.$router.push({name: 'ProfileEdit'});
            },
            profilePage() {
                this.$router.push({name: 'ProfileView'});
            },
            passwordChange() {
                this.$router.push({name: 'ChangePasswordView'})
            }
        },
        mounted() {
            this.$store.commit('setUser', this.$localStorage.get('user'));
            this.avatar = this.$store.state.user.picture;
            EventBus.$on('UPDATE_AUTH_USER_PROFILE', () => {
                axios.get(this.$env.BACKEND_API + 'auth/profile')
                    .then(res => {
                        this.$localStorage.set('user', res.data.user);
                        this.$store.commit('setUser', this.$localStorage.get('user'));
                        this.avatar = this.$store.state.user.profile_picture;
                    })
                    .catch(err => {
                        console.log(res.response);
                    })
            });
        }
    }
</script>

<style>
    .collapsed {
        transition: opacity 1s;
    }
</style>
