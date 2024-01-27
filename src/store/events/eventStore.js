

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
            eventID_edit: null,
            eventname_edit: null,
            eventday_edit: null,
            eventmonth_edit: null,
            eventyear_edit: null,
            eventhour_edit: null,
            eventminute_edit: null,
            eventmax_edit: null,
            eventtrainer_edit: null,
            eventinfo_edit: null,
            eventgroups_edit: null,
        };
    },
    actions: eventActions,
    getters: eventGetters,
    mutations: eventMutations,
};