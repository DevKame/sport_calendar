

export default {
    /** FETCHES THE DATABASE-ID OF CURRENTLY LOGGED USER
     * @param {store object} context    => VUEX STORE
     * @returns {number}                => DATABASE-ID
     */
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
    /** CHECKS IF A USER IS LOGGED IN
     * @param {store object} context    => VUEX STORE 
     * @returns {object.bool}           => result.logged_user_existent */
    async loggedUserExistent(context) {
        let response = await fetch(context.state.API_AUTH, {
            credentials: "include",
        });
        let result = await response.json();
        return result;
    },
    /** GETS USERDATA FROM A PROVIDED ID
     * @param {store object} context    => VUEX STORE
     * @param {number} id               => ID TO FETCH FROM DATABASE
     * @returns {object.logged_user}    => {id: number, email: String, firstname/lastname/role: String, groups: JSON-String}
     */
    async get_userdata_from_id(context, id) {
        let response = await fetch(context.state.API_AUTH, {
            method: "post",
            headers: {"Content-Type": "application/json"},
            body: JSON.stringify({id: id, task: "get-userdata-from-id"}),
            credentials: "include",
        });
        return await response.json();
    },
    /** TRYING TO LOGING WITH ENTERED LOGINDATA AND TASK: "try-login"
     *  IF SUCCESS, FETCHES USERDATA FROM PROVIDED EMAIL
     *  IF LOGIN/USERDATA FETCHES ARE UNSUCCESSFULL, RETURNS OBJECT FROM
     *  LOGIN-TRY WITH THE REASON OF THE FAILED LOGIN, OTHERWISE RETURNS
     *  USERDATA
     * @param {store object} context        => VUEX STORE
     * @param {object} logindata            => {email: String, password: String, task:String}
     * @returns {object} USERDATA | REASON FOR FAILED LOGIN
     */
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
                return data;
            } else {
                context.commit("setLoggedUser", userdata.logged_user);
            }
            return userdata;
        }
        else {
            return data;
        }
    }
}