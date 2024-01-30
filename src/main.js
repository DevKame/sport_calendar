
// ASSETS
import "bootstrap/dist/css/bootstrap.min.css";
import "bootstrap";
import "./assets/styles/colors.css";
import "./assets/styles/classes.css";
import "./assets/styles/resets.css";
import "./assets/fonts/fonts.css";

// CUSTOM COMPS
import TheKamedin           from "./comps/TheKamedin.vue";
import TheLogin             from "./comps/login/TheLogin.vue";
import InterfaceCard        from "./comps/multi/InterfaceCard.vue";
import ErrorAlert           from "./comps/multi/alerts/ErrorAlert.vue";
import SuccessAlert         from "./comps/multi/alerts/SuccessAlert.vue";
import TheDashboard         from "./comps/dashboard/TheDashboard.vue";
import TheEventOverview     from "./comps/dashboard/events/TheEventOverview.vue";
import TheTrainingOverview  from "./comps/dashboard/trainings/TheTrainingOverview.vue";
import TheTrainerOverview   from "./comps/dashboard/trainers/TheTrainerOverview.vue";
import TheStudentOverview   from "./comps/dashboard/students/TheStudentOverview.vue";
import TheGroupOverview     from "./comps/dashboard/groups/TheGroupOverview.vue";
import TheError             from "./comps/error/TheError.vue";
import OverviewLoading      from "./comps/multi/OverviewLoading.vue";
import FormLoading          from "./comps/multi/FormLoading.vue";
import InfoBox              from "./comps/dashboard/shared/InfoBox.vue";

import NewGroup             from "./comps/dashboard/groups/NewGroup.vue";
import EditGroup            from "./comps/dashboard/groups/EditGroup.vue";

import NewStudent           from "./comps/dashboard/students/NewStudent.vue";
import EditStudent          from "./comps/dashboard/students/EditStudent.vue";

import NewTrainer           from "./comps/dashboard/trainers/NewTrainer.vue";
import EditTrainer          from "./comps/dashboard/trainers/EditTrainer.vue";

import NewTraining          from "./comps/dashboard/trainings/NewTraining.vue";
import EditTraining         from "./comps/dashboard/trainings/EditTraining.vue";

import NewEvent             from "./comps/dashboard/events/NewEvent.vue";
import EditEvent            from "./comps/dashboard/events/EditEvent.vue";

import TheCal               from "./comps/calendar/TheCal.vue";

// FONTAWESOME
import { library }              from "@fortawesome/fontawesome-svg-core";
import { FontAwesomeIcon }      from "@fortawesome/vue-fontawesome";

import { faBarsStaggered }      from "@fortawesome/free-solid-svg-icons";
import { faMicrochip }          from "@fortawesome/free-solid-svg-icons";
import { faClose }              from "@fortawesome/free-solid-svg-icons";
import { faRectangleList }      from "@fortawesome/free-regular-svg-icons";
import { faDumbbell }           from "@fortawesome/free-solid-svg-icons";
import { faPersonChalkboard }   from "@fortawesome/free-solid-svg-icons";
import { faPeopleGroup }        from "@fortawesome/free-solid-svg-icons";
import { faTable }              from "@fortawesome/free-solid-svg-icons";
import { faGear }               from "@fortawesome/free-solid-svg-icons";
import { faPencil }             from "@fortawesome/free-solid-svg-icons";
import { faBan }                from "@fortawesome/free-solid-svg-icons";
import { faUserSlash }          from "@fortawesome/free-solid-svg-icons";
import { faCalendar }           from "@fortawesome/free-regular-svg-icons";
import { faBackward }           from "@fortawesome/free-solid-svg-icons";
import { faForward }           from "@fortawesome/free-solid-svg-icons";

library.add(faMicrochip);
library.add(faClose);
library.add(faBarsStaggered);
library.add(faRectangleList);
library.add(faDumbbell);
library.add(faPersonChalkboard);
library.add(faPeopleGroup);
library.add(faTable);
library.add(faGear);
library.add(faPencil);
library.add(faBan);
library.add(faUserSlash);
library.add(faCalendar);
library.add(faBackward);
library.add(faForward);

// ROUTER
import { createRouter, createWebHistory } from "vue-router";
let router = createRouter({
    history: createWebHistory(),
    routes:
    [
        {
            path: "/",
            name: "Start",
            component: TheLogin,
            meta:
            {
                loginRequired: false,
                logoutRequired: true,
            },
        },
        {
            path: "/dashboard",
            name: "Dashboard",
            redirect: {name: "Events"},
            component: TheDashboard,
            /** RIGHT BEFORE THIS NAV GUARD A GLOBAL NAV GUARD WAS INVOKED
             *  TO CHECK IF A USER AT ALL IS LOGGED IN. IF IT CAME TO THIS
             *  ROUTE, THAT MEANS THAT A USER IS LOGGED IN. THIS NAV GUARD
             *  FETCHES THIS USER´S DATA AND SETS IT IN VUEX STATE */
            async beforeEnter(_, _2, next) {
                try {
                    let userid = await store.dispatch("auth/getUserIDFromSession");
                    userid = userid.session_id;
                    let userdata = await store.dispatch("auth/get_userdata_from_id", userid);
                    store.commit("setLoggedUser", userdata.logged_user);
                    next(true);
                }
                catch(error) {
                    next({name: "Error"});
                }
            },
            meta:
            {
                loginRequired: true,
                logoutRequired: false,
            },
            children:
            [
                { path: "events", name: "Events", component: TheEventOverview, meta: {trainerrequired: true, logoutRequired: false} },
                { path: "newevent", name: "New-Event", component: NewEvent, meta: {trainerrequired: true, logoutRequired: false} },
                {
                    path: "editevent",
                    name: "Edit-Event",
                    component: EditEvent,
                    meta: {trainerrequired: true, logoutRequired: false},
                    beforeEnter(to, from, next) {
                        let nextPara = {name: "Events"};
                        console.clear();
                        console.log("from.name:", from.name);
                        console.log("to.name:", to.name);
                        if(from.name === "Events") {
                            nextPara = true;
                        }
                        next(nextPara);
                    }
                },

                { path: "trainings", name: "Trainings", component: TheTrainingOverview, meta: {trainerrequired: true, logoutRequired: false} },
                { path: "newtraining", name: "New-Training", component: NewTraining, meta: {trainerrequired: true, logoutRequired: false} },
                {
                    path: "edittraining",
                    name: "Edit-Training",
                    component: EditTraining,
                    meta: {trainerrequired: true, logoutRequired: false},
                    beforeEnter(to, from, next) {
                        let nextPara = {name: "Trainings"};
                        console.clear();
                        console.log("from.name:", from.name);
                        console.log("to.name:", to.name);
                        if(from.name === "Trainings") {
                            nextPara = true;
                        }
                        next(nextPara);
                    }
                },

                { path: "trainers", name: "Trainers", component: TheTrainerOverview, meta: {trainerrequired: true, logoutRequired: false} },
                { path: "newtrainer", name: "New-Trainer", component: NewTrainer, meta: {trainerrequired: true, logoutRequired: false} },
                {
                    path: "edittrainer",
                    name: "Edit-Trainer",
                    component: EditTrainer,
                    meta: {trainerrequired: true, logoutRequired: false},
                    beforeEnter(to, from, next) {
                        let nextPara = {name: "Trainers"};
                        console.clear();
                        console.log("from.name:", from.name);
                        console.log("to.name:", to.name);
                        if(from.name === "Trainers") {
                            nextPara = true;
                        }
                        next(nextPara);
                    }
                },

                { path: "students", name: "Students", component: TheStudentOverview, meta: {trainerrequired: true, logoutRequired: false} },
                { path: "newstudent", name: "New-Student", component: NewStudent, meta: {trainerrequired: true, logoutRequired: false} },
                {
                    path: "editstudent",
                    name: "Edit-Student",
                    component: EditStudent,
                    meta: {trainerrequired: true, logoutRequired: false},
                    beforeEnter(to, from, next) {
                        let nextPara = {name: "Students"};
                        console.clear();
                        console.log("from.name:", from.name);
                        console.log("to.name:", to.name);
                        if(from.name === "Students") {
                            nextPara = true;
                        }
                        next(nextPara);
                    }
                },

                { path: "groups", name: "Groups", component: TheGroupOverview, meta: {trainerrequired: true, logoutRequired: false} },
                { path: "newgroup", name: "New-Group", component: NewGroup, meta: {trainerrequired: true, logoutRequired: false} },
                {
                    path: "editgroup",
                    name: "Edit-Group",
                    component: EditGroup,
                    meta: {trainerrequired: true, logoutRequired: false},
                    beforeEnter(to, from, next) {
                        let nextPara = {name: "Groups"};
                        console.clear();
                        console.log("from.name:", from.name);
                        console.log("to.name:", to.name);
                        if(from.name === "Groups") {
                            nextPara = true;
                        }
                        next(nextPara);
                    }
                },

                { path: "calendar", name: "Calendar", component: TheCal },
            ],
        },
        {
            path: "/error",
            name: "Error",
            component: TheError,
        }
    ],
});
/** MAKES SURE TO ACCESS PORTECTED ROUTES ONLY IF LOGGED IN AND REDIRECTS
 *  BACK TO "Start"-ROUTE IF NOT.
 *  MAKES SURE TO ACCESS "Start"-ROUTE ONLY IF NOT LOGGED IN. REDIRECTS TO
 *  "Dashboard" IF ALREADY LOGGED IN */
router.beforeEach(async (to, _, next) => {
    let nextPara = true;
    let result = await store.dispatch("auth/loggedUserExistent");
    // let userRole = await store.getters["auth/userRole"];
    if(to.meta.loginRequired && !result.logged_user_existent) {
        nextPara = {name: "Start"};
    }
    else if(to.meta.logoutRequired && result.logged_user_existent) {
        nextPara = {name: "Dashboard"};
    }
    else if(to.meta.loginRequired && to.meta.trainerrequired) {
        if(!result.logged_user_existent)
        {
            nextPara = {name: "Start"};
        } else {
            let id = await store.dispatch("auth/getUserIDFromSession");
            let user = await store.dispatch("auth/get_userdata_from_id", id.session_id);
            if(user.logged_user.role === "STUDENT")
            {
                nextPara = {name: "Calendar"};
            } else {
                nextPara = true;
            }
        }
    }
    next(nextPara);
});

// STORE:
import store from "./store/rootStore.js";

import { createApp } from 'vue'
import App from './App.vue'

const app = createApp(App);
app.component("the-kamedin", TheKamedin);
app.component("itf-card", InterfaceCard);
app.component("error-alert", ErrorAlert);
app.component("success-alert", SuccessAlert);
app.component("ov-load", OverviewLoading);
app.component("form-loading", FormLoading);
app.component("info-box", InfoBox);
app.component("fa-icon", FontAwesomeIcon);

app.use(store);
app.use(router);
app.mount('#app')
