import DashboardLayout from '../components/Dashboard/Layout/DashboardLayout';

const Login = () => import('~/components/Auth/Login');
const Register = () => import('~/components/Auth/register');
const ForgetPassword = () => import('~/components/Auth/ForgetPassword');

import profileRoute from '~/router/Profile/Home';

/*----------- SysAdmin -----------*/
import homeRouteSysAdmin from '~/router/SysAdmin/Home';
import UsersRoute from '~/router/SysAdmin/Users';

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
);

export default routes;
