

import trainerActions from "./trainerActions.js";
import trainerGetters from "./trainerGetters.js";
import trainerMutations from "./trainerMutations.js";

export default {
    namespaced: true,
    state() {
        return {
            // ENDPOINT FOR WORKING WITH TRAINER-API
            API_TRAINERS: "http://localhost/Eskamedin/sport_calendar/vue_app/public/backend/trainers/trainers.php",
            // FOR EDITING A TRAINER HIS DATA ARE PREPARED HERE
            trainerID_edit: null,
            traineremail_edit: null,
            trainerfirstname_edit: null,
            trainerlastname_edit: null,
            trainerrole_edit: null,
            trainergroups_edit: null,
        };
    },
    actions: trainerActions,
    getters: trainerGetters,
    mutations: trainerMutations,
};