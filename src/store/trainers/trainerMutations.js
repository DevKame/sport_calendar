

export default {
    // SETS THE VALUES FOR Edit-Student ROUTE TO REMEMBER WHAT TO EDIT
    prepareTrainerForEdit(moduleState, data) {
        moduleState.traineremail_edit = data.email;
        moduleState.trainerID_edit = data.id;
        moduleState.trainerfirstname_edit = data.firstname;
        moduleState.trainerlastname_edit = data.lastname;
        moduleState.trainerrole_edit = data.role;
    },
    // SETS THE Edit-Trainer VALUES BACK TO null
    //TODO: WATCH THIS METHOD IN TRAINERS:
    resetTrainerDataForEdit(moduleState) {
        moduleState.traineremail_edit = null;
        moduleState.trainerID_edit = null;
        moduleState.trainerfirstname_edit = null;
        moduleState.trainerlastname_edit = null;
        moduleState.trainerrole_edit = null;
    }
}