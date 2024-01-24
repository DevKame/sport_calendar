
<!-- CUSTOMIZED ALERT WINDOW THAT EMITS A "close-alert" EVENT TO ITS PARENT WHICH THEN CAN
HANDLE THIS ALERTS TOGGLING -->


<template>
    <div class="errorAlert rounded-2 px-1 py-1 d-flex justify-content-between align-items-center bg-create">
        <span class="alertContent d-flex justify-content-start align-items-center">
            <slot></slot>
        </span>
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
.alertContent {
    height: 100%;
    flex: 1;
    flex-wrap: wrap;
}
.errorAlert > div {
    border: 1px solid var(--create-dark);
    width: 20px;
    aspect-ratio: 1;
}
.errorAlert {
    border: 1px solid var(--create-dark);
    color: var(--create-dark);
    font-size: 10px;
    width: 100%;
    height: 100%;
}
</style>