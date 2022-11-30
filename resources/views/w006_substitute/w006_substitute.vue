<template>
    <div id="C006-01" class="p-3">
        <ul class="nav nav-tabs mb-3" id="pills-tab" role="tablist">
            <li v-if="isProxyAttendance" class="nav-item" role="presentation">
                <a class="nav-link-tab active" style="font-size:12pt;" id="C006-01-01" data-toggle="tab" href="#C014" role="tab" aria-controls="C014" aria-selected="true" v-on:click="onClickInputAttendance">勤怠入力</a>
            </li>
            <li v-if="isProxyTarget" class="nav-item" role="presentation">
                <a class="nav-link-tab" style="font-size:12pt;" id="C006-01-02" data-toggle="tab" href="#C015" role="tab" aria-controls="C015" aria-selected="false">入力対象者設定</a>
            </li>
        </ul>
        <div class="tab-content" id="pills-tabContent">
            <div v-if="isProxyAttendance" class="tab-pane fade show active pagectl" id="C014" role="tabpanel" aria-labelledby="C006-01-01">
                <w014_input_attendance :employee_id="employee_id" :session_data="session_data" ref='w014_input_attendance'></w014_input_attendance>
            </div>
            <div v-if="isProxyTarget" class="tab-pane fade" id="C015" role="tabpanel" aria-labelledby="C006-01-02">
                <w015_setting_target  :employee_id="employee_id" :session_data="session_data"></w015_setting_target>
            </div>
        </div>
    </div>

</template>
<script>
export default {
    props: {
        employee_id: Number,
        session_data: Object, //Sessionから取得した社員情報・マスタ情報
    },
    data() {
        return {
        };
    },
    mounted(){
    },
    methods:{
        //代理入力共通初期化（代理入力メニュークリックしたら呼ばれる）
        initialize(){
            this.$refs['w014_input_attendance'].initialize();
        },
        //勤怠入力メニュークリックしたら初期化
        onClickInputAttendance(){
            this.$refs['w014_input_attendance'].initialize();
        }
    },
    computed:{
        isProxyAttendance:function(){
            return !!Number(this.session_data.authority_pattern.proxy_attendance_input);
        },
        isProxyTarget: function(){
            return !!Number(this.session_data.authority_pattern.proxy_input_target_setting);
        },
    },
    watch: {
    }
}
</script>