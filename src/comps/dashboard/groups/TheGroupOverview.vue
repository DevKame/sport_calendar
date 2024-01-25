<template>
    <div @click="overviewClickHandler" class="groupOverview overflow-x-hidden ps-2 allOverviews d-flex flex-column justify-content-start align-items-center border border-danger">

            <itf-card :dashboard-card="true">
                <template #header>
                    <h5 class="m-0">GROUPS</h5>
                </template>

                <template #body>
                    <div class="w-100 h-100 d-flex justify-content-around align-items-center py-2 bg-prim">
                        <router-link :to="{name: 'New-Group'}" class="px-1 btn-positive border border-black rounded-2 ">
                            New Group
                        </router-link>
                    </div>
                </template>
            </itf-card>

            <ov-load v-if="loadingContent" class="mt-3"></ov-load>
            <transition name="no-content">
                <h6 class="noContentHeadline text-center mt-3" v-if="noGroupsAvailable">There are no groups existent. Click "New Group to create one"</h6>
            </transition>
            <div v-if="!noGroupsAvailable" class="listHolder w-100">
                <transition-group tag="ul" name="content-list" class="groupList p-0" mode="out-in">
                    <group-item
                    v-for="(group, idx) in groupArray"
                    :key="group.id"
                    :name="group.name"
                    @delete-item="deleteGroup(idx, group.id)"
                    @edit-item="editGroup(group.id, group.name)"></group-item>
                </transition-group>
            </div>
    </div>
</template>

<script setup>
import { defineEmits, onMounted, ref } from 'vue';
import { useStore } from 'vuex';
import { useRouter } from 'vue-router';

import GroupItem from "./GroupItem.vue";

const router = useRouter();
const store = useStore();

// HANDLING CLICKS FOR DASHBOARD TO KNOW WHEN TO COLLAPSE NAV
let emits = defineEmits([
    "empty-click",
]);
function overviewClickHandler() {
    emits("empty-click");
}

// VALUES REPRESENT IF NO GROUP IS EXISTENT AND
// IF THE CONTENT IS CURRENTLY LOADING
const noGroupsAvailable = ref(false);
const loadingContent = ref(false);

// FETCHED GROUPS ARE SAVED WITHIN HERE
const groupArray = ref([]);

// INITIAL FETCHING OF GROUPS TO BE ABLE TO DISPLAY THEM
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
/** PREPARES STATE WITH NAME AND ID OF TO-BE-EDITED GROUP AND SWITCHES
 *  TO Edit-Group ROUTE TO ENABLE THE ACTUAL EDITING
 * @param {number} id   => ID OF THE TO-BE-EDITED GROUP 
 * @param {String} name => NAME OF THE TO-BE-EDITED GROUP */
function editGroup(id, name) {
    store.commit("groups/setGroupDataForEdit", {name: name, id: id});
    router.push({name: "Edit-Group"});
}
//TODO: When deleting group, update all users groups
/** INVOKES THE ACTION TO DELETE A GROUP. USES INDEX AFTER
 *  SUCCESS TO DELETE THE GROUP OUT OF groupArray
 * @param {number} index    => INDEX OF THIS GROUP WITHIN groupArray 
 * @param {number} id       => ID OF THE TO-BE-DELETED GROUP */
async function deleteGroup(index, id) {
    const deletereq =
    {
        task: "delete-group",
        id: id,
    };
    const deletedata = await store.dispatch("groups/post", deletereq);
    if(deletedata.success) {
        groupArray.value.splice(index, 1);
        if(groupArray.value.length === 0)
        {
            noGroupsAvailable.value = true;
        }
    }
    else {
        router.replace({name:"Error"});
    }
}
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