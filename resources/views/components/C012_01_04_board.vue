<template>
    <div class="container-fluid p-3 h-100 w-100 shadow-sm board" style="margin-top:20pt;">
        <div id="C012-01-04-01" class="text-left">
            労働時間管理（{{yearMonthStr}}）
        </div>
        <div v-if="isExistData">
            <div class="row">
                <div class="col-3 text-center">
                    <card id="C012-01-04-02" title="当月取得済み有休" :number="acquired_paid_leave_days" unit="日"></card>
                </div>
                <div class="col-3 text-center">
                    <card id="C012-01-04-03" title="当月時間外時間(法定外)" :time="non_statutory_working_time"></card>
                </div>
                <div class="col-3 text-center">
                    <card id="C012-01-04-04" title="当月遅刻・早退" :number="early_leave_late_arrival_days" unit="回"></card>
                </div>
                <div class="col-3 text-center">
                    <card id="C012-01-04-05" title="未取得の振替休日" :number="unacquired_substitute_holiday" unit="日"></card>
                </div>
            </div>
        </div>
        <div v-if="!isExistData">
            <div class="text-left" style="margin-left:50pt;color:#000000;font-size:15pt">
            データがありません
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'C012_01_04_board',
    props:{
        employee_id: Number,
        year_month: Number,
        session_data: Object,
    },
    data() {
        return {
            employeeID: 0,
            yearMonth: 0,
            labor_situation_info: '',
        };
    },
    methods: {
        getWorkingTimeManagementMonthly: async function(){
            this.labor_situation_info = await this.getAttendanceInformationMonthly(this.employeeID, this.yearMonth);
        }
    },
    computed:{
        isExistData: function(){
            return !!this.labor_situation_info.attendance_aggregate;
        },
        yearMonthStr: function(){
            return this.calcYearMonth(this.yearMonth, 0, "YYYY年MM月");
        },
        acquired_paid_leave_days: function(){
            return this.isExistData ? String(this.labor_situation_info.attendance_aggregate.acquired_paid_leave_days + this.labor_situation_info.attendance_aggregate.acquired_paid_leave_half_days / 2) : "-";
        },
        non_statutory_working_time: function(){
            return this.isExistData ? this.serialToHoursStr(this.labor_situation_info.attendance_aggregate.non_statutory_working_time + this.labor_situation_info.holiday_work_time) : "--:--";
        },
        early_leave_late_arrival_days: function(){
            return this.isExistData ? String(this.labor_situation_info.attendance_aggregate.early_leave_late_arrival_days + this.labor_situation_info.attendance_aggregate.early_leave_late_arrival_days_absent) : "-";
        },
        unacquired_substitute_holiday: function(){
            if(this.isExistData)
            {
                //振替休日年月日が入力されているが、振替休日取得年月日が入力されていない日数をカウントし「未取得の振替休日」とする
                //振替休日年月日が入力されていない日数をカウントし「未取得の振替休日」とする
                const target_end_serial = this.labor_situation_info.target_end_serial;
                var wk_unacquired_substitute_holiday = 0;
                for(let i = 0; i < this.labor_situation_info.substitute_until_this_month_info.length; i++)
                {
                    if( (this.labor_situation_info.substitute_until_this_month_info[i].substitute_holiday_date === 0)
                     || (this.labor_situation_info.substitute_until_this_month_info[i].substitute_holiday_date > target_end_serial)){
                        wk_unacquired_substitute_holiday++;
                    }
                }
                return String(wk_unacquired_substitute_holiday);
            }
            else
            {
                return "-";
            }
        }
    },
    watch: {
        employee_id: {
            immediate: true,
            handler(value) {
                if(!value || value.length <= 0)
                {
                    // do nothing
                }
                else
                {
                    this.employeeID = Number(value);
                    //yearMonthが初期値の時は処理しない（初回2度読み防止）
                    if(this.yearMonth){
                        this.getWorkingTimeManagementMonthly();
                    }
                }
            }
        },
        year_month: {
            immediate: true,
            handler(value) {
                if(!value || value.length <= 0)
                {
                    // do nothing
                }
                else
                {
                    this.yearMonth = value;
                    //employeeIDが初期値の時は処理しない（初回2度読み防止）
                    if(this.employeeID){
                        this.getWorkingTimeManagementMonthly();
                    }
                }
            }
        }
    },
}
</script>