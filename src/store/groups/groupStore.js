

import groupActions from "./groupActions.js";
import groupGetters from "./groupGetters.js";
import groupMutations from "./groupMutations.js";

export default {
    state() {
        return {
            API_GROUPS: "http://localhost/Eskamedin/sport_calendar/vue_app/public/backend/groups/groups.php",
        };
    },
    actions: groupActions,
    getters: groupGetters,
    mutations: groupMutations,
};