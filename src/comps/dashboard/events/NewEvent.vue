<template>
    <form @submit.prevent="create_event" @click="clickHandler" :class="{not_clickable: submitInProgress}" class="px-2 pb-4 border border-danger d-flex flex-column justify-content-start align-items-center">
        <div class="w-100 mt-2 d-flex justify-content-between align-items-center">
            <h1 class="m-0 me-auto">New event</h1>
            <router-link :to="{name:'Events'}" type="button" class="rounded-2 me-2 px-2">BACK</router-link>
        </div>
        
        <div class="inputWrapper mt-3 d-flex flex-column justify-cotnent-start align-items-center">

            <div class="d-flex justify-content-between align-items-center">
                <label for="createName">Name</label>
            </div>
            <input
            @input="createNameHandler"
            @click="resetErrors"
            type="mail"
            id="createName"
            name="createName"
            v-model.trim="createName"
            ref="eventNameInput" />

            <small>Max. 24 characters</small>
            <div class="alertHolder my-2">
                <transition name="error" mode="out-in">
                    <error-alert v-if="nameError" @close-alert="nameError = false">
                        <p class="m-0 fw-bold">Enter a none-empty value below 25 characters</p>
                    </error-alert>
                    <success-alert v-else-if="creationSuccess" @close-alert="creationSuccess = false">
                        <p class="m-0 fw-bold">Event succefully created</p>
                    </success-alert>
                </transition>
            </div>
        </div>
        <div class="inputWrapper d-flex flex-column justify-cotnent-start align-items-center">

            <div class="d-flex justify-content-between align-items-center pe-2">
                <label for="">Pre-defined training</label>
                <label class="fst-italic optional-label">optional</label>
            </div>

            
            <select
            @input="trainingSelectHandler"
            @click="resetErrors"
            ref="eventTrainingInput">
                <option value="no-training"></option>
                <option v-for="training in trainingArray"
                :key="training.id"
                :value="training.id">
                {{ training.name}}
                </option>
            </select>

        </div>

        <div class="inputWrapper mt-5 d-flex flex-column justify-cotnent-start align-items-center">

            <div class="d-flex justify-content-between align-items-center">
                <label for="createDatetime">Date and time</label>
            </div>
            <input
            @click="resetErrors"
            type="datetime-local"
            id="createDatetime"
            name="createDatetime"
            v-model.trim="createDatetime"
            ref="eventDatetimeInput" />

            <div class="alertHolder my-2">
                <transition name="error" mode="out-in">
                    <error-alert v-if="datetimeError" @close-alert="datetimeError = false">
                        <p class="m-0 fw-bold">Use this widget to enter a value thats in the future</p>
                    </error-alert>
                    <error-alert v-else-if="doubleError" @close-alert="doubleError = false">
                        <p class="m-0 fw-bold">An event with that date already exists</p>
                    </error-alert>
                </transition>
            </div>
        </div>
        
        <div class="inputWrapper d-flex flex-column justify-cotnent-start align-items-center">

            <div class="d-flex justify-content-between align-items-center">
                <label for="createMax">Max. participants</label>
            </div>
            <input
            @click="resetErrors"
            type="number"
            id="createMax"
            name="createMax"
            v-model.number="createMax"
            ref="eventMaxInput" />
            <small>Number over 0</small>
            
            <div class="alertHolder my-2">
                <transition name="error" mode="out-in">
                    <error-alert v-if="maxError" @close-alert="maxError = false">
                        <p class="m-0 fw-bold">Enter a number over 0</p>
                    </error-alert>
                </transition>
            </div>
        </div>
        
        <div class="inputWrapper d-flex flex-column justify-cotnent-start align-items-center">

            <div class="d-flex justify-content-between align-items-center pe-2">
                <label for="createTrainer">Trainer</label>
                <label class="fst-italic optional-label">optional</label>
            </div>

            <select
            @input="createTrainerHandler"
            @click="resetErrors"
            id="createTrainer"
            name="createTrainer"
            ref="eventTrainerInput"
            value="TRAINER">
                <option value="no-trainer">kein Trainer</option>
                <option v-for="trainer in trainerArray"
                :key="trainer.id"
                :value="trainer.id">
                {{ trainer.firstname + " " + trainer.lastname}}
                </option>
            </select>

            <div class="alertHolder my-2">
                <transition name="error" mode="out-in">
                    <error-alert v-if="trainerError" @close-alert="trainerError = false">
                        <p class="m-0 fw-bold">Enter a valid trainer-value only from this widget</p>
                    </error-alert>
                </transition>
            </div>
        </div>
        
        <div class="inputWrapper d-flex flex-column justify-cotnent-start align-items-center">

            <div class="d-flex justify-content-between align-items-center pe-2">
                <label for="createInfo">Information</label>
                <span class="optional-label">({{ infoChars }} / 256)</span>
                <label class="fst-italic optional-label">optional</label>
            </div>

            <textarea
            @click="resetErrors"
            id="createInfo"
            name="createInfo"
            v-model.trim="createInfo"
            ref="eventInfoInput"
            value="TRAINER">
            </textarea>

            <small>Max. 256 characters</small>
            <div class="alertHolder my-2">
                <transition name="error" mode="out-in">
                    <error-alert v-if="infoError" @close-alert="infoError = false">
                        <p class="m-0 fw-bold">Valid entry below 256 characters or nothing</p>
                    </error-alert>
                </transition>
            </div>
        </div>
        
        <ov-load v-if="loadingGroups"></ov-load>
        <div v-else class="w-100">
                <div v-if="groupArray.length > 0" class="inputWrapper groupWrapper d-flex flex-column justify-cotnent-start align-items-center">
                    <div class="d-flex justify-content-between align-items-center pe-2">
                        <label>Limit to particular group(s)</label>
                        <label class="fst-italic optional-label">optional</label>
                    </div>

                    <div class="checkBoxes px-1 d-flex flex-column justify-content-start align-items-start">
                        <group-checkboxes
                        v-for="group in groupArray"
                        :key="group.id"
                        :id="group.id"
                        :name="group.name"
                        @box-clicked="updateChosenGroups(group.id, true)"></group-checkboxes>
                    </div>
                    
                    <div class="alertHolder my-2">
                        <transition name="error" mode="out-in">
                            <error-alert v-if="connectionError" @close-alert="connectionError = false">
                                <p class="m-0 fw-bold">We have issues connecting to our data. Try again later. We have issues connecting to our data. Try again later.</p>
                            </error-alert>
                            <error-alert v-else-if="groupError" @close-alert="groupError = false">
                                <p class="m-0 fw-bold">Too many groups. Choose less</p>
                            </error-alert>
                            <success-alert v-else-if="creationSuccess" @close-alert="creationSuccess = false">
                                <p class="m-0 fw-bold">Event succefully created</p>
                            </success-alert>
                        </transition>
                    </div>
                </div>
        </div>
        

        <div class="w-100 mt-3 d-flex justify-content-end align-items-center">
            <form-loading v-if="submitInProgress" class="me-5"></form-loading>
            <router-link :to="{name:'Events'}" type="button" class="rounded-2 me-2 px-2">BACK</router-link>
            <input type="submit" value="CREATE" class="btn-sec border border-black rounded-2">
        </div>
    </form>
</template>


<script setup>
import { defineEmits, ref, onMounted, computed, watch, reactive } from 'vue';
import { useStore } from 'vuex';
import { useRouter } from 'vue-router';

import GroupCheckboxes from "../shared/GroupCheckboxes.vue";

const router = useRouter();
const store = useStore();

// HANDLING CLICKS FOR DASHBOARD TO KNOW WHEN TO COLLAPSE NAV
const emits = defineEmits([
    "empty-click",
]);
function clickHandler() {
    emits("empty-click");
}
// TOTAL CHARACTERS OF createInfo
const infoChars = computed(() => {
    return createInfo.value.length;
});
// REPRESENTS THE LOADING OF POSSIBLE GROUPS TO CHOOSE
const loadingGroups = ref(false);
// ALL FETCHED GROUPS AND TRAINERS ARE BEEING SAVE HERE
const groupArray = ref([]);
const trainerArray = ref([]);
const trainingArray = ref([]);
// FETCHES ALL GROUPS TO RENDER AS CHECKBOXES
onMounted(async () => {
    loadingGroups.value = true;
    const groupdata = await store.dispatch("groups/getAllGroups");
    loadingGroups.value = false;
    groupArray.value = [...groupdata.groups];
    
    const trainerreq = {task:"get-name-and-id"};
    const trainerdata = await store.dispatch("trainers/post", trainerreq);
    trainerArray.value = [...trainerdata.trainers];

    
    const trainingsdata = await store.dispatch("trainings/getAllTrainings");
    trainingArray.value = [...trainingsdata.trainings];
});
// VALUES BIND TO INPUT ELEMENTS WITH V-MODEL
const createName = ref("");
const createDatetime = ref("");
const createMax = ref(0);
const createTrainer = ref("no-trainer");
const createInfo = ref("");
const chosenGroups = ref("[]");

let splittedDatetime = reactive({});
// INDICATORS IF AND WHAT INPUT FIELD HAS AN ERROR
const nameError = ref(false);
const datetimeError = ref(false);
const maxError = ref(false);
const trainerError = ref(false);
const infoError = ref(false);
const groupError = ref(false);
const doubleError = ref(false);
const connectionError = ref(false);
const creationSuccess = ref(false);
// REFERENCE TO DOM ELEMENTS TO RESET AFTER SUCCESSFULL CREATION
const eventNameInput = ref();
const eventDatetimeInput = ref();
const eventMaxInput = ref();
const eventTrainingInput = ref();
const eventTrainerInput = ref();
const eventInfoInput = ref();

// RESETS chosenGroups ADN DESELCTS EVERY CHECKBOX:
function resetChosenGroups() {
    chosenGroups.value = "[]";
    document.querySelectorAll("input[type='checkbox']").forEach(box => {
        box.checked = false;
    });
}
// CALLBACK FOR <select> FOR TRAININGS
function trainingSelectHandler(e) {
    resetChosenGroups();
    if(e.target.value !== "no-training")
    {
        let trainingID = +e.target.value;
        let training = trainingArray.value.find(curr => curr.id === trainingID);
        let groupsOfTraining = JSON.parse(training.groups);
        document.querySelectorAll("input[type='checkbox']").forEach(box => {
            if(groupsOfTraining.includes(+box.id))
            {
                box.checked = true;
                updateChosenGroups(+box.id);
            }
        });
        createName.value = training.name;
    }
}
// CALLBACK FOR <select> FOR TRAINERS
function createTrainerHandler(e) {
    createTrainer.value = e.target.value;
}
// CALLBACK: RESETS THE <select> FOR TRAININGS WHEN TYPING SOMETHING FOR createName
function createNameHandler() {
    eventTrainingInput.value.value = "no-training";
}
// WHENEVER THE <input type="datetime-local"> CHANGES, SPLITS ITS DAY,MONTH,YEAR,HOUR AND MINUTE
// TO splittedDatetime
watch(createDatetime, val => {
    let year = val.slice(0, 4);
    let month = val.slice(5, 7);
    let day = val.slice(8, 10);
    let hour = val.slice(11, 13);
    let minute = val.slice(14, 16);
    splittedDatetime.day = day;
    splittedDatetime.month = month;
    splittedDatetime.year = year;
    splittedDatetime.hour = hour;
    splittedDatetime.minute = minute;
});
/** CALLBACK FOR CLICKING ON A GROUP CHECKBOX
 *  UPDATES THE chosenGroups JSON STRING BASED ON IF
 *  YOU CHECKED OR UN-CHECKED A GROUP
 * @param {number} id                           => ID OF THE CLICKED GROUP
 * @param {bool | undef.} resetTrainingSelect   => IF TRUE, RESET eventTrainingInput */
function updateChosenGroups(id, resetTrainingSelect) {
    if(resetTrainingSelect)
    {
        eventTrainingInput.value.value = "no-training";
    }
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
    datetimeError.value = false;
    maxError.value = false;
    trainerError.value = false;
    infoError.value = false;
    groupError.value = false;
    doubleError.value = false;
    connectionError.value = false;
    creationSuccess.value = false;
}
// REPRESENTS THAT SUBMITTING IS IN PROGRESS
const submitInProgress = ref(false);
/** SUBMITTING PROCESS OF CREATING A STUDENT */
async function create_event() {
    submitInProgress.value = true;
    const req =
    {
        task: "validate-event",
        name: createName.value,
        datetime: createDatetime.value,
        fulldate: splittedDatetime.day + "." + splittedDatetime.month + "." + splittedDatetime.year,
        fulltime: splittedDatetime.hour + ":" + splittedDatetime.minute,
        year: splittedDatetime.year,
        month: splittedDatetime.month,
        day: splittedDatetime.day,
        hour: splittedDatetime.hour,
        minute: splittedDatetime.minute,
        max: createMax.value,
        trainer: createTrainer.value,
        info: createInfo.value,
        groups: chosenGroups.value,
    };
    resetErrors();
    let valiresponse = await store.dispatch("events/post", req);
    if(!valiresponse.success)
    {
        switch(valiresponse.reason) {
            case "invalid-name-value":
                nameError.value = true;
                break;
            case "found-double":
                doubleError.value = true;
                break;
            case "invalid-datetime-value":
                datetimeError.value = true;
                break;
            case "invalid-max-value":
                maxError.value = true;
                break;
            case "invalid-trainer-value":
                trainerError.value = true;
                break;
            case "invalid-info-value":
                infoError.value = true;
                break;
            case "groups-too-long":
                groupError.value = true;
                break;
            case "connection-problems":
                router.replace({name: "Error"});
                break;
        }
    }
    else
    {
        req.task = "create-event";
        let createresponse = await store.dispatch("events/post", req);
        if(createresponse.success) {
            document.querySelectorAll("input[type='checkbox']").forEach(box => {
                box.checked = false;
            });
            createName.value = "";
            createDatetime.value = "";
            createMax.value = 0;
            createTrainer.value = "no-trainer";
            chosenGroups.value = "[]";
            creationSuccess.value = true;
            eventNameInput.value.focus();
        } else {
            connectionError.value = true;
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
.optional-label {
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