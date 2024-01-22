
// ASSETS
import "bootstrap/dist/css/bootstrap.min.css";
import "bootstrap";
import "./assets/styles/colors.css";
import "./assets/styles/classes.css";
import "./assets/styles/resets.css";
import "./assets/fonts/fonts.css";

// CUSTOM COMPS
import TheKamedin from "./comps/TheKamedin.vue";
import TheLogin from "./comps/login/TheLogin.vue";
import InterfaceCard from "./comps/multi/InterfaceCard.vue";
import ErrorAlert from "./comps/multi/ErrorAlert.vue";
import TheDashboard from "./comps/dashboard/TheDashboard.vue";
import TheEventOverview from "./comps/dashboard/events/TheEventOverview.vue";
import TheTrainingOverview from "./comps/dashboard/trainings/TheTrainingOverview.vue";

// FONTAWESOME
import { library } from "@fortawesome/fontawesome-svg-core";
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";

import { faMicrochip } from "@fortawesome/free-solid-svg-icons";
import { faClose } from "@fortawesome/free-solid-svg-icons";

library.add(faMicrochip);
library.add(faClose);

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
        },
        {
            path: "/dashboard",
            name: "Dashboard",
            redirect: {name: "Events"},
            component: TheDashboard,
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
            ]
        }
    ]
});

import { createApp } from 'vue'
import App from './App.vue'

const app = createApp(App);
app.component("the-kamedin", TheKamedin);
app.component("itf-card", InterfaceCard);
app.component("error-alert", ErrorAlert);
app.component("fa-icon", FontAwesomeIcon);

app.use(router);
app.mount('#app')
