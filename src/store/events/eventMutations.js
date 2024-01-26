

export default {
    // SETS THE VALUES FOR Edit-Event ROUTE TO REMEMBER WHAT TO EDIT
    prepareEventForEdit(moduleState, data) {
        moduleState.studentemail_edit = data.email;
        moduleState.studentID_edit = data.id;
        moduleState.studentfirstname_edit = data.firstname;
        moduleState.studentlastname_edit = data.lastname;
        moduleState.studentgroups_edit = data.groups;
    },
    // SETS THE Edit-Event VALUES BACK TO null
    resetEventDataForEdit(moduleState) {
        moduleState.studentemail_edit = null;
        moduleState.studentID_edit = null;
    }
}