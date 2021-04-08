import DashboardLayout from '../components/Dashboard/Layout/DashboardLayout';

const Login = () => import('~/components/Auth/Login');
const Register = () => import('~/components/Auth/register');
const ForgetPassword = () => import('~/components/Auth/ForgetPassword');

import profileRoute from '~/router/Profile/Home';

/*----------- SysAdmin -----------*/
import homeRouteSysAdmin from '~/router/SysAdmin/Home';
import UsersRoute from '~/router/SysAdmin/Users';
import MembersRoute from '~/router/SysAdmin/Members';
import AccountingDailySavingRoute from '~/router/SysAdmin/Accounting/DailySavings';
import AccountingTotalSavingRoute from '~/router/SysAdmin/Accounting/TotalSavings';

const baseRoutes = [
    {
        path: '/',
        component: DashboardLayout,
        redirect: '/admin/overview',
        meta: {requireAuth: true}
    },

    {path: '/login', component: Login, name: 'login'},
    {path: '/register', component: Register, name: 'register'},
    {path: '/forget/password', component: ForgetPassword, name: 'forget-password'},

    {path: '*', component: require('../components/Errors/404'), name: '404'}
];

const routes = baseRoutes.concat(
    profileRoute,
    homeRouteSysAdmin,
    UsersRoute,
    MembersRoute,
    AccountingDailySavingRoute,
    AccountingTotalSavingRoute
);

export default routes;
