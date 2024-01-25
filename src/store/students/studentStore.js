

import studentActions from "./studentActions.js";
import studentGetters from "./studentGetters.js";
import studentMutations from "./studentMutations.js";

export default {
    namespaced: true,
    state() {
        return {
            // ENDPOINT FOR WORKING WITH GROUPS-API
            API_STUDENTS: "http://localhost/Eskamedin/sport_calendar/vue_app/public/backend/students/students.php",
            // IF YOU WANT TO EDIT A STUDENT; HIS ID IS SAVED HERE TO REMEMBER WHOM TO EDIT
            studentID_edit: null,
        };
    },
    actions: studentActions,
    getters: studentGetters,
    mutations: studentMutations,
};