export default [
    /*----------- SysAdmin -----------*/
    {
        name: 'Dashboard',
        icon: 'ti-dashboard',
        path: '/admin/overview',
        meta: {requireAuth: true, roles: ['Admin']},
    },
    {
        name: 'Users',
        icon: 'fa fa-users',
        path: '/admin/user/list',
        meta: {requireAuth: true, roles: ['Admin']},
    },
    {
        name: 'Members',
        icon: 'fa fa-user-o',
        path: '/admin/member/list',
        meta: {requireAuth: true, roles: ['Admin']},
    }
]
