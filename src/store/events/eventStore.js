

import eventActions from "./eventActions.js";
import eventGetters from "./eventGetters.js";
import eventMutations from "./eventMutations.js";

export default {
    namespaced: true,
    state() {
        return {
            // ENDPOINT FOR WORKING WITH EVENT-API
            API_EVENTS: "http://localhost/Eskamedin/sport_calendar/vue_app/public/backend/events/events.php",
            // FOR EDITING AN EVENT HIS DATA ARE PREPARED HERE
            studentID_edit: null,
            studentemail_edit: null,
            studentfirstname_edit: null,
            studentlastname_edit: null,
            studentgroups_edit: null,
        };
    },
    actions: eventActions,
    getters: eventGetters,
    mutations: eventMutations,
};