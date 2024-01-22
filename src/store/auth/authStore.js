

import authActions from "./authActions.js";
import authGetters from "./authGetters.js";
import authMutations from "./authMutations.js";

export default {
    state() {
        return {
            API_AUTH: "http://localhost/Eskamedin/sport_calendar/vue_app/public/backend/auth/auth.php",
        };
    },
    actions: authActions,
    getters: authGetters,
    mutations: authMutations,
};