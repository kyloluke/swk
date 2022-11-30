<template>
    <div id="C007-01" class="p-3">
        <ul class="nav nav-tabs mb-3" id="pills-tab" role="tablist">
            <li v-if="isAttendanceInquiry" class="nav-item" role="presentation">
                <a class="nav-link-tab active" style="font-size:12pt;" id="C007-01-01" data-toggle="tab" href="#C016" role="tab" aria-controls="C016" aria-selected="true" v-on:click="onClickApproval">照会・承認</a>
            </li>
            <li v-if="isAttendanceVacation" class="nav-item" role="presentation">
                <a class="nav-link-tab" style="font-size:12pt;" id="C007-01-02" data-toggle="tab" href="#C017" role="tab" aria-controls="C017" aria-selected="false" v-on:click="onClickLaborSituation">労働・休暇状態管理</a>
            </li>
            <li v-if="isAttendanceDaily" class="nav-item" role="presentation">
                <a class="nav-link-tab" style="font-size:12pt;" id="C007-01-03" data-toggle="tab" href="#C018" role="tab" aria-controls="C018" aria-selected="false" v-on:click="onClickDailyReport">日報</a>
            </li>
            <li v-if="isAttendanceTarget" class="nav-item" role="presentation"> 
                <a class="nav-link-tab" style="font-size:12pt;" id="C007-01-04" data-toggle="tab" href="#C019" role="tab" aria-controls="C019" aria-selected="false">承認対象者設定</a>
            </li>
        </ul>
        <div class="tab-content" id="pills-tabContent">
            <div v-if="isAttendanceInquiry" class="tab-pane fade show active pagectl" id="C016" role="tabpanel" aria-labelledby="C007-01-01">
                <w016_approval  :employee_id="employee_id" :session_data="session_data" ref='w016_approval'></w016_approval>
            </div>
            <div v-if="isAttendanceVacation" class="tab-pane fade" id="C017" role="tabpanel" aria-labelledby="C007-01-02">
                <w017_labor_situation  :employee_id="employee_id" :session_data="session_data" ref='w017_labor_situation'></w017_labor_situation>
            </div>
            <div v-if="isAttendanceDaily" class="tab-pane fade" id="C018" role="tabpanel" aria-labelledby="C007-01-03">
                <w018_daily_report  :employee_id="employee_id" ref='w018_daily_report'></w018_daily_report>
            </div>
            <div v-if="isAttendanceTarget" class="tab-pane fade" id="C019" role="tabpanel" aria-labelledby="C007-01-04">
                <w019_setting_target  :employee_id="employee_id" :session_data="session_data"></w019_setting_target>
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
        //勤怠管理者共通初期化（勤怠管理者メニュークリックしたら呼ばれる）
        initialize(){
            this.$refs['w016_approval'].initialize();
        },
        //照会・承認メニュークリックしたら初期化
        onClickApproval(){
            this.$refs['w016_approval'].initialize();
        },
        //労働・休暇状態管理メニュークリックしたら初期化
        onClickLaborSituation(){
            this.$refs['w017_labor_situation'].initialize();
        },
        //日報メニュークリックしたら初期化
        onClickDailyReport(){
            this.$refs['w018_daily_report'].initialize();
        }
    },
    computed:{
        isAttendanceInquiry:function(){
            return !!Number(this.session_data.authority_pattern.attendance_admin_inquiry_approval);
        },
        isAttendanceVacation: function(){
            return !!Number(this.session_data.authority_pattern.attendance_admin_work_vacation_management);
        },
        isAttendanceDaily: function(){
            return !!Number(this.session_data.authority_pattern.attendance_admin_daily_report);
        },
        isAttendanceTarget: function(){
            return !!Number(this.session_data.authority_pattern.attendance_admin_approval_target_setting);
        },
    },
    watch: {
    }
}
</script>