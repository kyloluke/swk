<template>
    <div id="C004-01" class="container-fluid p-3 h-100 w-100" style="margin-top:20pt;">
        <c004-01-01_information_board title="インフォメーション"></c004-01-01_information_board>
        <c004-01-02_information_board v-if="false" title="勤怠情報" :employee_id="employee_id" :session_data="session_data" @themselves="requiredApp"></c004-01-02_information_board>
        <c003-01_record_time_clock v-if="session_data.is_multitennant"></c003-01_record_time_clock>
        <c003_01_web_punch v-if="session_data.is_multitennant" :employee_id="employee_id" :session_data="session_data"></c003_01_web_punch>
    </div>
</template>

<script>
export default {
    props: {
        employee_id: Number, //親からもらった社員番号 Stringで来る
        session_data: Object, //Sessionから取得した社員情報・マスタ情報
    },
    data() {
        return {
            employeeID: 0, //ここでの値保持＆子へ渡す用
        };
    },
    methods:{
        requiredApp() {
            this.$emit('themselves'); //本人勤怠入力画面を当月に更新
        },
    },
    computed:{
    },
    watch: {
    }
}
</script>