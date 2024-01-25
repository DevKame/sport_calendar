

import groupActions from "./groupActions.js";
import groupGetters from "./groupGetters.js";
import groupMutations from "./groupMutations.js";

export default {
    namespaced: true,
    state() {
        return {
            // ENDPOINT FOR WORKING WITH GROUPS-API
            API_GROUPS: "http://localhost/Eskamedin/sport_calendar/vue_app/public/backend/groups/groups.php",
            // WHEN ENTERING THE ROUT FOR EDITING A GROUP, THE ROUTE USES THOSE VALUES
            // TO REMEMBER WHAT IT WANTS TO EDIT
            groupname_edit: null,
            groupID_edit: null,
        };
    },
    actions: groupActions,
    getters: groupGetters,
    mutations: groupMutations,
};