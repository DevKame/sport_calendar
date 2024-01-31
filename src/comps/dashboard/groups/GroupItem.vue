<template>
    <li class="groupItem my-2 d-flex justify-content-start align-items-center">
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

        <div class="actualListItem ms-3 ps-2 py-1">
            {{ props.name }}
        </div>
    </li>
</template>

<script setup>
import { defineProps, defineEmits, ref } from 'vue';
import { useStore } from 'vuex';

const store = useStore();

let emits = defineEmits([
    "delete-item",
    "edit-item",
]);
// VALUE TO BE DISPLAYED
let props = defineProps([
    "name",
]);

// INDICATES IF THE EDIT MENU IS ON OR OFF
const menuOn = ref(false);
// TOGGLES THE VISIBILITY OF THE EDIT MENU
function toggleMenu() {
    menuOn.value = !menuOn.value;
    if(!menuOn.value) {
        resetEditGroup();
    }
}
// TELLS PARENT TO INVOKE AN EDIT ACTION
function deleteItem() {
    resetEditGroup();
    emits("delete-item");
}
// TELLS PARENT TO INVOKE A DELETE ACTION
function editItem() {
    emits("edit-item");
}
// RESETS POSSIBLE PREPARED DATA FOR EDITING
function resetEditGroup() {
    store.commit["groups/resetGroupDataForEdit"];
}
</script>


<style scoped>
.groupItem {
    font-family: "Raleway Reg 400";
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
</style>

