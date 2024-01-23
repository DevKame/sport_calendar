<template>
    <div>
        <header class="dashboardHeader p-2 d-flex justify-content-start align-items-center bg-sec">
            
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
        </header>
        <router-view></router-view>
    </div>
</template>

<script setup>
import { computed } from "vue";
import { useStore } from "vuex";

const store = useStore();

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
.userHolder h6,
.roleHolder {
    font-size: 12px;
}
.userHolder .badge {
    background-color: var(--role-badge);
}
.userHolder {
    width: 50%;
}
.imageHolder img {
    width: 100%;
}
.imageHolder {
    width: 25%;
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