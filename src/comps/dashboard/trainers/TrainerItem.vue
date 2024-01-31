<template>
    <li class="trainerItem my-2 ps-2 d-flex justify-content-start align-items-center">
        <div @click="toggleMenu" class="toggleOptions position-relative rounded-circle d-flex justify-content-center align-items-center">
            <fa-icon icon="fa-solid fa-gear"></fa-icon>

                <button @click="editItem" :class="{is_outside: menuOn}" class="position-absolute edit-option option-buttons btn-edit border border-black d-flex justify-content-start align-items-center rounded-pill">
                    <fa-icon icon="fa-solid fa-pencil" class="me-2"></fa-icon>
                    EDIT
                </button>

                <button @click="deleteItem" :class="{is_outside: menuOn}" class="position-absolute delete-option option-buttons btn-delete border border-black d-flex justify-content-start align-items-center rounded-pill">
                    <fa-icon icon="fa-solid fa-ban" class="me-2"></fa-icon>
                    DELETE
                </button>
                
        </div>

        <div class="actualListItem ms-3 px-2 py-1">
            <header @click="detailView = !detailView" class="w-100 h-100 d-flex justify-content-start align-items-center">
                <p class="m-0 me-1">{{ props.firstname }}</p>
                <p class="m-0">{{ props.lastname }}</p>
                <p class="m-0 ms-auto badge bg-role-badge text-black">{{ props.role }}</p>
            </header>
            <transition name="details">
                <section v-if="detailView" class="d-flex flex-wrap justify-content-start align-items-center">
                    <hr class="bg-sec w-100 m-0 mt-1" />
                    <p class="m-0">{{ props.email }}</p>
                </section>
            </transition>
        </div>
    </li>
</template>

<script setup>
import { defineProps, defineEmits, ref } from 'vue';
import { useStore } from 'vuex';

const store = useStore();

// DISPLAYED VALUES ARE PROVIDED FROM <TheTrainerOverview>
let props = defineProps([
    "email",
    "firstname",
    "lastname",
    "role",
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
        resetEditTrainer();
    }
}

// TELLS PARENT TO INVOKE AN EDIT ACTION
function deleteItem() {
    resetEditTrainer();
    emits("delete-item");
}
// TELLS PARENT TO INVOKE A DELETE ACTION
function editItem() {
    emits("edit-item");
}
// RESETS POSSIBLE PREPARED DATA FOR EDITING
function resetEditTrainer() {
    store.commit["trainers/resetTrainerDataForEdit"];
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
.delete-option.is_outside {
    left: 450%;
    opacity: 1;
    pointer-events: auto;
}
.edit-option.is_outside {
    opacity: 1;
    pointer-events: auto;
    left: 200%;
}
.option-buttons {
    transition: left .3s ease, opacity .3s ease;
    top: 0;
    left: 0;
    z-index: 10;
    height: 100%;
    opacity: 0;
    pointer-events: none;
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

