<template>
    <li class="eventItem my-2 ps-2 d-flex justify-content-around align-items-center">

        <div @click="toggleMenu" class="toggleOptions position-relative rounded-circle d-flex justify-content-center align-items-center">
            <fa-icon icon="fa-solid fa-gear"></fa-icon>

                <button @click="editItem" :class="{is_outside: menuOn}" class="position-absolute edit-option btn-edit option-buttons border border-black d-flex justify-content-start align-items-center rounded-pill">
                    <fa-icon icon="fa-solid fa-pencil" class="me-2"></fa-icon>
                    EDIT
                </button>

                <button @click="deleteItem" :class="{is_outside: menuOn}" class="position-absolute delete-option btn-delete option-buttons border border-black d-flex justify-content-start align-items-center rounded-pill">
                    <fa-icon icon="fa-solid fa-ban" class="me-2"></fa-icon>
                    DELETE
                </button>
                
        </div>

        <div class="actualListItem ms-3 ps-2 pe-1 py-1">

            <header @click="detailView = !detailView" class="w-100 h-100 d-flex flex-column flex-xl-row justify-content-start align-items-center">

                <div class="headerFirstWrapper d-flex justify-content-start align-items-center">
                    <p class="m-0 me-1 fw-bold pe-none">{{ props.event.name }}</p>
                    <p v-if="props.event.old === 1" class="m-0 ms-auto badge bg-role-badge text-black">{{ indicatorForOld }}</p>
                </div>

                <div class="headerSecondWrapper mt-1 pe-xl-3 d-flex justify-content-start align-items-center">
                    <p class="m-0 ms-xl-2 pe-none">{{ props.event.fulldate }}</p>
                    <p class="m-0 ms-4 pe-none">{{ props.event.fulltime }}</p>
                    <p @click.stop="fastTrainerSignup" class="m-0 ms-auto badge bg-trainer-badge text-black" :class="{you_badge: trainerIsYou, no_trainer: trainerIsNone}">{{ trainer }}</p>
                </div>

            </header>

            <transition name="details">
                <section v-if="detailView" class="d-flex flex-wrap justify-content-start align-items-center">
                    <hr class="bg-sec w-100 m-0 mt-1" />
                    <div v-if="groups.length > 0" class="w-100 d-flex flex-wrap justify-content-start align-items-start">
                        <p class="fw-bold w-100 m-0">Groups:</p>
                        <span v-for="group in groups" :key="group" class="badge text-black bg-neutral my-1 mx-1">
                            {{ group }}
                        </span>
                    </div>
                    <div class="w-100 d-flex flex-wrap justify-content-start align-items-start">
                        <p class="fw-bold w-100 m-0">Info:</p>
                        <p>{{ infoContent }}</p>
                    </div>
                </section>
            </transition>
        </div>
    </li>
</template>

<script setup>
import { defineProps, defineEmits, ref, computed } from 'vue';
import { useStore } from 'vuex';

const store = useStore();

// DISPLAYED VALUES ARE PROVIDED FROM <TheEventOverview>
let props = defineProps([
    "event",
    "groups",
    "trainer",
]);
let emits = defineEmits([
    "delete-item",
    "edit-item",
    "sign-trainer",
    "trainer-signup-fast",
]);
// INDICATES IF THE EDIT MENU IS ON OR OFF
const menuOn = ref(false);
// TOGGLES THE VISIBILITY OF THE EDIT MENU
function toggleMenu() {
    menuOn.value = !menuOn.value;
    if(!menuOn.value) {
        resetEditEvent();
    }
}
// DEPENDENT ON WHICH TRAINER IS APPLIED, THIS BADGE GETS ANOTHER CLASS
// AND WITH THAT OTHER COLORS
const trainerIsYou = computed(() => {
    return props.trainer === "You!";
});
const trainerIsNone = computed(() => {
    return props.trainer === "no-trainer";
});
// IF EVENT IS OLD, IT GETS A VISUAL INDICATOR FOR THAT
const indicatorForOld = computed(() => {
    return props.event.old === 1 ?
    "old" : "new";
});
// SETS THE INFO CONTENT OR A DEFAULT VALUE IF NO CONTENT IS PROVIDED
const infoContent = computed(() => {
    let result = "No information available";
    if(props.event.info.trim() !== "")
    {
        result = props.event.info;
    }
    return result;
});

// TELLS PARENT TO INVOKE AN EDIT ACTION
function deleteItem() {
    resetEditEvent();
    emits("delete-item");
}
// TELLS PARENT TO INVOKE A DELETE ACTION
function editItem() {
    emits("edit-item");
}
// RESETS POSSIBLE PREPARED DATA FOR EDITING
function resetEditEvent() {
    store.commit["events/resetEventDataForEdit"];
}
// IF TRAINER IS NONE, TELLS PARENT TO ADJUST TRAINER THAT CLICKED IT TO CORRESPONDING TRAINING
function fastTrainerSignup() {
    if(props.event.trainer === "no-trainer")
    {
        emits("sign-trainer");
    } else {
        detailView.value = !detailView.value;
    }
}

const detailView = ref(false);
</script>


<style scoped>
.headerFirstWrapper,
.headerSecondWrapper {
    width: 100%;
}
.bg-trainer-badge.no_trainer {
    border: 3px solid black;
    background-color: var(--delete);
}
.bg-trainer-badge.you_badge {
    border: 3px solid black;
    background-color: var(--sec);
}
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
.actualListItem section p {
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
.eventItem {
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

@media screen and (min-width: 1200px) {
    .headerFirstWrapper {
        width: 30%;
    }
    .headerSecondWrapper {
        width: 70%;
    }
}
</style>

