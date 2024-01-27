

export default {
    // SETS THE VALUES FOR Edit-Student ROUTE TO REMEMBER WHAT TO EDIT
    prepareStudentForEdit(moduleState, data) {
        moduleState.studentemail_edit = data.email;
        moduleState.studentID_edit = data.id;
        moduleState.studentfirstname_edit = data.firstname;
        moduleState.studentlastname_edit = data.lastname;
        moduleState.studentgroups_edit = data.groups;
    },
    // SETS THE Edit-Student VALUES BACK TO null
    resetStudentDataForEdit(moduleState) {
        moduleState.studentemail_edit = null;
        moduleState.studentID_edit = null;
        moduleState.studentfirstname_edit = null;
        moduleState.studentlastname_edit = null;
        moduleState.studentgroups_edit = null;
    }
}