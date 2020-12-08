export default {
    getAllRoles: function (env) {
        return axios.get(env.GATEWAY_API + 'admin/user/roles/all');
    }
}
