<template>
    <div :class="{'nav-open': $sidebar.showSidebar}">
        <notifications transition-name="notification-list" transition-mode="out-in">

        </notifications>

        <transition name="page" mode="out-in">
            <router-view></router-view>
        </transition>
    </div>
</template>

<script>
    import('sweetalert2/dist/sweetalert2.css');
    import('vue-notifyjs/themes/default.css');
</script>

<style lang="scss">
    .page-enter-active, .page-leave-active {
        transition: opacity .5s, transform .5s;
    }

    .page-leave-to {
        opacity: 0;
        transform: translateX(-1%);
    }

    .page-enter {
        opacity: 0;
        transform: translateX(1%);
    }

    .notifications.vue-notifyjs {
        .notification-list-move {
            transition: transform 0.3s, opacity 0.4s;
        }

        .notification-list-item {
            display: inline-block;
            margin-right: 10px;

        }

        .notification-list-enter-active, .notification-list-leave-active {
            transition: opacity 0.4s;
        }

        .notification-list-enter, .notification-list-leave-to /* .list-leave-active for <2.1.8 */
        {
            opacity: 0;
        }
    }
</style>
