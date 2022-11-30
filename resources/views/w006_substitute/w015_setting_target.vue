<template>
    <div>
        <settingTargetBoards :employee_id="employee_id" :setting_target_type="setting_target_type"></settingTargetBoards>
    </div>
</template>
<script>
export default {
    props: {
        employee_id: Number, //親からもらった社員番号 Numberで来る
        session_data: Object,
    },
    data() {
        return {
            setting_target_type: 2
        };
    },
}
</script>