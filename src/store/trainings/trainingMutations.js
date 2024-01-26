

export default {
    // SETS THE VALUES FOR Edit-Training ROUTE TO REMEMBER WHAT TO EDIT
    prepareTrainingForEdit(moduleState, data) {
        moduleState.trainingID_edit = data.id;
        moduleState.trainingname_edit = data.name;
        moduleState.traininggroups_edit = data.groups;
    },
    // SETS THE Edit-Training VALUES BACK TO null
    resetTrainingDataForEdit(moduleState) {
        moduleState.trainingID_edit = null;
        moduleState.trainingname_edit = null;
        moduleState.traininggroups_edit = null;
    }
}