<template>
    <div id="C009-01" class="p-3">
        <ul class="nav nav-tabs mb-3" id="pills-tab" role="tablist">
            <li v-if="isOfficeAttendance" class="nav-item" role="presentation">
                <a class="nav-link-tab active" style="font-size:12pt;" id="C009-01-01" data-toggle="tab" href="#C020" role="tab" aria-controls="C020" aria-selected="true" v-on:click="onClickInputAttendance">勤怠入力</a>
            </li>
            <li v-if="isOfficeWork" class="nav-item" role="presentation">
                <a class="nav-link-tab" style="font-size:12pt;" id="C009-01-02" data-toggle="tab" href="#C021" role="tab" aria-controls="C021" aria-selected="false" v-on:click="onClickLaborSituation">労働・休暇状態管理</a>
            </li>
            <li v-if="isOfficeClosing" class="nav-item" role="presentation">
                <a class="nav-link-tab" style="font-size:12pt;" id="C009-01-03" data-toggle="tab" href="#C022" role="tab" aria-controls="C022" aria-selected="false" v-on:click="onClickCloseAttendance">締め処理</a>
            </li>
            <li v-if="isOfficeSearch && !session_data.is_production" class="nav-item" role="presentation"> 
                <a class="nav-link-tab" style="font-size:12pt;" id="C009-01-04" data-toggle="tab" href="#C023" role="tab" aria-controls="C023" aria-selected="false">汎用検索</a>
            </li>
            <li v-if="isOfficeMaster" class="nav-item" role="presentation"> 
                <a class="nav-link-tab" style="font-size:12pt;" id="C009-01-05" data-toggle="tab" href="#C024" role="tab" aria-controls="C024" aria-selected="false" v-on:click="onClickMasterManager">マスタ管理</a>
            </li>
        </ul>
        <div class="tab-content" id="pills-tabContent">
            <div v-if="isOfficeAttendance" class="tab-pane fade show active pagectl" id="C020" role="tabpanel" aria-labelledby="C009-01-01">
                <w020_input_attendance :employee_id="employee_id" :session_data="session_data" ref='w020_input_attendance'></w020_input_attendance>
            </div>
            <div v-if="isOfficeWork" class="tab-pane fade" id="C021" role="tabpanel" aria-labelledby="C009-01-02">
                <w021_labor_situation :employee_id="employee_id" :session_data="session_data" ref='w021_labor_situation'></w021_labor_situation>
            </div>
            <div v-if="isOfficeClosing" class="tab-pane fade" id="C022" role="tabpanel" aria-labelledby="C009-01-03">
                <w022_close_attendance :employee_id="employee_id" :session_data="session_data" ref='w022_close_attendance'></w022_close_attendance>
            </div>
            <div v-if="isOfficeSearch" class="tab-pane fade" id="C023" role="tabpanel" aria-labelledby="C009-01-04">
                <w023_general_search :employee_id="employee_id" :session_data="session_data" ref='w023_general_search'></w023_general_search>
            </div>
            <div v-if="isOfficeMaster" class="tab-pane fade" id="C024" role="tabpanel" aria-labelledby="C009-01-05">
                <w024_master_manage :employee_id="employee_id" :session_data="session_data" ref='w024_master_manage'></w024_master_manage>
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
        //事業所共通初期化（事業所メニュークリックしたら呼ばれる）
        initialize(){
            this.$refs['w020_input_attendance'].initialize();
        },
        //勤怠入力メニュークリックしたら初期化
        onClickInputAttendance(){
            this.$refs['w020_input_attendance'].initialize();
        },
        //労働・休暇状態管理メニュークリックしたら初期化
        onClickLaborSituation(){
            this.$refs['w021_labor_situation'].initialize();
        },
        //締め処理メニュークリックしたら初期化
        onClickCloseAttendance(){
            this.$refs['w022_close_attendance'].initialize();
        },
        //汎用検索メニュークリックしたら初期化
        onClickGeneralSearch(){
            this.$refs['w023_general_search'].initialize();
        },
        //マスタ管理メニュークリックしたら初期化
        onClickMasterManager(){
            this.$refs['w024_master_manage'].initialize();
        },
    },
    computed:{
        isOfficeAttendance:function(){
            return !!Number(this.session_data.authority_pattern.office_attendance_input);
        },
        isOfficeWork:function(){
            return !!Number(this.session_data.authority_pattern.office_work_vacation_management);
        },
        isOfficeClosing:function(){
            return !!Number(this.session_data.authority_pattern.office_closing);
        },
        isOfficeSearch:function(){
            return !!Number(this.session_data.authority_pattern.office_general_search);
        },
        isOfficeMaster:function(){
            return !!Number(this.session_data.authority_pattern.office_master_management);
        },
    },
    watch: {
    }
}
</script>