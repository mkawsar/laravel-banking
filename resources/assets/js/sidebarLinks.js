export default [
    /*----------- SysAdmin -----------*/
    {
        name: 'Dashboard',
        icon: 'ti-dashboard',
        path: '/admin/overview',
        meta: {requireAuth: true, roles: ['Admin']},
    }
]
