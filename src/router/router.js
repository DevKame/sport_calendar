


import { createRouter, createWebHistory } from "vue-router";


const TheError              = () => import("../comps/error/TheError.vue");

const TheLogin              = () => import("../comps/login/TheLogin.vue");
const TheDashboard          = () => import("../comps/dashboard/TheDashboard.vue");

const TheGroupOverview      = () => import("../comps/dashboard/groups/TheGroupOverview.vue");
const NewGroup              = () => import("../comps/dashboard/groups/NewGroup.vue");
const EditGroup             = () => import("../comps/dashboard/groups/EditGroup.vue");

const TheStudentOverview    = () => import("../comps/dashboard/students/TheStudentOverview.vue");
const NewStudent            = () => import("../comps/dashboard/students/NewStudent.vue");
const EditStudent           = () => import("../comps/dashboard/students/EditStudent.vue");

const TheTrainerOverview    = () => import("../comps/dashboard/trainers/TheTrainerOverview.vue");
const NewTrainer            = () => import("../comps/dashboard/trainers/NewTrainer.vue");
const EditTrainer           = () => import("../comps/dashboard/trainers/EditTrainer.vue");

const TheTrainingOverview   = () => import("../comps/dashboard/trainings/TheTrainingOverview.vue");
const NewTraining           = () => import("../comps/dashboard/trainings/NewTraining.vue");
const EditTraining          = () => import("../comps/dashboard/trainings/EditTraining.vue");

const TheEventOverview      = () => import("../comps/dashboard/events/TheEventOverview.vue");
const NewEvent              = () => import("../comps/dashboard/events/NewEvent.vue");
const EditEvent             = () => import("../comps/dashboard/events/EditEvent.vue");

const TheCal                = () => import("../comps/calendar/TheCal.vue");

// VUEX STORE
import store from "../store/rootStore.js";

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


export default router;