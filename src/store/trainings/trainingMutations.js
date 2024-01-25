

export default {
    // SETS THE VALUES FOR Edit-Student ROUTE TO REMEMBER WHAT TO EDIT
    prepareTrainerForEdit(moduleState, data) {
        moduleState.traineremail_edit = data.email;
        moduleState.trainerID_edit = data.id;
        moduleState.trainerfirstname_edit = data.firstname;
        moduleState.trainerlastname_edit = data.lastname;
        moduleState.trainerrole_edit = data.role;
    },
    // SETS THE Edit-Student VALUES BACK TO null
    //TODO: WATCH THIS METHOD IN STUDENTS:
    resetGroupDataForEdit(moduleState) {
        moduleState.studentemail_edit = null;
        moduleState.studentID_edit = null;
    }
}