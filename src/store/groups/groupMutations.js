

export default {
    setGroupDataForEdit(moduleState, data) {
        moduleState.groupname_edit = data.name;
        moduleState.groupID_edit = data.id;
    },
    resetGroupDataForEdit(moduleState) {
        moduleState.groupname_edit = null;
        moduleState.groupID_edit = null;
    }
}