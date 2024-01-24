

export default {
    async getAllGroups(context) {
        const response = await fetch(context.state.API_GROUPS);
        const data = await response.json();
        console.table(data);
        return data;
    },
}