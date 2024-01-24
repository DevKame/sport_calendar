

export default {
    async createGroup(context, name) {
        const create =
        {
            task: "create-group",
            name: name,
        };
        const response = await fetch(context.state.API_GROUPS, {
            method: "post",
            headers: {"Content-Type": "application/json"},
            body: JSON.stringify(create),
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