

export default {
    async post(context, payload) {
        const response = await fetch(context.state.API_GROUPS, {
            method: "post",
            headers: {"Content-Type": "application/json"},
            body: JSON.stringify(payload),
        });
        return await response.json();
    },
    async deleteGroup(context, id) {
        const deletereq =
        {
            task: "delete-group",
            id: id,
        };
        const response = await fetch(context.state.API_GROUPS, {
            method: "post",
            headers: {"Content-Type": "application/json"},
            body: JSON.stringify(deletereq),
        });
        return await response.json();
    },
    async createGroup(context, name) {
        const createreq =
        {
            task: "create-group",
            name: name,
        };
        const response = await fetch(context.state.API_GROUPS, {
            method: "post",
            headers: {"Content-Type": "application/json"},
            body: JSON.stringify(createreq),
        });
        return await response.json();
    },
    async validateGroupname(context, name) {
        const valireq =
        {
            task: "validate-group",
            name: name,
        };
        const response = await fetch(context.state.API_GROUPS, {
            method: "post",
            headers: {"Content-Type": "application/json"},
            body: JSON.stringify(valireq),
        });
        return await response.json();
    },
    async getAllGroups(context) {
        const response = await fetch(context.state.API_GROUPS);
        return await response.json();
    },
}