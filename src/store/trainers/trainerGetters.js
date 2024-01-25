

export default {
    // RETURNS THE DATA OF A PREPARED STUDENT
    preparedTrainerForEdit(moduleState) {
        return {
            email: moduleState.traineremail_edit,
            id: moduleState.trainerID_edit,
            firstname: moduleState.trainerfirstname_edit,
            lastname: moduleState.trainerlastname_edit,
            role: moduleState.trainerrole_edit,
        };
        
    },
}