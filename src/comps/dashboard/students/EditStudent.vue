<template>
    <ov-load v-if="loadingRoute" class="mt-5"></ov-load>
    <form v-else @submit.prevent="change_student" @click="clickHandler" :class="{not_clickable: submitInProgress}" class="px-2 pb-4 ms-xl-5 me-xl-auto d-flex flex-column justify-content-start align-items-center">
        <h1 class="me-auto mt-2">Edit student</h1>
        
        <div class="inputWrapper mt-3 d-flex flex-column justify-content-start align-items-center">

            <div class="d-flex justify-content-between align-items-center">
                <label for="editEmail">Email</label>
            </div>
            <input
            @click="resetErrors"
            type="mail"
            id="editEmail"
            name="editEmail"
            v-model.trim="editEmail"
            ref="studentEmailInput" />

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
        <div class="inputWrapper d-flex flex-column justify-content-start align-items-center">

            <div class="d-flex justify-content-between align-items-center">
                <label for="editFirstname">Firstname</label>
            </div>
            <input
            @click="resetErrors"
            type="text"
            id="editFirstname"
            name="editFirstname"
            v-model.trim="editFirstname"
            ref="studentFirstnameInput" />

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
                <label for="editLastname">Lastname</label>
            </div>
            <input
            @click="resetErrors"
            type="text"
            id="editLastname"
            name="editLastname"
            v-model.trim="editLastname"
            ref="studentLastnameInput" />

            <small>Max. 32 characters</small>
            <div class="alertHolder my-2">
                <transition name="error" mode="out-in">
                    <error-alert v-if="lastnameError" @close-alert="lastnameError = false">
                        <p class="m-0 fw-bold">Enter a none-empty value below 33 characters</p>
                    </error-alert>
                </transition>
            </div>
        </div>
        
        <div v-if="groupArray.length > 0" class="inputWrapper groupWrapper d-flex flex-column justify-content-start align-items-center">
            <div class="d-flex justify-content-between align-items-center pe-2">
                <label>Limit to particular group(s)</label>
                <label class="fst-italic">optional</label>
            </div>

            <div class="checkBoxes px-1 d-flex flex-column flex-md-row flex-md-wrap justify-content-start align-items-start">
                <group-checkboxes
                v-for="group in groupArray"
                :key="group.id"
                :id="group.id"
                :name="group.name"
                :checked="group.checked"
                @box-clicked="updateChosenGroups(group.id)"></group-checkboxes>
            </div>
            
            <div class="alertHolder my-2">
                <transition name="error" mode="out-in">
                    <error-alert v-if="connectionError" @close-alert="connectionError = false">
                        <p class="m-0 fw-bold">We have issues connecting to our data. Try again later.</p>
                    </error-alert>
                    <error-alert v-else-if="groupError" @close-alert="connectionError = false">
                        <p class="m-0 fw-bold">Too much groups. Choose less please</p>
                    </error-alert>
                    <error-alert v-else-if="noChangeError" @close-alert="noChangeError = false">
                        <p class="m-0 fw-bold">You made no changes</p>
                    </error-alert>
                    <success-alert v-else-if="creationSuccess" @close-alert="creationSuccess = false">
                        <p class="m-0 fw-bold">Student succefully changed</p>
                    </success-alert>
                </transition>
            </div>
        </div>
        

        <div class="w-100 mt-3 d-flex justify-content-end align-items-center">
            <form-loading v-if="submitInProgress" class="me-5"></form-loading>
            <router-link :to="{name:'Students'}" type="button" class="rounded-2 me-2 px-2">BACK</router-link>
            <input type="submit" value="SAVE" class="btn-sec border border-black rounded-2">
        </div>
    </form>
</template>


<script setup>
import { defineEmits, ref, onMounted, reactive } from 'vue';
import { useStore } from 'vuex';

import GroupCheckboxes from "../shared/GroupCheckboxes.vue";

const store = useStore();

// HANDLING CLICKS FOR DASHBOARD TO KNOW WHEN TO COLLAPSE NAV
const emits = defineEmits([
    "empty-click",
]);
function clickHandler() {
    emits("empty-click");
}

// REPRESENTS THE LOADING OF COMPLETE ROUTE
const loadingRoute = ref(true);

let preparedStudent = reactive({});
// ALL FETCHED GROUPS ARE BEEING SAVE HERE
const groupArray = ref([]);
// FETCHES ALL GROUPS TO RENDER AS CHECKBOXES
// MAKES SURE TO ALREADY SELECT THE BOXES THAT FIT
// THE STUDENTS GROUPS
onMounted(async () => {
    preparedStudent = {...store.getters["students/preparedStudentForEdit"]};
    editEmail.value = preparedStudent.email;
    editFirstname.value = preparedStudent.firstname;
    editLastname.value = preparedStudent.lastname;
    const groupdata = await store.dispatch("groups/getAllGroups");

    let groupsAsArray = [...groupdata.groups];

    let checkedGroups =
    groupsAsArray.filter(curr => preparedStudent.groups.includes(curr.name));

    groupArray.value = selectBoxes(groupsAsArray, checkedGroups);

    loadingRoute.value = false;
});
/** ADDS A CHECKED VALUE (Bool) TO EVERY GROUP THAT THE STUDENT
 *  ALSO HAS TO MAKE SURE TO AUTOMATICLY SELECT THE CORRESPONDING
 *  CHECKBOXES
 * @param {Array} groups        => ALL GROUPS THAT WILL BE groupArray.value LATER
 * @param {Array} sgroups       => ALL GROUPS OF THE STUDENT
 * 
 * @returns {Array}             => MODIFIED groups WITH "CHECKED" VALUES */
function selectBoxes(groups, sgroups) {
    for(let grp of groups)
    {
        if(sgroups.find(curr => curr.id === grp.id))
        {
            grp.checked = true;
        }
    }
    return groups;
}
// VALUES BIND TO INPUT ELEMENTS WITH V-MODEL
const editEmail = ref("");
const editFirstname = ref("");
const editLastname = ref("");
const chosenGroups = ref("[]");
// INDICATORS IF AND WHAT INPUT FIELD HAS AN ERROR
const emailError = ref(false);
const noChangeError = ref(false);
const firstnameError = ref(false);
const lastnameError = ref(false);
const groupError = ref(false);
const doubleError = ref(false);
const connectionError = ref(false);
const creationSuccess = ref(false);
// REFERENCE TO DOM ELEMENTS TO RESET AFTER SUCCESSFULL CREATION
const studentEmailInput = ref();
const studentFirstnameInput = ref();
const studentLastnameInput = ref();

/** CALLBACK FOR CLICKING ON A GROUP CHECKBOX
 *  UPDATES THE chosenGroups JSON STRING BASED ON IF
 *  YOU CHECKED OR UN-CHECKED A GROUP
 * @param {number} id   => ID OF THE CLICKED GROUP */
function updateChosenGroups(id) {
    const oldGroups = JSON.parse(chosenGroups.value);
    if(oldGroups.includes(id))
    {
        oldGroups.splice(oldGroups.indexOf(id), 1);
    } else {
        oldGroups.push(id);
    }
    chosenGroups.value = JSON.stringify(oldGroups);
}
// UN-DISPLAYS POTENTIAL ERRORS
function resetErrors() {
    emailError.value = false;
    noChangeError.value = false;
    firstnameError.value = false;
    lastnameError.value = false;
    groupError.value = false;
    doubleError.value = false;
    connectionError.value = false;
    creationSuccess.value = false;
}


// INDICATES THAT SUBMITTING IS IN PROGRESS
const submitInProgress = ref(false);

/** SUBMITTING PROCESS OF CREATING A STUDENT */
async function change_student() {
    submitInProgress.value = true;
    const req =
    {
        task: "validate-student-edit",
        id: preparedStudent.id,
        email: editEmail.value,
        firstname: editFirstname.value,
        lastname: editLastname.value,
        chosengroups: chosenGroups.value,
    };
    resetErrors();
    let valiresponse = await store.dispatch("students/post", req);
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
            case "groups-too-long":
                groupError.value = true;
                break;
        }
    }
    else
    {
        req.task = "edit-student";
        let changeresponse = await store.dispatch("students/post", req);
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