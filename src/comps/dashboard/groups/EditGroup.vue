<template>
    <form @submit.prevent="edit_group" @click="clickHandler" :class="{not_clickable: submitInProgress}" class="px-2 border border-danger d-flex flex-column justify-content-start align-items-center">
        <h1 class="m-0 me-auto mt-2">Edit group</h1>
        
        <div class="inputWrapper mt-3 border border-danger d-flex flex-column justify-cotnent-start align-items-center">

            <div class="d-flex justify-content-between align-items-center">
                <label for="editName">Name</label>
            </div>

            <input
            @click="resetErrors"
            type="text"
            id="editName"
            name="editName"
            v-model.trim="editName"
            ref="groupNameInput" />

            <small>Max. 25 characters</small>
            <div class="alertHolder my-2">
                <transition name="error" mode="out-in">
                    <error-alert v-if="nameError" @close-alert="nameError = false">
                        <p class="m-0 fw-bold">Enter a none-empty value below 26 characters</p>
                    </error-alert>
                    <error-alert v-else-if="doubleError" @close-alert="doubleError = false">
                        <p class="m-0 fw-bold">Group {{ editName }} already exists</p>
                    </error-alert>
                    <error-alert v-else-if="noChangeError" @close-alert="noChangeError = false">
                        <p class="m-0 fw-bold">You made no changes</p>
                    </error-alert>
                    <error-alert v-else-if="connectionError" @close-alert="connectionError = false">
                        <p class="m-0 fw-bold">We have issues connecting to our data. Try again later. We have issues connecting to our data. Try again later.</p>
                    </error-alert>
                    <success-alert v-else-if="editionSuccess" @close-alert="editionSuccess = false">
                        <p class="m-0 fw-bold">Group succefully renamed</p>
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
import { defineEmits, ref, onMounted } from 'vue';
import { useStore } from 'vuex';

const store = useStore();

// HANDLING CLICKS FOR DASHBOARD TO KNOW WHEN TO COLLAPSE NAV
const emits = defineEmits([
    "empty-click",
]);

function clickHandler() {
    emits("empty-click");
}
// VALUES THAT TELL THE BACKEND WHAT TO EDIT
const editName = ref("");
const editID = ref(0);
// SETS THE VALUES OUT OF STATE
onMounted(() => {
    editName.value = store.getters["groups/editGroupname"];
    editID.value = store.getters["groups/editGroupID"];
});

// INDICATORS IF AND WHAT INPUT FIELD HAS AN ERROR
const nameError = ref(false);
const noChangeError = ref(false);
const doubleError = ref(false);
const connectionError = ref(false);
const editionSuccess = ref(false);

// REFERENCE TO DOM ELEMENTS TO RESET AFTER SUCCESSFULL CREATION
const groupNameInput = ref();
// UN-DISPLAYS POTENTIAL ERRORS
function resetErrors() {
    nameError.value = false;
    noChangeError.value = true;
    doubleError.value = false;
    connectionError.value = false;
    editionSuccess.value = false;
}
// REPRESENTS THAT SUBMITTING IS IN PROGRESS
const submitInProgress = ref(false);
/** SUBMITTING PROCESS OF EDITING THE GROUP */
async function edit_group() {
    submitInProgress.value = true;
    const valireq =
    {
        task: "validate-group-edit",
        name: editName.value,
        id: store.getters["groups/editGroupID"],
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
            case "connection- problems":
                connectionError.value = true;
                break;
        }
    }
    else
    {
        const editreq =
        {
            task: "edit-group",
            name: editName.value,
            id: store.getters["groups/editGroupID"],
        };
        let changeresponse = await store.dispatch("groups/post", editreq);
        if(changeresponse.success) {
            editionSuccess.value = true;
            groupNameInput.value.focus();
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