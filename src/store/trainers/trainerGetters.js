

export default {
    // RETURNS THE DATA OF A PREPARED STUDENT
    preparedStudentForEdit(moduleState) {
        return {
            id: moduleState.studentID_edit,
            email: moduleState.studentemail_edit,
            firstname: moduleState.studentfirstname_edit,
            lastname: moduleState.studentlastname_edit,
            groups: moduleState.studentgroups_edit,
        };
        
    },
}