const DashboardLayout = () => import('~/components/Dashboard/Layout/DashboardLayout');
const OverviewSysAdmin = () => import('~/components/Dashboard/Views/SysAdmin/Home/Overview');

export default [
    {
        path: '/admin',
        component: DashboardLayout,
        redirect: '/admin/overview',
        children: [
            {
                path: 'overview',
                name: 'Overview',
                component: OverviewSysAdmin,
                meta: {title: 'Dashboard', requireAuth: true, roles: ['Admin']}
            }
        ]
    }
]
