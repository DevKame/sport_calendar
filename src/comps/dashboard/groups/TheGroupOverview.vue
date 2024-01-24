<template>
    <div @click="overviewClickHandler" class="groupOverview ps-2 allOverviews d-flex flex-column justify-content-start align-items-center border border-danger">

            <itf-card :dashboard-card="true">
                <template #header>
                    <h5 class="m-0">GROUPS</h5>
                </template>

                <template #body>
                    <div class="w-100 h-100 d-flex justify-content-around align-items-center py-2 bg-prim">
                        <button class="btn-positive border border-black rounded-2 ">New Group</button>
                    </div>
                </template>
            </itf-card>

            <ov-load v-if="loadingContent"></ov-load>
            <transition name="no-content">
                <h6 class="noContentHeadline text-center" v-if="noGroupsAvailable">There are no groups existent. Click "New Group to create one"</h6>
            </transition>
            <div v-if="!noGroupsAvailable" class="listHolder border border-success w-100">
                <transition-group tag="ul" name="content-list" class="groupList p-0" mode="out-in">
                    <group-item
                    v-for="group in groupArray"
                    :key="group.id"
                    :name="group.name"></group-item>
                </transition-group>
            </div>
    </div>
</template>

<script setup>
import { defineEmits, onMounted, ref } from 'vue';
import { useStore } from 'vuex';

import GroupItem from "./GroupItem.vue";
const store = useStore();

const noGroupsAvailable = ref(false);
const loadingContent = ref(false);

const groupArray = ref([]);

let emits = defineEmits([
    "empty-click",
]);
function overviewClickHandler() {
    emits("empty-click");
}

onMounted(async () => {
    loadingContent.value = true;
    const groupdata = await store.dispatch("groups/getAllGroups");
    loadingContent.value = false;
    if(groupdata.groups.length === 0)
    {
        noGroupsAvailable.value = true;
    }
    else {
        groupArray.value = [...groupdata.groups];
    }
});
</script>

<style scoped>
.groupList {
    list-style-type: none;
}
.noContentHeadline {
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