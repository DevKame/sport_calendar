<template>
    <div @click="overviewClickHandler" class="trainingOverview allOverviews d-flex flex-column justify-content-start align-items-center border border-danger">

            <itf-card :dashboard-card="true">
                <template #header>
                    <h5 class="m-0">TRAININGS</h5>
                </template>

                <template #body>
                    <div class="w-100 h-100 d-flex justify-content-around align-items-center py-2 bg-prim">
                        <button class="btn-positive border border-black rounded-2 ">New Training</button>
                    </div>
                </template>
            </itf-card>
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
</style>