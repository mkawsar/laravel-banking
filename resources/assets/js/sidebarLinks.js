export default [
    /*----------- SysAdmin -----------*/
    {
        name: 'ড্যাশবোর্ড',
        icon: 'ti-dashboard',
        path: '/admin/overview',
        meta: {requireAuth: true, roles: ['Admin']},
    },
    {
        name: 'উজার্স',
        icon: 'fa fa-users',
        path: '/admin/user/list',
        meta: {requireAuth: true, roles: ['Admin']},
    },
    {
        name: 'মেম্বার্স',
        icon: 'fa fa-user-o',
        path: '/admin/member/list',
        meta: {requireAuth: true, roles: ['Admin']},
    },
    {
        name: 'মেম্বার রুট লিস্ট',
        icon: 'fa fa-road',
        path: '/admin/member/routes/list',
        meta: {requireAuth: true, roles: ['Admin']},
    },
    {
        name: 'দৈনিক সঞ্চয়',
        icon: 'fa fa-life-ring',
        path: '/admin/accounting/daily/savings/list',
        meta: {requireAuth: true, roles: ['Admin']},
    }
]
