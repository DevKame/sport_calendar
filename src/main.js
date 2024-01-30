
// ASSETS
import "bootstrap/dist/css/bootstrap.min.css";
import "bootstrap";
import "./assets/styles/colors.css";
import "./assets/styles/classes.css";
import "./assets/styles/resets.css";
import "./assets/fonts/fonts.css";

// CUSTOM COMPS
import TheKamedin           from "./comps/TheKamedin.vue";
import InterfaceCard        from "./comps/multi/InterfaceCard.vue";
import ErrorAlert           from "./comps/multi/alerts/ErrorAlert.vue";
import SuccessAlert         from "./comps/multi/alerts/SuccessAlert.vue";
import OverviewLoading      from "./comps/multi/OverviewLoading.vue";
import FormLoading          from "./comps/multi/FormLoading.vue";
import InfoBox              from "./comps/dashboard/shared/InfoBox.vue";


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
import { faForward }            from "@fortawesome/free-solid-svg-icons";

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
import router from "./router/router.js";

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
