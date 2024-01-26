<template>
    <ov-load v-if="loadingRoute" class="mt-5"></ov-load>
    <form v-else @submit.prevent="change_trainer" @click="clickHandler" :class="{not_clickable: submitInProgress}" class="px-2 border border-danger d-flex flex-column justify-content-start align-items-center">
        <h1 class="me-auto">Edit trainer</h1>
        
        <div class="inputWrapper border border-danger d-flex flex-column justify-cotnent-start align-items-center">

            <div class="d-flex justify-content-between align-items-center">
                <label for="editEmail">Email</label>
            </div>
            <input
            @click="resetErrors"
            type="mail"
            id="editEmail"
            name="editEmail"
            v-model.trim="editEmail"
            ref="trainerEmailInput" />

            <small>Max. 40 characters</small>
            <div class="alertHolder my-2">
                <transition name="error" mode="out-in">
                    <error-alert v-if="emailError" @close-alert="emailError = false">
                        <p class="m-0 fw-bold">Enter a none-empty value below 41 characters</p>
                    </error-alert>
                    <error-alert v-else-if="doubleError" @close-alert="doubleError = false">
                        <p class="m-0 fw-bold">Email {{ editEmail }} already exists</p>
                    </error-alert>
                </transition>
            </div>
        </div>
        <div class="inputWrapper border border-danger d-flex flex-column justify-cotnent-start align-items-center">

            <div class="d-flex justify-content-between align-items-center">
                <label for="editFirstname">Firstname</label>
            </div>
            <input
            @click="resetErrors"
            type="text"
            id="editFirstname"
            name="editFirstname"
            v-model.trim="editFirstname"
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
        <div class="inputWrapper border border-danger d-flex flex-column justify-cotnent-start align-items-center">

            <div class="d-flex justify-content-between align-items-center">
                <label for="editLastname">Lastname</label>
            </div>
            <input
            @click="resetErrors"
            type="text"
            id="editLastname"
            name="editLastname"
            v-model.trim="editLastname"
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
        
        <div class="inputWrapper border border-danger d-flex flex-column justify-cotnent-start align-items-center">

            <div class="d-flex justify-content-between align-items-center">
                <label for="editRole">Role</label>
            </div>

            <select
            @click="resetErrors"
            id="editRole"
            name="editRole"
            v-model.trim="editRole"
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
                <error-alert v-else-if="noChangeError" @close-alert="noChangeError = false">
                    <p class="m-0 fw-bold">You made no changes</p>
                </error-alert>
                <success-alert v-else-if="creationSuccess" @close-alert="creationSuccess = false">
                    <p class="m-0 fw-bold">Group succefully created</p>
                </success-alert>
            </transition>
        </div>

        <div class="w-100 mt-3 d-flex justify-content-end align-items-center">
            <form-loading v-if="submitInProgress" class="me-5"></form-loading>
            <router-link :to="{name:'Trainers'}" type="button" class="rounded-2 me-2 px-2">BACK</router-link>
            <input type="submit" value="SAVE" class="btn-sec border border-black rounded-2">
        </div>
    </form>
</template>


<script setup>
import { defineEmits, ref, computed, onMounted, reactive } from 'vue';
import { useStore } from 'vuex';

const store = useStore();

// HANDLING CLICKS FOR DASHBOARD TO KNOW WHEN TO COLLAPSE NAV
const emits = defineEmits([
    "empty-click",
]);
function clickHandler() {
    emits("empty-click");
}
const userRole = computed(() => {
    return store.getters["auth/userRole"];
});

const loadingRoute = ref(true);
// FETCHES ALL GROUPS TO RENDER AS CHECKBOXES
// MAKES SURE TO ALREADY SELECT THE BOXES THAT FIT
// THE STUDENTS GROUPS
let preparedTrainer = reactive({});
onMounted(async () => {
    preparedTrainer = {...store.getters["trainers/preparedTrainerForEdit"]};
    editEmail.value = preparedTrainer.email;
    editFirstname.value = preparedTrainer.firstname;
    editLastname.value = preparedTrainer.lastname;
    editRole.value = preparedTrainer.role;

    loadingRoute.value = false;
});

// VALUES BIND TO INPUT ELEMENTS WITH V-MODEL
const editEmail = ref("");
const editFirstname = ref("");
const editLastname = ref("");
const editRole = ref("TRAINER");
// INDICATORS IF AND WHAT INPUT FIELD HAS AN ERROR
const emailError = ref(false);
const noChangeError = ref(false);
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
    noChangeError.value = true;
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
async function change_trainer() {
    submitInProgress.value = true;
    const valireq =
    {
        task: "validate-trainer-edit",
        id: preparedTrainer.id,
        email: editEmail.value,
        firstname: editFirstname.value,
        lastname: editLastname.value,
        role: editRole.value,
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
        const changereq =
        {
            task: "edit-trainer",
            id: preparedTrainer.id,
            email: editEmail.value,
            firstname: editFirstname.value,
            lastname: editLastname.value,
            role: editRole.value,
            chosengroups: "[]",
        };
        let changeresponse = await store.dispatch("trainers/post", changereq);
        if(changeresponse.success) {
            creationSuccess.value = true;
        } else {
            switch(changeresponse.reason) {
                case "no-changes":
                    noChangeError.value = true;
                    break;
                default:
                    connectionError.value = true;
                    break;
            }
        }
    }
    submitInProgress.value = false;

}
</script>

<style scoped>
.checkBoxes {
    border: 2px solid var(--tert);
    border-radius: 15px;
}
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
}
form h1 {
    font-family: "Raleway SBold 600";
}
form {
    width: 100%;
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