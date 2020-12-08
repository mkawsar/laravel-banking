const DashboardLayout = () => import('~/components/Dashboard/Layout/DashboardLayout');
const ProfileEdit = () => import('~/components/Dashboard/Views/Profile/ProfileEdit');
const ProfileView = () => import('~/components/Dashboard/Views/Profile/Profile');
const ChangePasswordView = () => import('~/components/Dashboard/Views/Profile/ChangePassword');

export default [
    {
        path: '/home',
        component: DashboardLayout,
        redirect: '/admin/overview',
        children: [
            {
                path: 'profile/view',
                name: 'ProfileView',
                component: ProfileView,
                meta: {
                    title: 'Profile Information',
                    requireAuth: true,
                    roles: ['Admin', 'Basic', 'Nutritionist', 'Fitness Experts', 'Nutritionist + FitnessExperts']
                }
            },
            {
                path: 'profile/edit',
                name: 'ProfileEdit',
                component: ProfileEdit,
                meta: {
                    title: 'Profile Edit',
                    requireAuth: true,
                    roles: ['Admin', 'Basic', 'Nutritionist', 'Fitness Experts', 'Nutritionist + FitnessExperts']
                }
            },
            {
                path: 'profile/change/password',
                name: 'ChangePasswordView',
                component: ChangePasswordView,
                meta: {
                    title: 'Password Change',
                    requireAuth: true,
                    roles: ['Admin', 'Basic', 'Nutritionist', 'Fitness Experts', 'Nutritionist + FitnessExperts']
                }
            }
        ]
    }
]
