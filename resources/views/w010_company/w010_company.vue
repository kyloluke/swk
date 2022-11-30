<template>
    <div id="C010-01" class="p-3">
        <ul class="nav nav-tabs mb-3" id="pills-tab" role="tablist">
            <li v-if="isGeneralAttendance" class="nav-item" role="presentation">
                <a class="nav-link-tab active" style="font-size:12pt;" id="C010-01-01" data-toggle="tab" href="#C025" role="tab" aria-controls="C025" aria-selected="true" v-on:click="onClickInputAttendance">勤怠入力</a>
            </li>
            <li v-if="isGeneralWork" class="nav-item" role="presentation">
                <a class="nav-link-tab" style="font-size:12pt;" id="C010-01-02" data-toggle="tab" href="#C026" role="tab" aria-controls="C026" aria-selected="false" v-on:click="onClickLaborSituation">労働・休暇状態管理</a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link-tab" style="font-size:12pt;" id="C010-01-03" data-toggle="tab" href="#C027" role="tab" aria-controls="C027" aria-selected="false" v-on:click="onClickDailyReport">日報</a>
            </li>
            <li v-if="isGeneralClosing" class="nav-item" role="presentation"> 
                <a class="nav-link-tab" style="font-size:12pt;" id="C010-01-04" data-toggle="tab" href="#C028" role="tab" aria-controls="C028" aria-selected="false" v-on:click="onClickCloseAttendance">締め処理</a>
            </li>
            <li v-if="isGeneralSearch" class="nav-item" role="presentation"> 
                <a class="nav-link-tab" style="font-size:12pt;" id="C010-01-05" data-toggle="tab" href="#C029" role="tab" aria-controls="C029" aria-selected="false" v-on:click="onClickGeneralSearch">汎用検索</a>
            </li>
            <li v-if="isGeneralVacation" class="nav-item" role="presentation"> 
                <a class="nav-link-tab" style="font-size:12pt;" id="C010-01-06" data-toggle="tab" href="#C030" role="tab" aria-controls="C030" aria-selected="false" v-on:click="onClickAbsentManage">休暇管理</a>
            </li>
            <li v-if="isGeneralIO" class="nav-item" role="presentation"> 
                <a class="nav-link-tab" style="font-size:12pt;" id="C010-01-07" data-toggle="tab" href="#C031" role="tab" aria-controls="C031" aria-selected="false">データ入出力</a>
            </li>
            <li v-if="isGeneralMaster" class="nav-item" role="presentation">
                <a class="nav-link-tab" style="font-size:12pt;" id="C010-01-08" data-toggle="tab" href="#C032" role="tab" aria-controls="C032" aria-selected="false" v-on:click="onClickMasterManager">マスタ管理</a>
            </li> 
            <li v-if="isGeneralMaster" class="nav-item" role="presentation">
                <a class="nav-link-tab" style="font-size:12pt;" id="C010-01-09" data-toggle="tab" href="#C033" role="tab" aria-controls="C033" aria-selected="false" v-on:click="onClickSystemManager">システム管理</a>
            </li>
        </ul>
        <div class="tab-content" id="pills-tabContent">
            <div v-if="isGeneralAttendance" class="tab-pane fade show active pagectl" id="C025" role="tabpanel" aria-labelledby="C010-01-01">
                <w025_input_attendance :employee_id="employee_id" :session_data="session_data" ref='w025_input_attendance'></w025_input_attendance>
            </div>
            <div v-if="isGeneralWork" class="tab-pane fade" id="C026" role="tabpanel" aria-labelledby="C010-01-02">
                <w026_labor_situation :employee_id="employee_id" :session_data="session_data" ref='w026_labor_situation'></w026_labor_situation>
            </div>
            <div class="tab-pane fade" id="C027" role="tabpanel" aria-labelledby="C010-01-03">
                <w027_daily_report :employee_id="employee_id" ref='w027_daily_report'></w027_daily_report>
            </div>
            <div v-if="isGeneralClosing" class="tab-pane fade" id="C028" role="tabpanel" aria-labelledby="C010-01-04">
                <w028_close_attendance :employee_id="employee_id" :session_data="session_data" ref='w028_close_attendance'></w028_close_attendance>
            </div>
            <div v-if="isGeneralSearch" class="tab-pane fade" id="C029" role="tabpanel" aria-labelledby="C010-01-05">
                <w029_general_search :employee_id="employee_id" :session_data="session_data" ref='w029_general_search'></w029_general_search>
            </div>
            <div v-if="isGeneralVacation" class="tab-pane fade" id="C030" role="tabpanel" aria-labelledby="C010-01-06">
                <w030_absent_manage :employee_id="employee_id" ref='w030_absent_manage'></w030_absent_manage>
            </div>
            <div v-if="isGeneralIO" class="tab-pane fade" id="C031" role="tabpanel" aria-labelledby="C010-01-07">
                <w031_data_io :employee_id="employee_id" :session_data="session_data"></w031_data_io>
            </div>
            <div v-if="isGeneralMaster" class="tab-pane fade" id="C032" role="tabpanel" aria-labelledby="C010-01-08">
                <w032_master_manage :employee_id="employee_id" :session_data="session_data" ref='w032_master_manage'></w032_master_manage>
            </div>
            <div v-if="isGeneralMaster" class="tab-pane fade" id="C033" role="tabpanel" aria-labelledby="C010-01-09">
                <w033_system_manage :employee_id="employee_id" :session_data="session_data" ref='w033_system_manage'></w033_system_manage>
            </div>
        </div>
    </div>

</template>
<script>
export default {
    props: {
        employee_id: Number, 
        session_data: Object,
    },
    data() {
        return {
        };
    },
    mounted(){
    },
    methods:{
        //総務共通初期化（総務メニュークリックしたら呼ばれる）
        initialize(){
            this.$refs['w025_input_attendance'].initialize();
        },
        //勤怠入力メニュークリックしたら初期化
        onClickInputAttendance(){
            this.$refs['w025_input_attendance'].initialize();
        },
        //労働・休暇状態管理メニュークリックしたら初期化
        onClickLaborSituation(){
            this.$refs['w026_labor_situation'].initialize();
        },
        //日報メニュークリックしたら初期化
        onClickDailyReport(){
            this.$refs['w027_daily_report'].initialize();
        },
        //締め処理メニュークリックしたら初期化
        onClickCloseAttendance(){
            this.$refs['w028_close_attendance'].initialize();
        },
        //汎用検索メニュークリックしたら初期化
        onClickGeneralSearch(){
            this.$refs['w029_general_search'].initialize();
        },
        //休暇管理メニュークリックしたら初期化
        onClickAbsentManage(){
            this.$refs['w030_absent_manage'].initialize();
        },
        //マスタ管理メニュークリックしたら初期化
        onClickMasterManager(){
            this.$refs['w032_master_manage'].initialize();
        },
        //システム管理メニュークリックしたら初期化
        onClickSystemManager(){
            this.$refs['w033_system_manage'].initialize();
        },
        
    },
    computed:{
        isGeneralAttendance:function(){
            return !!Number(this.session_data.authority_pattern.general_affairs_attendance_input);
        },
        isGeneralWork:function(){
            return !!Number(this.session_data.authority_pattern.general_affairs_work_vacation_management);
        },
        isGeneralClosing:function(){
            return !!Number(this.session_data.authority_pattern.general_affairs_closing);
        },
        isGeneralSearch:function(){
            return !!Number(this.session_data.authority_pattern.general_affairs_general_search);
        },
        isGeneralVacation:function(){
            return !!Number(this.session_data.authority_pattern.general_affairs_vacation_management);
        },
        isGeneralIO:function(){
            return !!Number(this.session_data.authority_pattern.general_affairs_data_input_output);
        },
        isGeneralMaster:function(){
            return !!Number(this.session_data.authority_pattern.general_affairs_master_management);
        },
    },
    watch: {
    }
}
</script>