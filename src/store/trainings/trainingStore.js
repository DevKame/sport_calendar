

import trainingActions from "./trainingActions.js";
import trainingGetters from "./trainingGetters.js";
import trainingMutations from "./trainingMutations.js";

export default {
    namespaced: true,
    state() {
        return {
            // ENDPOINT FOR WORKING WITH TRAINING-API
            API_TRAININGS: "http://localhost/Eskamedin/sport_calendar/vue_app/public/backend/trainings/trainings.php",
            // FOR EDITING A TRAINING HIS DATA ARE PREPARED HERE
            trainingID_edit: null,
            trainingname_edit: null,
            traininggroups_edit: null,
        };
    },
    actions: trainingActions,
    getters: trainingGetters,
    mutations: trainingMutations,
};