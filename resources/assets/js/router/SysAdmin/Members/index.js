const DashboardLayout = () => import('~/components/Dashboard/Layout/DashboardLayout');
const MemberList = () => import('~/components/Dashboard/Views/SysAdmin/Members/List');
const MemberCreate = () => import('~/components/Dashboard/Views/SysAdmin/Members/Create');
const MemberEdit = () => import('~/components/Dashboard/Views/SysAdmin/Members/Edit');
const MemberDetails = () => import('~/components/Dashboard/Views/SysAdmin/Members/Details');

// Member Route list
const MemberRouteList = () => import('~/components/Dashboard/Views/SysAdmin/Members/Routes/List');
const MemberRouteDetails = () => import('~/components/Dashboard/Views/SysAdmin/Members/Routes/Details');


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
            {
                path: 'list/:memberID/edit',
                name: 'MemberEdit',
                component: MemberEdit,
                meta: {title: 'Member Edit', requireAuth: true, roles: ['Admin', 'User']}
            },
            {
                path: 'list/:memberID/details',
                name: 'MemberDetails',
                component: MemberDetails,
                meta: {title: 'Member Details', requireAuth: true, roles: ['Admin', 'User']}
            },
        ]
    },
    {
        path: '/admin/member/routes/',
        component: DashboardLayout,
        redirect: '/admin/overview',
        children: [
            {
                path: 'list',
                name: 'MemberRouteList',
                component: MemberRouteList,
                meta: {title: 'Member Route List', requireAuth: true, roles: ['Admin', 'User']}
            },
            {
                path: 'list/:routeID/details',
                name: 'MemberRouteDetails',
                component: MemberRouteDetails,
                meta: {title: 'Member Route Details', requireAuth: true, roles: ['Admin', 'User']}
            }
        ]
    }
]
