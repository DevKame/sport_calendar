<template>
    <ov-load v-if="loadingRoute" class="mt-5"></ov-load>
    <form v-else @submit.prevent="create_student" @click="clickHandler" :class="{not_clickable: submitInProgress}" class="px-2 border border-danger d-flex flex-column justify-content-start align-items-center">
        <h1 class="me-auto">Edit student</h1>
        
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
        
        <div v-if="groupArray.length > 0" class="inputWrapper groupWrapper border border-danger d-flex flex-column justify-cotnent-start align-items-center">
            <div class="d-flex justify-content-between align-items-center pe-2">
                <label>Limit to particular group(s)</label>
                <label class="fst-italic">optional</label>
            </div>

            <div class="checkBoxes border border-info d-flex flex-column justify-content-start align-items-start">
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
                        <p class="m-0 fw-bold">We have issues connecting to our data. Try again later. We have issues connecting to our data. Try again later.</p>
                    </error-alert>
                    <success-alert v-else-if="creationSuccess" @close-alert="creationSuccess = false">
                        <p class="m-0 fw-bold">Group succefully created</p>
                    </success-alert>
                </transition>
            </div>
        </div>
        

        <div class="w-100 mt-3 d-flex justify-content-end align-items-center">
            <form-loading v-if="submitInProgress" class="me-5"></form-loading>
            <router-link :to="{name:'Students'}" type="button" class="rounded-2 me-2 px-2">BACK</router-link>
            <input type="submit" value="CREATE" class="btn-sec border border-black rounded-2">
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
onMounted(async () => {
    console.clear();
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
const firstnameError = ref(false);
const lastnameError = ref(false);
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
    showData();
}
//DEV: SHOWING THE COMPLETE FORM
function showData() {
    console.clear();
    console.log("Email:", editEmail.value);
    console.log("Firstname:", editFirstname.value);
    console.log("Lastname:", editLastname.value);
    console.log("chosenGroups:", chosenGroups.value);
}
// UN-DISPLAYS POTENTIAL ERRORS
function resetErrors() {
    emailError.value = false;
    firstnameError.value = false;
    lastnameError.value = false;
    doubleError.value = false;
    connectionError.value = false;
    creationSuccess.value = false;
}


// REPRESENTS THAT SUBMITTING IS IN PROGRESS
const submitInProgress = ref(false);
/** SUBMITTING PROCESS OF CREATING A STUDENT */
async function create_student() {
    submitInProgress.value = true;
    const valireq =
    {
        task: "validate-student",
        email: editEmail.value,
        firstname: editFirstname.value,
        lastname: editLastname.value,
        chosengroups: chosenGroups.value,
    };
    resetErrors();
    let valiresponse = await store.dispatch("students/post", valireq);
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
        }
    }
    else
    {
        const createreq =
        {
            task: "create-student",
            email: editEmail.value,
            firstname: editFirstname.value,
            lastname: editLastname.value,
            chosengroups: chosenGroups.value,
        };
        let createresponse = await store.dispatch("students/post", createreq);
        if(createresponse.success) {
            document.querySelectorAll("input[type='checkbox']").forEach(box => {
                box.checked = false;
            });
            editEmail.value = "";
            editFirstname.value = "";
            editLastname.value = "";
            chosenGroups.value = "[]";
            creationSuccess.value = true;
            studentEmailInput.value.focus();
        } else {
            connectionError.value = true;
        }
    }
    submitInProgress.value = false;

}
</script>

<style scoped>
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