<template>
    <div @click="emitClosing" class="infoBox position-fixed d-flex justify-content-center align-items-center">
        <itf-card>

            <template #header>
                <div class="h-100 w-100 d-flex justify-content-between align-items-center">
                    <h5 class="m-0">Access denied</h5>
                    <div class="d-flex justify-content-center align-items-center border border-black rounded-circle">
                        <fa-icon icon="fa-solid fa-close"></fa-icon>
                    </div>
                </div>
            </template>

            <template #body>
                <div class="w-100 h-100 px-2 pt-2 bg-prim d-flex flex-column justify-content-start align-items-start">
                    <p>Sorry, either you try to edit/delete your own account or based on your role you dont have access to this function.</p>
                    <p>If your role is:</p>
                    <p class="fw-bold">TRAINER:</p>
                    <p>You can only create groups, students, etc aswell as editing the information of
                        existing events or signing up to a training within the calendar
                    </p>
                    <p class="fw-bold">SENIOR-TRAINER:</p>
                    <p>All from the above but you can now also edit everything (groups, students, etc)
                    </p>
                </div>
            </template>


        </itf-card>
    </div>
</template>

<script setup>
import { defineEmits } from 'vue';
let emits = defineEmits([
    "close-infobox",
]);

function emitClosing() {
    emits("close-infobox");
}
</script>


<style scoped>
h5 {
    font-family: "Raleway SBold 600";
}
h5 + div {
    min-width: 30px;
    aspect-ratio: 1;
}
.infoBox > div {
    width: clamp(300px, 30vw, 500px);
}
.infoBox {
    top: 0;
    left: 0;
    width: 100%;
    height: 100vh;
    background-color: rgba(0,0,0,.7);
    font-family: "Raleway Reg 400";
}
</style>