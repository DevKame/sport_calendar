

export default {
    // SETS THE VALUES FOR Edit-Event ROUTE TO REMEMBER WHAT TO EDIT
    prepareEventForEdit(moduleState, data) {
        moduleState.eventID_edit = data.event.id;
        moduleState.eventname_edit = data.event.name;
        moduleState.eventday_edit = data.event.day;
        moduleState.eventmonth_edit = data.event.month;
        moduleState.eventyear_edit = data.event.year;
        moduleState.eventhour_edit = data.event.hour;
        moduleState.eventminute_edit = data.event.minute;
        moduleState.eventmax_edit = data.event.max;
        moduleState.eventtrainer_edit = data.event.trainer;
        moduleState.eventinfo_edit = data.event.info;
        moduleState.eventgroups_edit = data.groups;
    },
    // SETS THE Edit-Event VALUES BACK TO null
    resetEventDataForEdit(moduleState) {
        moduleState.eventID_edit = null;
        moduleState.eventname_edit = null;
        moduleState.eventday_edit = null;
        moduleState.eventmonth_edit = null;
        moduleState.eventyear_edit = null;
        moduleState.eventhour_edit = null;
        moduleState.eventminute_edit = null;
        moduleState.eventmax_edit = null;
        moduleState.eventtrainer_edit = null;
        moduleState.eventinfo_edit = null;
        moduleState.eventgroups_edit = null;
    }
}