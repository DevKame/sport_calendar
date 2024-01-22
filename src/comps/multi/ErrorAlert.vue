
<!-- CUSTOMIZED ALERT WINDOW THAT EMITS A "close-alert" EVENT TO ITS PARENT WHICH THEN CAN
HANDLE THIS ALERTS TOGGLING -->


<template>
    <div class="errorAlert rounded-2 px-1 py-1 d-flex justify-content-between align-items-center bg-delete">
        <slot></slot>
        <div @click="sendCloseEmit" class="d-flex justify-content-center align-items-center rounded-circle">
            <fa-icon icon="fa-solid fa-close" size="xl"></fa-icon>
        </div>
    </div>
</template>

<script setup>
import { defineEmits } from 'vue';
let emits = defineEmits([
    "close-alert",
]);
function sendCloseEmit() {
    emits("close-alert");
}
</script>


<style scoped>
.errorAlert > div {
    border: 1px solid var(--delete-dark);
    width: 20px;
    aspect-ratio: 1;
}
.errorAlert {
    border: 1px solid var(--delete-dark);
    color: var(--delete-dark);
    font-size: 10px;
    width: 100%;
    height: 100%;
}
</style>