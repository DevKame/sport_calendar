

export default {
    // RETURNS THE DATA OF A PREPARED TRAINING
    preparedTrainingForEdit(moduleState) {
        return {
            id: moduleState.trainingID_edit,
            name: moduleState.trainingname_edit,
            groups: moduleState.traininggroups_edit,
        };
        
    },
}