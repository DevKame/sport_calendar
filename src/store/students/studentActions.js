

export default {
    async post(context, payload) {
        const response = await fetch(context.state.API_STUDENTS, {
            method: "post",
            headers: {"Content-Type": "application/json"},
            body: JSON.stringify(payload),
        });
        return await response.json();
    },
    async getAllStudents(context) {
        const response = await fetch(context.state.API_STUDENTS);
        return await response.json();
    },
}