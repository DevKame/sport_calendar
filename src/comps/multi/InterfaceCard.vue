
<!-- A WIDGET WITH A LIGHT BLUE HEADER AND CONTENT BODY MEANT PRIMARILY TO DISPLAY
UI ELEMENTS FOR THE USER TO INTERACT

SLOT NAME="HEADER":
- CONTAINS MOSTLY JUST A <h5> ELEMENT AS A TITLE BUT FEW WIDGETS WILL ALSO HAVE
  A CLOSING BUTTON

SLOT NAME="BODY":
- CAN CONTAINS ALL SORTS OF CONTENTS LIKE BUTTONS, INPUTS, ETC. -->


<template>
    <div :class="{dashboard_card: props.dashboardCard}" class="itfCard overflow-hidden d-flex flex-column justify-content-start align-items-center rounded-3">
        <header class="p-2 itfHeader bg-sec">
            <slot name="header"></slot>
        </header>

        
        <slot name="body"></slot>
    </div>
</template>

<script setup>
import { defineProps } from "vue";
let props = defineProps([
    "dashboardCard",
]);
</script>

<!-- SLOT HEADER´S HEADLINE IS ALWAYS A <h4> ELEMENT !! -->
<style scoped>
.itfCard {
    box-shadow: 0 0 10px 2px #777;
    z-index: 15;
}
.itfHeader {
    width: 100%;
    box-shadow: 0 2px 10px 1px black;
    font-family: "Raleway SBold 600";
}
</style>