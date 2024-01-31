
// ASSETS
import "bootstrap/dist/css/bootstrap.min.css";
import "bootstrap";
import "./assets/styles/colors.css";
import "./assets/styles/classes.css";
import "./assets/styles/resets.css";
import "./assets/fonts/fonts.css";


import { createApp, defineAsyncComponent } from 'vue'
import App from './App.vue'


// CUSTOM COMPS
const TheKamedin        = defineAsyncComponent(() => import("./comps/TheKamedin.vue"));
const InterfaceCard     = defineAsyncComponent(() => import("./comps/multi/InterfaceCard.vue"));
const ErrorAlert        = defineAsyncComponent(() => import("./comps/multi/alerts/ErrorAlert.vue"));
const SuccessAlert      = defineAsyncComponent(() => import("./comps/multi/alerts/SuccessAlert.vue"));
const OverviewLoading   = defineAsyncComponent(() => import("./comps/multi/OverviewLoading.vue"));
const FormLoading       = defineAsyncComponent(() => import("./comps/multi/FormLoading.vue"));
const InfoBox           = defineAsyncComponent(() => import("./comps/dashboard/shared/InfoBox.vue"));


// FONTAWESOME
import { library }              from "@fortawesome/fontawesome-svg-core";
import { FontAwesomeIcon }      from "@fortawesome/vue-fontawesome";

//  -- IMPORT NEEDED ICONS
import { faBarsStaggered,faMicrochip,faClose,faDumbbell,faPersonChalkboard,faPeopleGroup,faTable,faGear,faPencil,faBan,faUserSlash,faBackward,faForward }
from "@fortawesome/free-solid-svg-icons";
//  -- AND ADD THEM TO LIBRARY
import { faRectangleList, faCalendar }
from "@fortawesome/free-regular-svg-icons";

library.add(faMicrochip,faClose,faBarsStaggered,faRectangleList,faDumbbell,faPersonChalkboard,faPeopleGroup,faTable,faGear,faPencil,faBan,faUserSlash,faCalendar,faBackward,faForward);

// ROUTER
import router from "./router/router.js";

// STORE:
import store from "./store/rootStore.js";


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
app.mount('#app');
