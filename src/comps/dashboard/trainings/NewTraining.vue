<template>
    <form @submit.prevent="create_training" @click="clickHandler" :class="{not_clickable: submitInProgress}" class="container-xl px-2 pb-4 ms-xl-3 me-xl-auto d-flex flex-column justify-content-start align-items-center">
        <h1 class="me-auto mt-2">New training</h1>
        
        <div class="inputWrapper mt-3 d-flex flex-column justify-content-start align-items-center">

            <div class="d-flex justify-content-between align-items-center">
                <label for="createName">Name</label>
            </div>
            <input
            @click="resetErrors"
            type="text"
            id="createName"
            name="createName"
            v-model.trim="createName"
            ref="trainingNameInput" />

            <small>Max. 24 characters</small>
            <div class="alertHolder my-2">
                <transition name="error" mode="out-in">
                    <error-alert v-if="nameError" @close-alert="nameError = false">
                        <p class="m-0 fw-bold">Enter a none-empty value below 25 characters</p>
                    </error-alert>
                    <error-alert v-else-if="doubleError" @close-alert="connectionError = false">
                        <p class="m-0 fw-bold">A training with that name aleary exists</p>
                    </error-alert>
                </transition>
            </div>
        </div>
        
        <ov-load v-if="loadingGroups"></ov-load>
        <div v-else class="w-100">
                <div v-if="groupArray.length > 0" class="inputWrapper groupWrapper d-flex flex-column justify-content-start align-items-center">
                    <div class="d-flex justify-content-between align-items-center pe-2">
                        <label>Limit to particular group(s)</label>
                        <label class="fst-italic">optional</label>
                    </div>

                    <div class="checkBoxes px-1 d-flex flex-column flex-lg-row flex-lg-wrap justify-content-start align-items-start">
                        <group-checkboxes
                        v-for="group in groupArray"
                        :key="group.id"
                        :id="group.id"
                        :name="group.name"
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
                            <success-alert v-else-if="creationSuccess" @close-alert="creationSuccess = false">
                                <p class="m-0 fw-bold">Training succefully created</p>
                            </success-alert>
                        </transition>
                    </div>
                </div>
        </div>
        

        <div class="w-100 mt-3 d-flex justify-content-end align-items-center">
            <form-loading v-if="submitInProgress" class="me-5"></form-loading>
            <router-link :to="{name:'Trainings'}" type="button" class="rounded-2 me-2 px-2">BACK</router-link>
            <input type="submit" value="CREATE" class="btn-sec border border-black rounded-2">
        </div>
    </form>
</template>


<script setup>
import { defineEmits, ref, onMounted } from 'vue';
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
// FETCHES ALL GROUPS TO RENDER AS CHECKBOXES
onMounted(async () => {
    loadingGroups.value = true;
    const groupdata = await store.dispatch("groups/getAllGroups");
    loadingGroups.value = false;
    groupArray.value = [...groupdata.groups];
});
// VALUES BIND TO INPUT ELEMENTS WITH V-MODEL
const createName = ref("");
const chosenGroups = ref("[]");
// INDICATORS IF AND WHAT INPUT FIELD HAS AN ERROR
const nameError = ref(false);
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
    doubleError.value = false;
    connectionError.value = false;
    creationSuccess.value = false;
}
// REPRESENTS THAT SUBMITTING IS IN PROGRESS
const submitInProgress = ref(false);
/** SUBMITTING PROCESS OF CREATING A STUDENT */
async function create_training() {
    submitInProgress.value = true;
    const valireq =
    {
        task: "validate-training",
        name: createName.value,
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
        const createreq =
        {
            task: "create-training",
            name: createName.value,
            chosengroups: chosenGroups.value,
        };
        let createresponse = await store.dispatch("trainings/post", createreq);
        if(createresponse.success) {
            document.querySelectorAll("input[type='checkbox']").forEach(box => {
                box.checked = false;
            });
            createName.value = "";
            chosenGroups.value = "[]";
            creationSuccess.value = true;
            trainingNameInput.value.focus();
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