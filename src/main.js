
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
import ErrorAlert           from "./comps/multi/ErrorAlert.vue";
import TheDashboard         from "./comps/dashboard/TheDashboard.vue";
import TheEventOverview     from "./comps/dashboard/events/TheEventOverview.vue";
import TheTrainingOverview  from "./comps/dashboard/trainings/TheTrainingOverview.vue";
import TheTrainerOverview   from "./comps/dashboard/trainers/TheTrainerOverview.vue";
import TheStudentOverview   from "./comps/dashboard/students/TheStudentOverview.vue";
import TheGroupOverview     from "./comps/dashboard/groups/TheGroupOverview.vue";
import TheError             from "./comps/error/TheError.vue";
import OverviewLoading      from "./comps/multi/OverviewLoading.vue";

// FONTAWESOME
import { library } from "@fortawesome/fontawesome-svg-core";
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";

import { faBarsStaggered } from "@fortawesome/free-solid-svg-icons";
import { faMicrochip } from "@fortawesome/free-solid-svg-icons";
import { faClose } from "@fortawesome/free-solid-svg-icons";
import { faRectangleList } from "@fortawesome/free-regular-svg-icons";
import { faDumbbell } from "@fortawesome/free-solid-svg-icons";
import { faPersonChalkboard } from "@fortawesome/free-solid-svg-icons";
import { faPeopleGroup } from "@fortawesome/free-solid-svg-icons";
import { faTable } from "@fortawesome/free-solid-svg-icons";

library.add(faMicrochip);
library.add(faClose);
library.add(faBarsStaggered);
library.add(faRectangleList);
library.add(faDumbbell);
library.add(faPersonChalkboard);
library.add(faPeopleGroup);
library.add(faTable);

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
            redirect: {name: "Trainings"},
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
                    console.table(error);
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
                {
                    path: "events",
                    name: "Events",
                    component: TheEventOverview,
                },
                {
                    path: "trainings",
                    name: "Trainings",
                    component: TheTrainingOverview,
                },
                {
                    path: "trainers",
                    name: "Trainers",
                    component: TheTrainerOverview,
                },
                {
                    path: "students",
                    name: "Students",
                    component: TheStudentOverview,
                },
                {
                    path: "groups",
                    name: "Groups",
                    component: TheGroupOverview,
                },
            ]
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
    if(to.meta.loginRequired && !result.logged_user_existent) {
        nextPara = {name: "Start"};
    }
    else if(to.meta.loginRequired && result.logged_user_existent)
    {
        nextPara = true;
    }
    else if(to.meta.logoutRequired) {
        if(result.logged_user_existent) {
            nextPara = {name: "Dashboard"};
        } else {
            nextPara = true;
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
app.component("ov-load", OverviewLoading);
app.component("fa-icon", FontAwesomeIcon);

app.use(store);
app.use(router);
app.mount('#app')
