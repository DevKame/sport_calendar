<template>
    <div>
        <header @click="reactToDashboardclick" class="dashboardHeader p-2 d-flex justify-content-start align-items-center bg-sec">
            
            <div class="imageHolder border border-danger d-flex justify-content-center align-items-center">
                <img src="@/assets/img/logos/kaizen-630.png" alt="Kaizen Logo"/>
            </div>

            <div class="userHolder ms-2 border border-info d-flex flex-column justify-content-center align-items-start">
                <h6 class="m-0 mb-1">Welcome, <span>{{ userFirstname }}</span> <span>{{ userLastname }}</span></h6>
                <div class="roleHolder d-flex justify-content-start align-items-center w-100">
                    <p class="m-0">Role:</p>
                    <div class="badge text-black ms-2">{{ userRole }}</div>
                </div>
            </div>

            <div class="navHolder position-relative d-flex justify-content-center align-items-center border border-success ms-auto">
                <div :class="{opened: navIsOpen}" class="navItemHolder nav-menu position-relative border border-black rounded-circle d-flex justify-content-center align-items-center">
                    <fa-icon icon="fa-solid fa-bars-staggered" size="xl" class="pe-none"></fa-icon>
                </div>
            </div>
        </header>
        <router-view @empty-click="clickFromRouterView"></router-view>
    </div>
</template>

<script setup>
import { computed, ref } from "vue";
import { useStore } from "vuex";

const store = useStore();

function reactToDashboardclick(e) {
    if(e.target.classList.contains("navHolder") || e.target.classList.contains("nav-menu"))
    {
        toggleNavOpen();
    }
    else {
        navIsOpen.value = false;
    }
}
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
</script>


<style scoped>
.nav-menu::after {
    content: "";
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 160%;
    height: 160%;
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
.navItemHolder {
    width: 60%;
    aspect-ratio: 1;
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
    width: 100%;
    height: 100px;
    box-shadow: 0 2px 10px 1px #333;
    font-family: "Raleway SBold 600";
}
</style>