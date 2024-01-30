<template>
    <form @submit.prevent="create_group" @click="clickHandler" :class="{not_clickable: submitInProgress}" class="px-2 pb-4 ms-xl-5 d-flex flex-column justify-content-start align-items-center">
        <h1 class="m-0 me-auto mt-2">New group</h1>
        
        <div class="inputWrapper mt-5 d-flex flex-column justify-content-start align-items-center">

            <div class="d-flex justify-content-between align-items-center">
                <label for="createName">Name</label>
            </div>

            <input
            @click="resetErrors"
            type="text"
            id="createName"
            name="createName"
            v-model.trim="createName"
            ref="groupNameInput" />

            <small>Max. 25 characters</small>
            <div class="alertHolder my-2">
                <transition name="error" mode="out-in">
                    <error-alert v-if="nameError" @close-alert="nameError = false">
                        <p class="m-0 fw-bold">Enter a none-empty value below 26 characters</p>
                    </error-alert>
                    <error-alert v-else-if="doubleError" @close-alert="doubleError = false">
                        <p class="m-0 fw-bold">Group {{ createName }} already exists</p>
                    </error-alert>
                    <error-alert v-else-if="connectionError" @close-alert="connectionError = false">
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
            <router-link :to="{name:'Groups'}" type="button" class="rounded-2 me-2 px-2">BACK</router-link>
            <input type="submit" value="CREATE" class="btn-sec border border-black rounded-2">
        </div>
    </form>
</template>


<script setup>
import { defineEmits, ref } from 'vue';
import { useStore } from 'vuex';

const store = useStore();

// HANDLING CLICKS FOR DASHBOARD TO KNOW WHEN TO COLLAPSE NAV
const emits = defineEmits([
    "empty-click",
]);
function clickHandler() {
    emits("empty-click");
}

// VALUES BIND TO INPUT ELEMENTS WITH V-MODEL
const createName = ref("");
// INDICATORS IF AND WHAT INPUT FIELD HAS AN ERROR
const nameError = ref(false);
const doubleError = ref(false);
const connectionError = ref(false);
const creationSuccess = ref(false);
// REFERENCE TO DOM ELEMENTS TO RESET AFTER SUCCESSFULL CREATION
const groupNameInput = ref();

// UN-DISPLAYS POTENTIAL ERRORS
function resetErrors() {
    nameError.value = false;
    doubleError.value = false;
    connectionError.value = false;
    creationSuccess.value = false;
}
// REPRESENTS THAT SUBMITTING IS IN PROGRESS
const submitInProgress = ref(false);
/** SUBMITTING PROCESS OF CREATING A GROUP */
async function create_group() {
    submitInProgress.value = true;
    const valireq =
    {
        task: "validate-group",
        name: createName.value,
    };
    resetErrors();
    let valiresponse = await store.dispatch("groups/post", valireq);
    if(!valiresponse.success)
    {
        switch(valiresponse.reason) {
            case "invalid-value":
            case "too-long":
                nameError.value = true;
                break;
            case "found-double":
                doubleError.value = true;
                break;
        }
    }
    else
    {
        const createreq =
        {
            task: "create-group",
            name: createName.value,
        };
        let createresponse = await store.dispatch("groups/post", createreq);
        if(createresponse.success) {
            creationSuccess.value = true;
            createName.value = "";
            groupNameInput.value.focus();
        } else {
            connectionError.value = true;
        }
    }
    submitInProgress.value = false;

}
</script>

<style scoped>
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