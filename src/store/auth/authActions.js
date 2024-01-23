

export default {
    async getUserIDFromSession(context) {
        const req = {task: "get-userid-from-session"};
        let response = await fetch(context.state.API_AUTH, {
            method: "post",
            headers: {"Content-Type": "application/json"},
            body: JSON.stringify(req),
            credentials: "include",
        });
        return await response.json();
    },
    async loggedUserExistent(context) {
        let response = await fetch(context.state.API_AUTH, {
            credentials: "include",
        });
        let result = await response.json();
        return result;
    },
    async get_userdata_from_id(context, id) {
        let response = await fetch(context.state.API_AUTH, {
            method: "post",
            headers: {"Content-Type": "application/json"},
            body: JSON.stringify({id: id, task: "get-userdata-from-id"}),
            credentials: "include",
        });
        const userdata = await response.json();
        return userdata;
    },
    async try_login(context, logindata) {
        
        let response = await fetch(context.state.API_AUTH, {
            method: "post",
            headers: {"Content-Type": "application/json"},
            body: JSON.stringify(logindata),
            credentials: "include",
        });
        let data = await response.json();
        if(data.success) {
            let userdata =
            await context.dispatch("get_userdata_from_id", data.id_of_logged_user);
            if(!userdata.success) {
                context.commit("resetLoggedUser");
            } else {
                context.commit("setLoggedUser", userdata.logged_user);
            }
            return userdata;
        }
    }
}