<template>
    <li class="trainerItem my-2 d-flex justify-content-start align-items-center">
        <div @click="toggleMenu" class="toggleOptions position-relative rounded-circle d-flex justify-content-center align-items-center">
            <fa-icon icon="fa-solid fa-gear"></fa-icon>

                <button v-if="menuOn" @click="editItem" class="position-absolute edit-option btn-edit border border-black d-flex justify-content-start align-items-center rounded-pill">
                    <fa-icon icon="fa-solid fa-pencil" class="me-2"></fa-icon>
                    EDIT
                </button>

                <button v-if="menuOn" @click="deleteItem" class="position-absolute delete-option btn-delete border border-black d-flex justify-content-start align-items-center rounded-pill">
                    <fa-icon icon="fa-solid fa-ban" class="me-2"></fa-icon>
                    DELETE
                </button>
                
        </div>

        <div class="actualListItem ms-3 px-2 py-1">
            <header @click="detailView = !detailView" class="w-100 h-100 d-flex justify-content-start align-items-center">
                <p class="m-0 me-1">{{ props.name }}</p>
            </header>
            <transition name="details">
                <section v-if="detailView" class="d-flex flex-wrap justify-content-start align-items-center">
                    <div v-if="groups.length > 0" class="w-100 d-flex flex-wrap justify-content-start align-items-start">
                        <hr class="bg-sec w-100 m-0" />
                        <span v-for="group in groups" :key="group" class="badge text-black bg-neutral my-1 mx-1">
                            {{ group }}
                        </span>
                    </div>
                </section>
            </transition>
        </div>
    </li>
</template>

<script setup>
import { defineProps, defineEmits, ref } from 'vue';
import { useStore } from 'vuex';

const store = useStore();

// DISPLAYED VALUES ARE PROVIDED FROM <TheTrainingOverview>
let props = defineProps([
    "name",
    "groups",
]);
let emits = defineEmits([
    "delete-item",
    "edit-item",
]);
// INDICATES IF THE EDIT MENU IS ON OR OFF
const menuOn = ref(false);
// TOGGLES THE VISIBILITY OF THE EDIT MENU
function toggleMenu() {
    menuOn.value = !menuOn.value;
    if(!menuOn.value) {
        resetEditTraining();
    }
}
// TELLS PARENT TO INVOKE AN EDIT ACTION
function deleteItem() {
    resetEditTraining();
    emits("delete-item");
}
// TELLS PARENT TO INVOKE A DELETE ACTION
function editItem() {
    emits("edit-item");
}
// RESETS POSSIBLE PREPARED DATA FOR EDITING
function resetEditTraining() {
    store.commit["trainings/resetTrainingDataForEdit"];
}

const detailView = ref(false);
</script>


<style scoped>
header .badge {
    font-size: 12px;
}
header p {
    font-size: 14px;
}
hr {
    height: 2px;
}
.delete-option {
    z-index: 10;
    left: 450%;
}
.edit-option {
    z-index: 10;
    left: 200%;
}
.edit-option,
.delete-option {
    top: 0;
    height: 100%;
}
button {
    font-size: 12px;
}
.actualListItem {
    flex: 1;
    border: 2px solid var(--sec);
    border-top-left-radius: 20px;
    border-bottom-left-radius: 20px;
    border-right: none;
}
.toggleOptions {
    border: 1px solid black;
    background-color: var(--sec);
    width: 30px;
    aspect-ratio: 1;
}
.trainerItem {
    font-family: "Raleway Reg 400";
}
.details-enter-from
.details-leave-to {
    opacity: 0;
    transform: translate(0, -100%);
}
.details-enter-active
.details-leave-active {
    transition: all .3s ease;
}
.details-enter-to
.details-leave-from {
    opacity: 1;
    transform: translate(0, 0);
}
</style>

