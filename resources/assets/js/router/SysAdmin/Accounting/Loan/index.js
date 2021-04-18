const DashboardLayout = () => import('~/components/Dashboard/Layout/DashboardLayout');
const MemberLoanList = () => import('~/components/Dashboard/Views/SysAdmin/Accounting/Loan/List');
const MemberLoanAdd = () => import('~/components/Dashboard/Views/SysAdmin/Accounting/Loan/Add');

const MemberLoanPaymentList = () => import('~/components/Dashboard/Views/SysAdmin/Accounting/Loan/Payment/List');

export default [
    {
        path: '/admin/accounting/member/loan/',
        component: DashboardLayout,
        redirect: '/admin/overview',
        children: [
            {
                path: 'list',
                name: 'MemberLoanList',
                component: MemberLoanList,
                meta: {title: 'লোন লিস্ট', requireAuth: true, roles: ['Admin']}
            },
            {
                path: 'list/add',
                name: 'MemberLoanAdd',
                component: MemberLoanAdd,
                meta: {title: 'লোন প্রদান', requireAuth: true, roles: ['Admin']}
            },
            {
                path: 'list/:loanID/member/:memberID/payment/list',
                name: 'MemberLoanPaymentList',
                component: MemberLoanPaymentList,
                meta: {title: 'লোন প্রদান লিস্ট', requireAuth: true, roles: ['Admin']}
            },
        ]
    }
]
