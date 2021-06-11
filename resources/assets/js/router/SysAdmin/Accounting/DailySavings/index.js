const DashboardLayout = () => import('~/components/Dashboard/Layout/DashboardLayout');
const DailySavingsList = () => import('~/components/Dashboard/Views/SysAdmin/Accounting/DailySavings/List');
const DailySavingsCreate = () => import('~/components/Dashboard/Views/SysAdmin/Accounting/DailySavings/Create');

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
                meta: {title: 'দৈনিক সঞ্চয় লিস্ট', requireAuth: true, roles: ['Admin']}
            },
            {
                path: 'list/add',
                name: 'DailySavingsCreate',
                component: DailySavingsCreate,
                meta: {title: 'দৈনিক সঞ্চয় যোগ', requireAuth: true, roles: ['Admin']}
            },
        ]
    }
]
