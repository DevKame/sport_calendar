<template>
    <form @submit.prevent="change_training" @click="clickHandler" :class="{not_clickable: submitInProgress}" class="px-2 border border-danger d-flex flex-column justify-content-start align-items-center">
        <h1 class="me-auto">Edit training</h1>
        
        <div class="inputWrapper border border-danger d-flex flex-column justify-cotnent-start align-items-center">

            <div class="d-flex justify-content-between align-items-center">
                <label for="editName">Name</label>
            </div>
            <input
            @click="resetErrors"
            type="text"
            id="editName"
            name="editName"
            v-model.trim="editName"
            ref="trainingNameInput" />

            <small>Max. 24 characters</small>
            <div class="alertHolder my-2">
                <transition name="error" mode="out-in">
                    <error-alert v-if="nameError" @close-alert="nameError = false">
                        <p class="m-0 fw-bold">Enter a none-empty value below 27 characters</p>
                    </error-alert>
                    <error-alert v-else-if="doubleError" @close-alert="connectionError = false">
                        <p class="m-0 fw-bold">A training with that name aleary exists</p>
                    </error-alert>
                </transition>
            </div>
        </div>
        
        <ov-load v-if="loadingGroups"></ov-load>
        <div v-else class="w-100">
                <div v-if="groupArray.length > 0" class="inputWrapper groupWrapper border border-danger d-flex flex-column justify-cotnent-start align-items-center">
                    <div class="d-flex justify-content-between align-items-center pe-2">
                        <label>Limit to particular group(s)</label>
                        <label class="fst-italic">optional</label>
                    </div>

                    <div class="checkBoxes px-1 d-flex flex-column justify-content-start align-items-start">
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
                                <p class="m-0 fw-bold">Training succefully changed</p>
                            </success-alert>
                        </transition>
                    </div>
                </div>
        </div>
        

        <div class="w-100 mt-3 d-flex justify-content-end align-items-center">
            <form-loading v-if="submitInProgress" class="me-5"></form-loading>
            <router-link :to="{name:'Trainings'}" type="button" class="rounded-2 me-2 px-2">BACK</router-link>
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

// REPRESENTS THE LOADING OF POSSIBLE GROUPS TO CHOOSE
const loadingGroups = ref(false);

// ALL FETCHED GROUPS ARE BEEING SAVE HERE
const groupArray = ref([]);

let preparedTraining = reactive({});
// FETCHES ALL GROUPS TO RENDER AS CHECKBOXES
onMounted(async () => {
    loadingGroups.value = true;
    
    preparedTraining = {...store.getters["trainings/preparedTrainingForEdit"]};
    editName.value = preparedTraining.name;
    const groupdata = await store.dispatch("groups/getAllGroups");

    let groupsAsArray = [...groupdata.groups];

    let checkedGroups =
    groupsAsArray.filter(curr => preparedTraining.groups.includes(curr.name));

    groupArray.value = selectBoxes(groupsAsArray, checkedGroups);
    loadingGroups.value = false;
});
/** ADDS A CHECKED VALUE (Bool) TO EVERY GROUP THAT THE STUDENT
 *  ALSO HAVE TO MAKE SURE TO AUTOMATICLY SELECT THE CORRESPONDING
 *  CHECKBOXES
 * @param {Array} groups        => ALL GROUPS THAT WILL BE groupArray.value LATER
 * @param {Array} tgroups       => ALL GROUPS OF THE STUDENT
 * 
 * @returns {Array}             => MODIFIED groups WITH "CHECKED" VALUES */
function selectBoxes(groups, tgroups) {
    for(let grp of groups)
    {
        if(tgroups.find(curr => curr.id === grp.id))
        {
            grp.checked = true;
        }
    }
    return groups;
}
// VALUES BIND TO INPUT ELEMENTS WITH V-MODEL
const editName = ref("");
const chosenGroups = ref("[]");
// INDICATORS IF AND WHAT INPUT FIELD HAS AN ERROR
const nameError = ref(false);
const noChangeError = ref(false);
const doubleError = ref(false);
const groupError = ref(false);
const connectionError = ref(false);
const creationSuccess = ref(false);
// REFERENCE TO DOM ELEMENTS TO RESET AFTER SUCCESSFULL CREATION
const trainingNameInput = ref();

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
    nameError.value = false;
    noChangeError.value = false;
    doubleError.value = false;
    connectionError.value = false;
    creationSuccess.value = false;
}
// REPRESENTS THAT SUBMITTING IS IN PROGRESS
const submitInProgress = ref(false);
/** SUBMITTING PROCESS OF CREATING A STUDENT */
async function change_training() {
    submitInProgress.value = true;
    const valireq =
    {
        task: "validate-training-edit",
        id: preparedTraining.id,
        name: editName.value,
        chosengroups: chosenGroups.value,
    };
    resetErrors();
    let valiresponse = await store.dispatch("trainings/post", valireq);
    if(!valiresponse.success)
    {
        switch(valiresponse.reason) {
            case "found-double":
                doubleError.value = true;
                break;
            case "invalid-name":
                nameError.value = true;
                break;
            case "groups-too-long":
                groupError.value = true;
                break;
        }
    }
    else
    {
        const changereq =
        {
            task: "edit-training",
            id: preparedTraining.id,
            name: editName.value,
            chosengroups: chosenGroups.value,
        };
        let changeresponse = await store.dispatch("trainings/post", changereq);
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
//TODO: When i dont change anything and click "SAVE" i get "connection error"

//TODO:
/** When beeing on Edit-* Route, it only works if you directly
 *  came from the corresponding overview. If user refreshes or enters
 *  route manually, the needed vuex state wont be set. Thats why:
 *  set nav guard that redirects if Edit-* Routes are not invoked from
 *  an overview (FOR EVERY TOPIC(GROUPS/STUDENTS/etc)) */
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