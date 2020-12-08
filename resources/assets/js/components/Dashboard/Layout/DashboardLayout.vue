<template>
    <div class="wrapper">
        <side-bar type="sidebar" :sidebar-links="$sidebar.sidebarLinks">
            <user-menu></user-menu>
            <mobile-menu></mobile-menu>
        </side-bar>

        <div class="main-panel">
            <top-navbar></top-navbar>

            <dashboard-content @click.native="toggleSidebar">

            </dashboard-content>

            <content-footer></content-footer>
        </div>
    </div>
</template>

<script>
    import TopNavbar from './TopNavbar';
    import ContentFooter from './ContentFooter';
    import DashboardContent from './Content';
    import UserMenu from '../../UIComponents/SidebarPlugin/UserMenu';
    import MobileMenu from '../../UIComponents/SidebarPlugin/MobileMenu';

    export default {
        components: {
            TopNavbar: TopNavbar,
            ContentFooter: ContentFooter,
            DashboardContent: DashboardContent,
            UserMenu: UserMenu,
            MobileMenu: MobileMenu
        },

        methods: {
            toggleSidebar () {
                if (this.$sidebar.showSidebar) {
                    this.$sidebar.displaySidebar(false)
                }
            }
        }
    }
</script>

<style scoped>

</style>
