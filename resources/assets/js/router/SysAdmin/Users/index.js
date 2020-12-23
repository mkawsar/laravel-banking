const DashboardLayout = () => import('~/components/Dashboard/Layout/DashboardLayout');
const UserList = () => import('~/components/Dashboard/Views/SysAdmin/Users/List');
const UserCreate = () => import('~/components/Dashboard/Views/SysAdmin/Users/Create');

export default [
    {
        path: '/admin/user/',
        component: DashboardLayout,
        redirect: '/admin/overview',
        children: [
            {
                path: 'list',
                name: 'UserList',
                component: UserList,
                meta: {title: 'Users', requireAuth: true, roles: ['Admin']}
            },
            {
                path: 'list/create',
                name: 'UserCreate',
                component: UserCreate,
                meta: {title: 'User Create', requireAuth: true, roles: ['Admin']}
            }
        ]
    }
]
