

export default {
    /** ALL POST REQUESTS REGARDING EVENTS
     *  payload IS AN OBJECT THAT AT LEAST HAS A "task" -
     *  ATTRIBUTE. BACKEND USES THIS ATTRIBUTE TO IDENTIFY WHAT
     *  TO DO AND USES payload´s OTHER DATA TO DO THAT TASKS
     *  returns {object} */
    async post(context, payload) {
        const response = await fetch(context.state.API_EVENTS, {
            method: "post",
            headers: {"Content-Type": "application/json"},
            body: JSON.stringify(payload),
        });
        return await response.json();
    },
    /** GET REQUEST THAT RETURNS EVERY STUDENT EXISTENT */
    async getAllEvents(context) {
        const response = await fetch(context.state.API_EVENTS);
        return await response.json();
    },
}