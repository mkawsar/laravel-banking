const DashboardLayout = () => import('~/components/Dashboard/Layout/DashboardLayout');
const TotalSavingsList = () => import('~/components/Dashboard/Views/SysAdmin/Accounting/TotalSavings/List');
const SavingsWithdraw = () => import('~/components/Dashboard/Views/SysAdmin/Accounting/TotalSavings/Withdraw');

const MemberDailySavingsList = () => import('~/components/Dashboard/Views/SysAdmin/Accounting/MemberDetails/DailySavingsList');
const SavingWithdrawHistory = () => import('~/components/Dashboard/Views/SysAdmin/Accounting/MemberDetails/SavingWithdrawHistory');

export default [
    {
        path: '/admin/accounting/total/savings/',
        component: DashboardLayout,
        redirect: '/admin/overview',
        children: [
            {
                path: 'list',
                name: 'TotalSavingsList',
                component: TotalSavingsList,
                meta: {title: 'মোট সঞ্চয় লিস্ট', requireAuth: true, roles: ['Admin']}
            },
            {
                path: 'list/withdraw',
                name: 'SavingsWithdraw',
                component: SavingsWithdraw,
                meta: {title: 'সঞ্চয় উত্তোলন', requireAuth: true, roles: ['Admin']}
            },

            {
                path: 'list/member/:memberID/daily/savings/details',
                name: 'MemberDailySavingsList',
                component: MemberDailySavingsList,
                meta: {title: 'দৈনিক সঞ্চয়ের তালিকা', requireAuth: true, roles: ['Admin']}
            },
            {
                path: 'list/member/:memberID/savings/withdraw/history',
                name: 'SavingWithdrawHistory',
                component: SavingWithdrawHistory,
                meta: {title: 'সঞ্চয় উত্তোলনের তালিকা', requireAuth: true, roles: ['Admin']}
            }
        ]
    }
]
