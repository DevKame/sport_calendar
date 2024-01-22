

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
        state.is_logged_in = true;
        state.logged_id = data.id;
        state.logged_firstname = data.firstname;
        state.logged_lastname = data.lastname;
        state.logged_email = data.email;
        state.logged_role = data.role;
        state.logged_groups = JSON.parse(data.groups);
    }
}