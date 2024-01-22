

export default {
    async try_login(context, logindata) {
        console.log(context.commit);
        console.log(context.state);
        console.log(context.rootState);
        
        let response = await fetch(context.state.API_AUTH, {
            method: "post",
            headers: {"Content-Type": "application/json"},
            body: JSON.stringify(logindata),
            credentials: "include",
        });
        return await response.json();
        
    }
}