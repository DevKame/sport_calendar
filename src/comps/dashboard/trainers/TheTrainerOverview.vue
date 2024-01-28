<template>
    <div @click="overviewClickHandler" class="trainerOverview pt-4 ps-2 allOverviews d-flex flex-column justify-content-start align-items-center border border-danger">

            <itf-card :dashboard-card="true">
                <template #header>
                    <h5 class="m-0">TRAINERS</h5>
                </template>

                <template #body>
                    <div class="w-100 h-100 d-flex justify-content-around align-items-center py-2 bg-prim">
                        <router-link :to="{name: 'New-Trainer'}" class="px-1 text-black border border-black border-2 rounded-2 itf-buttons itf-new">
                            New Trainer
                        </router-link>
                    </div>
                </template>
            </itf-card>

            <ov-load v-if="loadingContent" class="mt-3"></ov-load>
            <transition name="no-content">
                <h6 class="noContentHeadline text-center mt-3" v-if="noTrainersAvailable">There are no trainers existent. Click "New Trainer to create one"</h6>
            </transition>
            <div v-if="!noTrainersAvailable" class="listHolder w-100">
                <transition-group tag="ul" name="content-list" class="trainerList p-0" mode="out-in">
                    <trainer-item
                    v-for="(trainer, idx) in trainerArray"
                    :key="trainer.id"
                    :firstname="trainer.firstname"
                    :lastname="trainer.lastname"
                    :email="trainer.email"
                    :role="trainer.role"
                    @delete-item="deleteTrainer(idx, trainer.id)"
                    @edit-item="editTrainer(trainer.id, trainer.email, trainer.firstname, trainer.lastname, trainer.role)"></trainer-item>
                </transition-group>
            </div>
            <teleport to="body">
                <transition name="info-box">
                    <info-box @close-infobox="accessInfoActive = false" v-if="accessInfoActive"></info-box>
                </transition>
            </teleport>
    </div>
</template>

<script setup>
import { defineEmits, onMounted, ref, computed } from 'vue';
import { useStore } from 'vuex';
import { useRouter } from 'vue-router';

import TrainerItem from "./TrainerItem.vue";

const router = useRouter();
const store = useStore();

// HANDLING CLICKS FOR DASHBOARD TO KNOW WHEN TO COLLAPSE NAV
let emits = defineEmits([
    "empty-click",
]);
function overviewClickHandler() {
    emits("empty-click");
}

// VALUES REPRESENT IF NO STUDENT IS EXISTENT AND
// IF THE CONTENT IS CURRENTLY LOADING
const noTrainersAvailable = ref(false);
const loadingContent = ref(false);

// MANAGES THE USER ROLE AND THE PRIVILEGES
const userRole = computed(() => {
    return store.getters["auth/userRole"];
});
const accessInfoActive = ref(false);

// FETCHED TRAINERS ARE SAVED WITHIN HERE
const trainerArray = ref([]);

// INITIAL FETCHING OF TRAINERS TO BE ABLE TO DISPLAY THEM
onMounted(async () => {
    loadingContent.value = true;

    const trainerdata = await store.dispatch("trainers/getAllTrainers");
    loadingContent.value = false;
    if(trainerdata.trainers.length === 0)
    {
        noTrainersAvailable.value = true;
    }
    else {
        trainerArray.value = [...trainerdata.trainers];
    }
});

/** PREPARES STATE WITH DATA OF TO-BE-EDITED TRAINER AND SWITCHES
 *  TO Edit-Trainer ROUTE TO ENABLE THE ACTUAL EDITING
 * @param {number} id   => ID OF THE TO-BE-EDITED Trainer 
 * @param {String} name => NAME OF THE TO-BE-EDITED Trainer */
function editTrainer(id, email, fn, ln, role) {
    if((userRole.value !== "ADMIN" && userRole.value !== "SENIOR-TRAINER") || id === store.getters["auth/userID"]) {
        accessInfoActive.value = true;
    }
    else {
        store.commit("trainers/prepareTrainerForEdit", {email: email, id: id, firstname: fn, lastname: ln, role});
        router.push({name: "Edit-Trainer"});
    }
}
/** DELETES THE CHOSEN TRAINER USING HIS ID AND REMOVES THE CORRESPONDING DOM
 * @param {number} index    => ON WHAT IDX IS THE LIST ELEMENT
 * @param {number} id       => ID OF THE TRAINER */
async function deleteTrainer(index, id) {
    if(userRole.value !== "ADMIN"  || id === store.getters["auth/userID"]) {
        accessInfoActive.value = true;
    }
    else
    {
        const deletereq =
        {
            task: "delete-trainer",
            id: id,
        };
        const deletedata = await store.dispatch("trainers/post", deletereq);
        if(deletedata.success) {
            trainerArray.value.splice(index, 1);
            if(trainerArray.value.length === 0)
            {
                noTrainersAvailable.value = true;
            }
            const req =
            {
                task: "update-event-trainer",
                id: id,
            };
            const updateeventdata = await store.dispatch("trainers/post", req);
            if(!updateeventdata.success)
            {
                router.push({name: "Error"});
            }
            //TODO: needs to update all events where this trainer was signed in
        }
        else {
            router.replace({name:"Error"});
        }
    }
}
</script>

<style scoped>
.itf-new {
    background-image: linear-gradient(to top, #ddd -20%, var(--create), var(--create), #ddd 110%);
}
.itf-buttons {
    box-shadow: 0 0 10px 1px #333;
}
.trainerList {
    list-style-type: none;
}
.noContentHeadline {
    width: 80%;
    font-family: "Raleway Reg 400";
}
.content-list-move {
    transition: transform .2s ease;
}
.content-list-enter-from,
.content-list-leave-to {
    opacity: 0;
    transform: translate(100%, 0);
}
.content-list-enter-active {
    transition: all .5s ease;
}
.content-list-leave-active {
    transition: all .5s ease;
    position: absolute;
}
.content-list-enter-to,
.content-list-leave-from {
    opacity: 1;
    transform: translate(0, 0);
}
.no-content-enter-from,
.no-content-leave-to {
    opacity: 0;
    transform: translate(-50%, 0);
}
.no-content-enter-active,
.no-content-leave-active {
    transition: all .3s ease;
}
.no-content-enter-to,
.no-content-leave-from {
    opacity: 1;
    transform: translate(0, 0);
}
</style>