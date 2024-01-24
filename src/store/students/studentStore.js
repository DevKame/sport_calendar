

import studentActions from "./studentActions.js";
import studentGetters from "./studentGetters.js";
import studentMutations from "./studentMutations.js";

export default {
    namespaced: true,
    state() {
        return {
            API_STUDENTS: "http://localhost/Eskamedin/sport_calendar/vue_app/public/backend/students/students.php",
            studentID_edit: null,
        };
    },
    actions: studentActions,
    getters: studentGetters,
    mutations: studentMutations,
};