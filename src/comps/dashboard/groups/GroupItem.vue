<template>
    <li class="groupItem my-2 d-flex justify-content-start align-items-center">
        <div @click="menuOn = !menuOn" class="toggleOptions position-relative rounded-circle d-flex justify-content-center align-items-center">
            <fa-icon icon="fa-solid fa-gear"></fa-icon>

                <button v-if="menuOn" class="position-absolute edit-option btn-edit border border-black d-flex justify-content-start align-items-center rounded-pill">
                    <fa-icon icon="fa-solid fa-pencil" class="me-2"></fa-icon>
                    EDIT
                </button>

                <button v-if="menuOn" class="position-absolute delete-option btn-delete border border-black d-flex justify-content-start align-items-center rounded-pill">
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
import { defineProps, ref } from 'vue';

let props = defineProps([
    "name",
]);

const menuOn = ref(false);
</script>


<style scoped>
.delete-option {
    left: 400%;
}
.edit-option {
    left: 150%;
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
.edit-enter-from {
    opacity: 0;
}
.edit-enter-active {
    transition: all .4s ease;
}
.edit-enter-to {
    opacity: 0;
}
</style>

