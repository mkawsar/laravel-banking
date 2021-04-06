const DashboardLayout = () => import('~/components/Dashboard/Layout/DashboardLayout');
const DailySavingsList = () => import('~/components/Dashboard/Views/SysAdmin/Accounting/DailySavings/List');

export default [
    {
        path: '/admin/accounting/daily/savings/',
        component: DashboardLayout,
        redirect: '/admin/overview',
        children: [
            {
                path: 'list',
                name: 'DailySavingsList',
                component: DailySavingsList,
                meta: {title: ' দৈনিক সঞ্চয় লিস্ট', requireAuth: true, roles: ['Admin']}
            },
        ]
    }
]
