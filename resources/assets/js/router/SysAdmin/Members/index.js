const DashboardLayout = () => import('~/components/Dashboard/Layout/DashboardLayout');
const MemberList = () => import('~/components/Dashboard/Views/SysAdmin/Members/List');
const MemberCreate = () => import('~/components/Dashboard/Views/SysAdmin/Members/Create');

export default [
    {
        path: '/admin/member/',
        component: DashboardLayout,
        redirect: '/admin/overview',
        children: [
            {
                path: 'list',
                name: 'MemberList',
                component: MemberList,
                meta: {title: 'Member List', requireAuth: true, roles: ['Admin', 'User']}
            },
            {
                path: 'list/create',
                name: 'MemberCreate',
                component: MemberCreate,
                meta: {title: 'Member Create', requireAuth: true, roles: ['Admin', 'User']}
            },
        ]
    }
]