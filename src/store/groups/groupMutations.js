

export default {
    // SETS THE VALUES FOR Edit-Group ROUTE TO REMEMBER WHAT TO EDIT
    setGroupDataForEdit(moduleState, data) {
        moduleState.groupname_edit = data.name;
        moduleState.groupID_edit = data.id;
    },
    // SETS THE Edit-Group VALUES BACK TO null
    resetGroupDataForEdit(moduleState) {
        moduleState.groupname_edit = null;
        moduleState.groupID_edit = null;
    }
}