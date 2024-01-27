

export default {
    // RETURNS THE DATA OF A PREPARED EVENT
    preparedEventForEdit(moduleState) {
        return {
            id: moduleState.eventID_edit,
            name: moduleState.eventname_edit,
            day: moduleState.eventday_edit,
            month: moduleState.eventmonth_edit,
            year: moduleState.eventyear_edit,
            hour: moduleState.eventhour_edit,
            minute: moduleState.eventminute_edit,
            max: moduleState.eventmax_edit,
            trainer: moduleState.eventtrainer_edit,
            info: moduleState.eventinfo_edit,
            groups: moduleState.eventgroups_edit,
        };
        
    },
}