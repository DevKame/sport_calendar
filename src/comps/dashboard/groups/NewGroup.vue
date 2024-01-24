<template>
    <form @submit.prevent="create_group" @click="clickHandler" class="px-2 border border-danger d-flex flex-column justify-content-start align-items-center">
        <h1 class="me-auto">Create a new group</h1>
        
        <div class="inputWrapper border border-danger d-flex flex-column justify-cotnent-start align-items-center">

            <div class="d-flex justify-content-between align-items-center">
                <label for="createName">Name</label>
            </div>

            <input @click="resetErrors" type="text" id="createName" name="createName" v-model.trim="createName" />

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
                </transition>
            </div>
        </div>
        <div class="w-100 mt-3 d-flex justify-content-end align-items-center">
            <router-link :to="{name:'Groups'}" type="button" class="rounded-2 me-2 px-2">BACK</router-link>
            <input type="submit" value="CREATE" class="btn-sec border border-black rounded-2">
        </div>
    </form>
</template>


<script setup>
import { defineEmits, ref } from 'vue';
import { useStore } from 'vuex';
import { useRouter } from 'vue-router';

const store = useStore();
const router = useRouter();

const emits = defineEmits([
    "empty-click",
]);

function clickHandler() {
    emits("empty-click");
}

const createName = ref("");

const nameError = ref(false);
const doubleError = ref(false);
const connectionError = ref(false);

function resetErrors() {
    nameError.value = false;
    doubleError.value = false;
    connectionError.value = false;
}

async function create_group() {
    resetErrors();
    let valiresponse = await store.dispatch("groups/validateGroupname", createName.value);
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
        let createresponse = await store.dispatch("groups/createGroup", createName.value);
        if(createresponse.success) {
            router.replace({name: "Groups"});
        } else {
            connectionError.value = true;
        }
    }
}
</script>

<style scoped>
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