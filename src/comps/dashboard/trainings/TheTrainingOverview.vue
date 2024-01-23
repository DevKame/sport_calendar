<template>
    <div @click="overviewClickHandler" class="trainingOverview allOverviews d-flex flex-column justify-content-start align-items-center border border-danger">

        <transition appear>
            <itf-card id="dashboard-itf-card">
                <template #header>
                    <h5 class="m-0">TRAININGS</h5>
                </template>

                <template #body>
                    <div class="w-100 h-100 d-flex justify-content-around align-items-center py-2 bg-prim">
                        <button class="btn-positive border border-black rounded-2 ">New Training</button>
                    </div>
                </template>
            </itf-card>
        </transition>
    </div>
</template>

<script setup>
import { defineEmits } from 'vue';
let emits = defineEmits([
    "empty-click",
]);
function overviewClickHandler() {
    emits("empty-click");
}
</script>

<style scoped>
.v-enter-from {
    opacity: 0;
}
.v-enter-active {
    transition: opacity .3s linear 1s;
}
.v-enter-to {
    opacity: 1;
}
.v-leave-from {
    opacity: 1;
}
.v-leave-active {
    transition: opacity .1s linear;
}
.v-leave-to {
    opacity: 0;
}
</style>