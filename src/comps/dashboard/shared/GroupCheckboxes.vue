
<!-- CHECKBOX FOR CHOOSING GROUPS FOR
    - STUDENTS
    - TRAININGS
    - EVENTS

EMITS "box-clicked" TO ITS PARENT TO INVOKE ACTIONS REGARDING THIS CHECKBOXES VALUE -->
<template>
    <div @click="clickBox" class="cbHolder my-1 px-2 position-relative d-flex justify-content-between align-items-center">
        <input @input="emitChange" ref="cbox" type="checkbox" :name="props.id" :id="props.id" class="position-absolute rounded-pill">
        <label :for="props.id" class="position-relative">{{ name }}</label>
    </div>
</template>

<script setup>
import { defineProps, defineEmits, ref, onMounted } from 'vue';
let props = defineProps([
    "id",
    "name",
    "checked",
]);
let emits = defineEmits([
    "box-clicked",
]);

onMounted(() => {
    if(props.checked)
    {
        cbox.value.click();
    }
})

const cbox = ref();

function clickBox(e) {
    if(e.target.classList.contains("cbHolder"))
    {
        cbox.value.click();
    }
}
function emitChange() {
    emits("box-clicked");
}
</script>

<style scoped>
input[type="checkbox"] {
    appearance: none;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    transition: all .3s ease;
}
input[type="checkbox"] + label {
    transition: all .3s ease;
}
input[type="checkbox"]:not(:checked) {
    background-color: var(--neutral-weak);
}
input[type="checkbox"]:checked {
    background-color: var(--neutral);
}
input[type="checkbox"]:checked + label {
    font-weight: bold;
}
input[type="checkbox"]:not(:checked) + label::selection {
    background-color: transparent;
    color: black;
}
input[type="checkbox"]:checked + label::selection {
    background-color: transparent;
    color: black;
}
label {
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
}
.cbHolder {
    min-height: 15px;
    font-size: 12px;
}
</style>