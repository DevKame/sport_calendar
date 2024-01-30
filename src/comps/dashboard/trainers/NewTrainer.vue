<template>
    <ov-load v-if="contentLoading" class="mt-5"></ov-load>
    <form v-else @submit.prevent="create_trainer" @click="clickHandler" :class="{not_clickable: submitInProgress}" class="px-2 pb-4 ms-xl-5 me-xl-auto d-flex flex-column justify-content-start align-items-center">
        <h1 class="me-auto mt-2">New trainer</h1>
        
        <div class="inputWrapper mt-3 d-flex flex-column justify-content-start align-items-center">

            <div class="d-flex justify-content-between align-items-center">
                <label for="createEmail">Email</label>
            </div>
            <input
            @click="resetErrors"
            type="mail"
            id="createEmail"
            name="createEmail"
            v-model.trim="createEmail"
            ref="trainerEmailInput" />

            <small>Max. 40 characters</small>
            <div class="alertHolder my-2">
                <transition name="error" mode="out-in">
                    <error-alert v-if="emailError" @close-alert="emailError = false">
                        <p class="m-0 fw-bold">Enter a none-empty value below 41 characters</p>
                    </error-alert>
                    <error-alert v-else-if="doubleError" @close-alert="doubleError = false">
                        <p class="m-0 fw-bold">Email {{ createEmail }} already exists</p>
                    </error-alert>
                </transition>
            </div>
        </div>
        <div class="inputWrapper d-flex flex-column justify-content-start align-items-center">

            <div class="d-flex justify-content-between align-items-center">
                <label for="createFirstname">Firstname</label>
            </div>
            <input
            @click="resetErrors"
            type="text"
            id="createFirstname"
            name="createFirstname"
            v-model.trim="createFirstname"
            ref="trainerFirstnameInput" />

            <small>Max. 16 characters</small>
            <div class="alertHolder my-2">
                <transition name="error" mode="out-in">
                    <error-alert v-if="firstnameError" @close-alert="firstnameError = false">
                        <p class="m-0 fw-bold">Enter a none-empty value below 17 characters</p>
                    </error-alert>
                </transition>
            </div>
        </div>
        <div class="inputWrapper d-flex flex-column justify-content-start align-items-center">

            <div class="d-flex justify-content-between align-items-center">
                <label for="createLastname">Lastname</label>
            </div>
            <input
            @click="resetErrors"
            type="text"
            id="createLastname"
            name="createLastname"
            v-model.trim="createLastname"
            ref="trainerLastnameInput" />

            <small>Max. 32 characters</small>
            <div class="alertHolder my-2">
                <transition name="error" mode="out-in">
                    <error-alert v-if="lastnameError" @close-alert="lastnameError = false">
                        <p class="m-0 fw-bold">Enter a none-empty value below 33 characters</p>
                    </error-alert>
                </transition>
            </div>
        </div>
        
        <div class="inputWrapper d-flex flex-column justify-content-start align-items-center">

            <div class="d-flex justify-content-between align-items-center">
                <label for="createRole">Role</label>
            </div>

            <select
            @click="resetErrors"
            id="createRole"
            name="createRole"
            v-model.trim="createRole"
            ref="trainerRoleInput"
            value="TRAINER">
                <option value="TRAINER">Trainer</option>
                <option v-if="userRole === 'ADMIN'" value="SENIOR-TRAINER">Senior trainer</option>
                <option v-if="userRole === 'ADMIN'" value="ADMIN">Admin</option>
            </select>
            
            <div class="alertHolder my-2">
                <transition name="error" mode="out-in">
                    <error-alert v-if="roleError" @close-alert="roleError = false">
                        <p class="m-0 fw-bold">Enter a valid role</p>
                    </error-alert>
                </transition>
            </div>
        </div>
        
        <div class="alertHolder w-100 my-2">
            <transition name="error" mode="out-in">
                <error-alert v-if="connectionError" @close-alert="connectionError = false">
                    <p class="m-0 fw-bold">We have issues connecting to our data. Try again later. We have issues connecting to our data. Try again later.</p>
                </error-alert>
                <success-alert v-else-if="creationSuccess" @close-alert="creationSuccess = false">
                    <p class="m-0 fw-bold">Group succefully created</p>
                </success-alert>
            </transition>
        </div>

        <div class="w-100 mt-3 d-flex justify-content-end align-items-center">
            <form-loading v-if="submitInProgress" class="me-5"></form-loading>
            <router-link :to="{name:'Trainers'}" type="button" class="rounded-2 me-2 px-2">BACK</router-link>
            <input type="submit" value="CREATE" class="btn-sec border border-black rounded-2">
        </div>
    </form>
</template>


<script setup>
import { defineEmits, ref, computed, onMounted } from 'vue';
import { useStore } from 'vuex';

const store = useStore();

// HANDLING CLICKS FOR DASHBOARD TO KNOW WHEN TO COLLAPSE NAV
const emits = defineEmits([
    "empty-click",
]);
function clickHandler() {
    emits("empty-click");
}
// IS NEEDED TO DETERMINE WHAT ROLES CAN BE CREATED
const userRole = computed(() => {
    return store.getters["auth/userRole"];
});

// INDICATES LOADING OF THE ROUTE
const contentLoading = ref(true);
// STOPS THE LOADING ANIMATION
onMounted(() => {
    contentLoading.value = false;
});
// VALUES BIND TO INPUT ELEMENTS WITH V-MODEL
const createEmail = ref("");
const createFirstname = ref("");
const createLastname = ref("");
const createRole = ref("TRAINER");
// INDICATORS IF AND WHAT INPUT FIELD HAS AN ERROR
const emailError = ref(false);
const firstnameError = ref(false);
const lastnameError = ref(false);
const roleError = ref(false);
const doubleError = ref(false);
const connectionError = ref(false);
const creationSuccess = ref(false);
// REFERENCE TO DOM ELEMENTS TO RESET AFTER SUCCESSFULL CREATION
const trainerEmailInput = ref();
const trainerFirstnameInput = ref();
const trainerLastnameInput = ref();
const trainerRoleInput = ref();
// UN-DISPLAYS POTENTIAL ERRORS
function resetErrors() {
    emailError.value = false;
    firstnameError.value = false;
    lastnameError.value = false;
    doubleError.value = false;
    roleError.value = false;
    connectionError.value = false;
    creationSuccess.value = false;
}
// INDICATES THAT SUBMITTING IS IN PROGRESS
const submitInProgress = ref(false);
/** SUBMITTING PROCESS OF CREATING A STUDENT */
async function create_trainer() {
    submitInProgress.value = true;
    const valireq =
    {
        task: "validate-trainer",
        email: createEmail.value,
        firstname: createFirstname.value,
        lastname: createLastname.value,
        role: createRole.value,
    };
    resetErrors();
    let valiresponse = await store.dispatch("trainers/post", valireq);
    if(!valiresponse.success)
    {
        switch(valiresponse.reason) {
            case "invalid-email-value":
            case "email-too-long":
                emailError.value = true;
                break;
            case "found-double":
                doubleError.value = true;
                break;
            case "invalid-firstname-value":
            case "firstname-too-long":
                firstnameError.value = true;
                break;
            case "invalid-lastname-value":
            case "lastname-too-long":
                lastnameError.value = true;
                break;
            case "invalid-role":
                roleError.value = true;
                break;
            case "connection-problems":
                connectionError.value = true;
                break;
        }
    }
    else
    {
        const createreq =
        {
            task: "create-trainer",
            email: createEmail.value,
            firstname: createFirstname.value,
            lastname: createLastname.value,
            role: createRole.value,
            chosengroups: "[]",
        };
        let createresponse = await store.dispatch("trainers/post", createreq);
        if(createresponse.success) {
            createEmail.value = "";
            createFirstname.value = "";
            createLastname.value = "";
            createRole.value = "";
            creationSuccess.value = true;
            trainerEmailInput.value.focus();
        } else {
            connectionError.value = true;
        }
    }
    submitInProgress.value = false;

}
</script>

<style scoped>
/* .selectHolder {
    min-height: 30px;
} */
.groupWrapper label:last-child {
    font-size: 12px;
}
.not_clickable {
    pointer-events: none;
}
a[type="button"] {
    background-color: transparent;
    border: 2px solid var(--sec);
    color: var(--sec);
}
.alertHolder {
    height: 35px;
}
.inputWrapper small {
    font-size: 10px;
}
.inputWrapper > * {
    width: 100%;
}
.inputWrapper {
    width: 100%;
    overflow: hidden;
}
form h1 {
    font-family: "Raleway SBold 600";
}
form {
    width: 100%;
    max-width: 700px;
    font-family: "Raleway Reg 400";
}
.error-enter-from {
    transform: translate(-100%, 0);
}
.error-leave-to {
    transform: translate(100%, 0);
}
.error-enter-active,
.error-leave-active {
    transition: transform .3s ease-out;
}
.error-enter-to,
.error-leave-from {
    transform: translate(0, 0);
}
</style>