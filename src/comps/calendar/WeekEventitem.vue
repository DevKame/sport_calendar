<template>
    <div class="weekeventitem w-100 bg-trainer-badge rounded-2 overflow-hidden p-1 my-2 d-flex flex-column justify-content-start align-items-start border border-black">
                
        <div class="w-100 d-flex justify-content-between align-items-center">
            <span class="fw-bold">{{ name }} <span v-if="event.old === 1" class="badge text-black bg-delete border border-black">old</span></span>
            <span :class="{is_free: freeSpaces}" class="totalIndicator fw-bold">{{ booked }} / {{ max }}</span>
        </div>

        <div class="w-100 mt-2 d-flex justify-content-between align-items-center">
            <p class="m-0">{{ hour }}:{{ minute }}</p>
            <p class="m-0">Trainer: <span class="badge text-black bg-role-badge border border-dark">{{ trainer }}</span></p>
        </div>

        <button
        v-if="userRole === 'STUDENT'"
        :class="{canSign: signable}"
        class="partakeButton btn-positive border border-black rounded-2">{{ canSignIn }}</button>

        <p v-if="infoAvailable" class="fst-italic">{{ info }}</p>
        <h6 v-if="atLeastOneStudent" @click="toggleStudentVisibility" class="fw-bold border border-black rounded-2 w-100 p-1 mt-2 mb-1">Partaking students:</h6>
        <div v-if="atLeastOneStudent && showStudents"  class="w-100 d-flex flex-column justify-content-start align-items-start">
            <p class="m-0 my-1" v-for="st in getPartakingStudents(event.students)" :key="st.id">{{ st.firstname }}</p>
        </div>

    </div>
</template>

<script setup>
import { defineProps, computed, ref } from 'vue';
import { useStore } from 'vuex';

const store = useStore();

let props = defineProps([
    "event",
    "trainers",
    "students",
]);

const signable = computed(() => {
    return JSON.parse(props.event.students).find(curr => curr === store.getters["auth/userID"]) ?
    false : true;
})
const canSignIn = computed(() => {
    return JSON.parse(props.event.students).find(curr => curr === store.getters["auth/userID"]) ?
    "Sign out" : "Sign In";
});

function getPartakingStudents(eventStudents) {
    // console.clear();
    let allStudents = props.students;
    let partakeStudents = JSON.parse(eventStudents);
    let parseStudents = [];
    for(let id of partakeStudents) {
        let student = allStudents.find(curr => curr.id === id);
        if(student) {
            parseStudents.push(student);
        }
    }
    return parseStudents;
}

const showStudents = ref(false);
function toggleStudentVisibility() {
    showStudents.value = !showStudents.value;
}
const name = computed(() => {
    return props.event.name;
});
const booked = computed(() => {
    return props.event.booked;
});
const freeSpaces = computed(() => {
    return props.event.booked < props.event.max;
});
const max = computed(() => {
    return props.event.max;
});
const hour = computed(() => {
    return props.event.hour;
});
const minute = computed(() => {
    return props.event.minute;
});
const trainer = computed(() => {
    let result = "no trainer";
    if(props.event.trainer !== "no-trainer")
    {
        const trainer = props.trainers.find(curr => curr.id === +props.event.trainer);
        result = trainer.firstname;
    }
    return result;
});
const infoAvailable = computed(() => {
    return props.event.info.trim() !== "";
})
const info = computed(() => {
    return props.event.info;
});

const atLeastOneStudent = computed(() => {
    return props.event.booked > 0;
});


const userRole = computed(() => {
    return store.getters.getLoggedUserRole;
});


</script>

<style scoped>
.partakeButton.canSign {
    background-image: linear-gradient(to top, #ddd -20%, var(--create), var(--create), #ddd 110%);
}
.partakeButton {
    background-image: linear-gradient(to top, #ddd -20%, var(--delete), var(--delete), #ddd 110%);
    box-shadow: 0 0 3px 1px #333;
}
::selection {
    all: unset;
}
.totalIndicator {
    color: firebrick;
}
.totalIndicator.is_free {
    color: var(--create);
}
.weekeventitem {
    box-shadow: 0 0 5px 1px #333 inset;
    font-family: "Raleway Reg 400";
}
</style>