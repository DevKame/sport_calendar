

export default {
    userRole(_, _2, rootState) {
        return rootState.logged_role;
    },
    userID(_, _2, rootState) {
        return rootState.logged_id;
    }
}