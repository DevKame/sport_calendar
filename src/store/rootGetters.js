

export default {
    getLoggedUserFirstname(state) {
        return state.logged_firstname;
    },
    getLoggedUserLastname(state) {
        return state.logged_lastname;
    },
    getLoggedUserRole(state) {
        return state.logged_role;
    },
    getLoggedUserGroups(state) {
        return state.logged_groups;
    }
}