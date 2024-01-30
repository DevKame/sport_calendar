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

        <div class="w-100 d-flex justify-content-between align-items-center">
            <button
            v-if="userRole === 'STUDENT'"
            @click="toggleParticipation(event.id)"
            :class="{canSign: signable}"
            class="partakeButton btn-positive border border-black rounded-2">{{ canSignIn }}</button>

            <div class="alertHolder ms-2 d-flex justify-content-center align- items-center border border-danger">

                <error-alert v-if="connectionError" @close-alert="connectionError = false">
                    <p class="m-0 fw-bold">Some connection problems occured. Try later</p>
                </error-alert>

                <error-alert v-else-if="lengthError" @close-alert="lengthError = false">
                    <p class="m-0 fw-bold">Some connection problems occured. Try later</p>
                </error-alert>

                <success-alert v-else-if="signupSuccess" @close-alert="signupSuccess = false">
                    <p class="m-0 fw-bold">Successfully signed in / out !!</p>
                </success-alert>

            </div>

        </div>

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

// let emits = defineEmits([
//     "signin-success",
// ])
const store = useStore();

let props = defineProps([
    "event",
    "trainers",
    "students",
]);

// INDICATES IF USER IS ALREADY PARTAKING OR NOT
const signable = computed(() => {
    return JSON.parse(props.event.students).find(curr => curr === store.getters["auth/userID"]) ?
    false : true;
})
//DEPENDING ON IF USER PARTAKES OR NOT, ASSIGNS THE PROPER BUTTON COLOR AND TEXT
const canSignIn = computed(() => {
    return JSON.parse(props.event.students).find(curr => curr === store.getters["auth/userID"]) ?
    "Sign out" : "Sign In";
});
/** RETURNS NAMES OF STUDENTS THAT PARTAKE IN THE PARTICULAR TRAINING
 * @param {JSON String} eventStudents   => ID´s OF PARTAKING STUDENTS
 * @return {Array} parseStudents        => ID, FIRST AND LASTNAME OF STUDENTS */
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
// HELPER VARIABLES FOR ERROR DISPLAYING
const connectionError = ref(false);
const lengthError = ref(false);
const signupSuccess = ref(false);
// RESETS ALL ERRORS
function resetErrors() {
    connectionError.value = false;
}
/** HANDLES THE UPDATING OF DATA WHEN USER SINGS IN/OUT
 *  AND UPDATES THE CORRESPONDING FRONTEND
 * @param {number} eid                      => EVENT ID */
async function toggleParticipation(eid) {
    resetErrors();
    const req =
    {
        eid: eid,
        sid: store.getters["auth/userID"],
    };
    req.task = signable.value === true ?
    "signin-student" : "signout-student";
    console.log(req);
    let signingdata = await store.dispatch("events/post", req);
    if(!signingdata.success)
    {
        switch(signingdata.reason) {
            case "connection-problems":
                connectionError.value = true;
                break;
            case "too-long":
                lengthError.value = true;
                break;
        }
    } else {
        signupSuccess.value = true;
    }
    console.table(signingdata);
}

// DETERMINES IF PARTAKING STUDENTS ARE SHOWN OR NOT
const showStudents = ref(false);
function toggleStudentVisibility() {
    showStudents.value = !showStudents.value;
}
// RETURNS THE RELEVANT DATA TO DISPLAY OUT OF THE PROVIDED EVENT PROP
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
/** INDICATES IF AT LEAST ONE STUDENT IS PARTAKING
 *  @return {Bool} */
const atLeastOneStudent = computed(() => {
    return props.event.booked > 0;
});

/** RETURNS USER ROLE
 *  @return {String} => STUDENT, TRAINER, SENIO-TRAINER OR ADMIN */
const userRole = computed(() => {
    return store.getters.getLoggedUserRole;
});


</script>

<style scoped>
.alertHolder {
    flex: 1;
}
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