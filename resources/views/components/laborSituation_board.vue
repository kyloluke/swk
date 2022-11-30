<template>
<div>
    <c012_01_03_board :employee_id="employee_id" :year_month="year_month" :session_data="session_data"></c012_01_03_board>
    <c012_01_04_board :employee_id="employee_id" :year_month="year_month" :session_data="session_data"></c012_01_04_board>
    <c012_01_05_board :employee_id="employee_id" :year_month="year_month" :session_data="session_data"></c012_01_05_board>
    <c012_01_06_board :employee_id="employee_id" :year_month="year_month"></c012_01_06_board>
</div>
</template>
<script>

export default {
    name: "laborSituation_board",
    props:{
        employee_id: Number,
        year_month: Number,
        session_data: Object,
    },
    data() {
        return {
            employeeID: 0,
            yearMonth: 0,
        };
    },
    methods: {
    },
    watch: {
    }
}

</script>