<template>
    <div>
        <header @click="reactToDashboardclick" class="dashboardHeader p-2 d-flex flex-lg-wrap justify-content-start align-items-center bg-sec">
            
            <div class="imageHolder d-flex justify-content-center align-items-center">
                <img src="@/assets/img/logos/kaizen-630.png" alt="Kaizen Logo"/>
            </div>

            <div class="userHolder ms-2 d-flex flex-column justify-content-center align-items-start">
                <h6 class="m-0 mb-1">Welcome, <span>{{ userFirstname }}</span> <span>{{ userLastname }}</span></h6>
                <div class="roleHolder d-flex justify-content-start align-items-center w-100">
                    <p class="m-0">Role:</p>
                    <div class="badge text-black ms-2 d-flex justify-content-center align-items-center">{{ userRole }}</div>
                </div>
            </div>

            <div class="navHolder position-relative d-flex d-lg-none justify-content-center align-items-center ms-auto">
                <div :class="{opened: navIsOpen}" class="navItemHolder nav-menu position-relative border border-black rounded-circle d-flex justify-content-center align-items-center">
                    <fa-icon icon="fa-solid fa-bars-staggered" size="xl" class="pe-none"></fa-icon>
                </div>
                <transition name="events">
                    <div v-if="navIsOpen && userRole !== 'STUDENT'" class="navItemHolder bg-prim nav-events border border-black rounded-circle d-flex justify-content-center align-items-center">
                        <router-link :to="{name: 'Events'}" class="d-flex flex-column justify-content-start align-items-center">
                            <fa-icon icon="fa-regular fa-rectangle-list" class="pe-none"></fa-icon>
                            <small class="fw-bold">Events</small>
                        </router-link>
                    </div>
                </transition>
                <transition name="trainings">
                    <div v-if="navIsOpen && userRole !== 'STUDENT'" class="navItemHolder bg-prim nav-trainings border border-black rounded-circle d-flex justify-content-center align-items-center">
                        <router-link :to="{name: 'Trainings'}" class="d-flex flex-column justify-content-start align-items-center">
                            <fa-icon icon="fa-solid fa-dumbbell" class="pe-none"></fa-icon>
                            <small class="fw-bold">Trainings</small>
                        </router-link>
                    </div>
                </transition>
                <transition name="trainers">
                    <div v-if="navIsOpen && userRole !== 'STUDENT'" class="navItemHolder bg-prim nav-trainers border border-black rounded-circle d-flex justify-content-center align-items-center">
                        <router-link :to="{name: 'Trainers'}" class="d-flex flex-column justify-content-start align-items-center">
                            <fa-icon icon="fa-solid fa-person-chalkboard" class="pe-none"></fa-icon>
                            <small class="fw-bold">Trainers</small>
                        </router-link>
                    </div>
                </transition>
                <transition name="students">
                    <div v-if="navIsOpen && userRole !== 'STUDENT'" class="navItemHolder bg-prim nav-students border border-black rounded-circle d-flex justify-content-center align-items-center">
                        <router-link :to="{name: 'Students'}" class="d-flex flex-column justify-content-start align-items-center">
                            <fa-icon icon="fa-solid fa-people-group" class="pe-none"></fa-icon>
                            <small class="fw-bold">Students</small>
                        </router-link>
                    </div>
                </transition>
                <transition name="groups">
                    <div v-if="navIsOpen && userRole !== 'STUDENT'" class="navItemHolder bg-prim nav-groups border border-black rounded-circle d-flex justify-content-center align-items-center">
                        <router-link :to="{name: 'Groups'}" class="d-flex flex-column justify-content-start align-items-center">
                            <fa-icon icon="fa-solid fa-table" class="pe-none"></fa-icon>
                            <small class="fw-bold">Groups</small>
                        </router-link>
                    </div>
                </transition>
                <transition name="calendar">
                    <div v-if="navIsOpen && userRole !== 'STUDENT'" class="navItemHolder bg-sec nav-calendar border border-black rounded-circle d-flex justify-content-center align-items-center">
                        <router-link :to="{name: 'Calendar'}" class="d-flex flex-column justify-content-start align-items-center">
                            <fa-icon icon="fa-regular fa-calendar" class="pe-none"></fa-icon>
                            <small class="fw-bold">Calendar</small>
                        </router-link>
                    </div>
                </transition>
                <transition name="logout">
                    <div @click="try_logout" v-if="navIsOpen" class="navItemHolder bg-delete nav-logout border border-black rounded-circle d-flex justify-content-center align-items-center">
                        <span class="d-flex flex-column justify-content-start align-items-center">
                            <fa-icon icon="fa-solid fa-user-slash" class="pe-none"></fa-icon>
                            <small class="fw-bold">Logout</small>
                        </span>
                    </div>
                </transition>
            </div>

            <div class="widenavHolder mt-2 w-100 d-none d-lg-flex justify-content-around align-items-center">
                    <router-link :to="{name: 'Events'}" :class="roleBasedDisplay" class="justify-content-between align-items-center px-3 py-2 widenavLinks position-relative">
                        <fa-icon icon="fa-regular fa-rectangle-list" class="me-3"></fa-icon>
                        <small class="fw-bold">Events</small>
                    </router-link>
                    <router-link :to="{name: 'Trainings'}" :class="roleBasedDisplay" class="justify-content-between align-items-center px-3 py-2 widenavLinks position-relative">
                        <fa-icon icon="fa-solid fa-dumbbell" class="me-3"></fa-icon>
                        <small class="fw-bold">Trainings</small>
                    </router-link>
                    <router-link :to="{name: 'Trainers'}" :class="roleBasedDisplay" class="justify-content-between align-items-center px-3 py-2 widenavLinks position-relative">
                        <fa-icon icon="fa-solid fa-person-chalkboard" class="me-3"></fa-icon>
                        <small class="fw-bold">Trainers</small>
                    </router-link>
                    <router-link :to="{name: 'Students'}" :class="roleBasedDisplay" class="justify-content-between align-items-center px-3 py-2 widenavLinks position-relative">
                        <fa-icon icon="fa-solid fa-people-group" class="me-3"></fa-icon>
                        <small class="fw-bold">Students</small>
                    </router-link>
                    <router-link :to="{name: 'Groups'}" :class="roleBasedDisplay" class="justify-content-between align-items-center px-3 py-2 widenavLinks position-relative">
                        <fa-icon icon="fa-solid fa-table" class="me-3"></fa-icon>
                        <small class="fw-bold">Groups</small>
                    </router-link>
                    <router-link :to="{name: 'Calendar'}" :class="roleBasedDisplay" class="justify-content-between align-items-center px-3 py-2 widenavLinks position-relative">
                        <fa-icon icon="fa-regular fa-calendar" class="me-3"></fa-icon>
                        <small class="fw-bold">Calendar</small>
                    </router-link>
                    <span @click="try_logout" :class="roleBasedMargin" class="d-flex justify-content-between align-items-center px-3 py-2 widenavLinks position-relative widenavLogout">
                        <fa-icon icon="fa-solid fa-user-slash" class="me-3"></fa-icon>
                        <small class="fw-bold">Logout</small>
                    </span>
            </div>
        </header>

        <router-view v-slot="slotProps" @empty-click="clickFromRouterView">
            <transition name="sub-routes" mode="out-in">
                <component :is="slotProps.Component"></component>
            </transition>
        </router-view>
    </div>
</template>

<script setup>
import { computed, ref } from "vue";
import { useRouter } from "vue-router";
import { useStore } from "vuex";

const store = useStore();
const router = useRouter();

function reactToDashboardclick(e) {
    if(e.target.classList.contains("navHolder") || e.target.classList.contains("nav-menu"))
    {
        toggleNavOpen();
    }
    else {
        navIsOpen.value = false;
    }
}

const roleBasedMargin = computed(() => {
    return userRole.value !== "STUDENT" ?
    "" : "ms-auto";
});
const roleBasedDisplay = computed(() => {
    return userRole.value !== "STUDENT" ?
    "d-flex" : "d-none";
})
const navIsOpen = ref(false);
function toggleNavOpen() {
    navIsOpen.value = !navIsOpen.value;
}
function clickFromRouterView() {
    navIsOpen.value = false;
}
const userFirstname = computed(() => {
    return store.getters.getLoggedUserFirstname;
});
const userLastname = computed(() => {
    return store.getters.getLoggedUserLastname;
});
const userRole = computed(() => {
    return store.getters.getLoggedUserRole;
});


async function try_logout() {
    await store.dispatch("auth/try_logout");
    router.replace({name: "Start"});
}
</script>


<style scoped>
.widenavLinks::after {
    position: absolute;
    content: "";
    width: 0;
    height: 1px;
    top: 1px;
    left: 50%;
    background-color: black;
    transition: all .5s ease;
}
.widenavLinks.widenavLogout::after {
    background-color: rgb(80, 69, 69);
}
.widenavLinks:hover::after {
    width: 50%;
    left: 25%;
}
.widenavLinks.widenavLogout::after {
    background-color: firebrick;
}
.widenavLinks::before {
    position: absolute;
    content: "";
    width: 0;
    height: 3px;
    bottom: 0;
    left: 50%;
    background-color: black;
    transition: all .3s ease;
}
.widenavLinks:hover::before {
    width: 100%;
    left: 0;
}
.widenavLinks.widenavLogout::before {
    background-color: firebrick;
}
.widenavLinks.widenavLogout {
    color: firebrick;
    text-decoration: none;
    cursor: pointer;
}
.widenavLinks {
    color: black;
    text-decoration: none;
}
.sub-routes-enter-from,
.sub-routes-leave-to {
    opacity: 0;
    transform: translate(0, 50px);
}
.sub-routes-enter-to,
.sub-routes-leave-from {
    opacity: 1;
    transform: translate(0, 0);
}
.sub-routes-enter-active,
.sub-routes-leave-active {
    transition: all .2s ease-in;
}
.navHolder a,
.navHolder span {
    color: black;
    text-decoration: none;
}
.nav-logout {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, 100%);
}
.nav-calendar {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, 225%);
}
.nav-groups {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, 350%);
}
.nav-students {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, 475%);
}
.nav-trainers {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, 600%);
}
.nav-trainings {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, 725%);
}
.nav-events {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, 850%);
}
.logout-enter-from,
.logout-leave-to {
    opacity: 0;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
}
.logout-enter-to,
.logout-leave-from {
    opacity: 1;
    top: 50%;
    left: 50%;
    transform: translate(-50%, 100%);
}
.calendar-enter-from,
.calendar-leave-to {
    opacity: 0;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
}
.calendar-enter-to,
.calendar-leave-from {
    opacity: 1;
    top: 50%;
    left: 50%;
    transform: translate(-50%, 225%);
}
.groups-enter-from,
.groups-leave-to {
    opacity: 0;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
}
.groups-enter-to,
.groups-leave-from {
    opacity: 1;
    top: 50%;
    left: 50%;
    transform: translate(-50%, 350%);
}
.students-enter-from,
.students-leave-to {
    opacity: 0;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
}
.students-enter-to,
.students-leave-from {
    opacity: 1;
    top: 50%;
    left: 50%;
    transform: translate(-50%, 475%);
}
.trainers-enter-from,
.trainers-leave-to {
    opacity: 0;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
}
.trainers-enter-to,
.trainers-leave-from {
    opacity: 1;
    top: 50%;
    left: 50%;
    transform: translate(-50%, 600%);
}
.trainings-enter-from,
.trainings-leave-to {
    opacity: 0;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
}
.trainings-enter-to,
.trainings-leave-from {
    opacity: 1;
    top: 50%;
    left: 50%;
    transform: translate(-50%, 725%);
}
.events-enter-from,
.events-leave-to {
    opacity: 0;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
}
.events-enter-active,
.trainings-enter-active,
.trainers-enter-active,
.students-enter-active,
.groups-enter-active,
.calendar-enter-active,
.logout-enter-active,
.events-leave-active,
.trainings-leave-active,
.trainers-leave-active,
.students-leave-active,
.groups-leave-active,
.calendar-leave-active,
.logout-leave-active {
    transition: all .4s ease-out;
}
.events-enter-to,
.events-leave-from {
    opacity: 1;
    top: 50%;
    left: 50%;
    transform: translate(-50%, 850%);
}
.nav-menu::after {
    content: "";
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 140%;
    height: 140%;
    border: 3px solid #444;
    clip-path: polygon(30% 100%, 50% 50%, 90% 100%, 30% 100%);
    border-radius: inherit;
    animation: menu-spin-after 1s linear infinite;
    transition: opacity .3s ease-in;
    opacity: 0;
}
.nav-menu::before {
    content: "";
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 130%;
    height: 130%;
    border: 3px solid #444;
    clip-path: polygon(0% 50%, 50% 50%, 100% 10%, 100% 0%, 0% 0%, 0% 50%);
    border-radius: inherit;
    animation: menu-spin-before 1s linear infinite;
    transition: opacity .3s ease-in;
    opacity: 0;
}
.nav-menu.opened::before,
.nav-menu.opened::after {
    opacity: 1;
}
@keyframes menu-spin-before {
    0% {
        transform: translate(-50%, -50%) rotate(0);
    }
    50% {
        transform: translate(-50%, -50%) rotate(150deg);
    }
    100% {
        transform: translate(-50%, -50%) rotate(360deg);
    }
}
@keyframes menu-spin-after {
    0% {
        transform: translate(-50%, -50%) rotate(0);
    }
    50% {
        transform: translate(-50%, -50%) rotate(-150deg);
    }
    100% {
        transform: translate(-50%, -50%) rotate(-360deg);
    }
}
.navItemHolder small {
    font-size: 8px;
}
.navItemHolder {
    width: 60%;
    aspect-ratio: 1;
    z-index: 21;
}
.navHolder {
    width: clamp(80px, 20%, 110px);
}
.userHolder h6,
.roleHolder {
    font-size: 12px;
}
.userHolder .badge {
    background-color: var(--role-badge);
}
.userHolder {
    width: 50%;
    max-width: 300px;
}
.imageHolder img {
    width: 100%;
}
.imageHolder {
    width: 25%;
    max-width: 150px;
}
.dashboardHeader > * {
    height: 80%;
}
.dashboardHeader {
    height: 100px;
    box-shadow: 0 2px 10px 1px #333;
    font-family: "Raleway SBold 600";
}
@media screen and (min-width: 576px) {
    .navItemHolder small {
        font-size: 10px;
    }
}
@media screen and (min-width: 768px) {
    .navItemHolder small {
        font-size: 12px;
    }
}
@media screen and (min-width: 992px) {
.dashboardHeader {
    height: auto;
}
}
</style>