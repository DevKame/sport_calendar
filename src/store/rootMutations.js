

export default {
    resetLoggedUser(state) {
        state.is_logged_in = false;
        state.logged_id = null;
        state.logged_firstname = null;
        state.logged_lastname = null;
        state.logged_email = null;
        state.logged_role = null;
        state.logged_groups = null;
    },
    setLoggedUser(state, data) {
        console.log(data);
        state.is_logged_in = true;
        state.logged_id = data.logged_user.id;
        state.logged_firstname = data.logged_user.firstname;
        state.logged_lastname = data.logged_user.lastname;
        state.logged_email = data.logged_user.email;
        state.logged_role = data.logged_user.role;
        state.logged_groups = data.logged_user.groups;
    }
}