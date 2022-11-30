<template>
    <div class="container-fluid p-3 h-100 w-100 shadow-sm board" style="margin-top:20pt;">
        <div id="C012-01-06-01" class="text-left">
            休暇管理（{{yearMonthStartStr}}～{{yearMonthEndStr}}）
        </div>
        <div class="row">
            <div class="col-3 text-center">
                <card id="C012-01-06-02" title="有休取得義務日数（年間）" :text2="obligatoryTakePaidLeaveText" :number="obligatoryTakePaidLeaveNumber" :unit="obligatoryTakePaidLeaveUnit" :date="obligatoryTakePaidLeaveDate" :comment2="obligatoryTakePaidLeaveComment"></card>
            </div>
            <div class="col-9 text-center">
                <div class="row">
                    <div class="col-4">
                        <card id="C012-01-06-03" title="有休取得日数（年間）" :number="acquiredPaidLeaveDays" unit="日"></card>
                    </div>
                    <div class="col-4">
                        <card id="C012-01-06-05" title="保存休取得日数（年間）" :number="accumulatedPaidLeaveDays" unit="日"></card>
                    </div>
                    <div class="col-4">
                        
                    </div>
                </div>
                <div class="row" style="margin-top:20pt;">
                    <div class="col-4">
                        <card id="C012-01-06-06" title="有休残日数" :number="remainingPaidLeaveDays" unit="日"></card>
                    </div>
                    <div class="col-4">
                        <card id="C012-01-06-08" title="保存休残日数" :number="unusedAccumulatedPaidLeaveDays" unit="日"></card>
                    </div>
                    <div class="col-4">
                        
                    </div>
                </div>
            </div>
        </div>
        <div class="row" style="margin-top:20pt;">
            <div class="col-12 text-center">
                <div id="C012-01-06-09" class="card shadow h-100" style="background-color:#BCD2EE;">
                    <div class="card-body w-75">
                        <div class="card-title text-left">振替休日・休日振替出勤一覧</div>
                        <div v-if="this.substitute_information.length <= 0" class="text-left" style="margin-left:50pt;color:#000000;font-size:15pt">
                            データがありません
                        </div>
                        <table v-else class="table-white" style="margin-top:20pt;font-size:12pt;">
                            <thead>
                                <tr>
                                    <th rowspan="2">休日振替出勤日</th>
                                    <th colspan="2">振替休日</th>
                                    <th rowspan="2">休日振替出勤事由</th>
                                </tr>
                                <tr>
                                    <th>予定日</th>
                                    <th>取得日</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="item in substitute_information" :key="item.index">
                                    <td>{{item.holiday_substitute_date}}</td>
                                    <td>{{item.substitute_holiday_date}}</td>
                                    <td>{{item.acquired_substitue_holiday_date}}</td>
                                    <td>{{item.substitute_reason}}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="row" style="margin-top:20pt;">
            <div class="col-12 text-center">
                <div id="C012-01-06-12" class="card shadow h-100" style="background-color:#BCD2EE;">
                    <div class="card-body w-75">
                        <div class="card-title text-left">休暇取得一覧</div>
                        <div v-if="unemployed_information.length <= 0" class="text-left" style="margin-left:50pt;color:#000000;font-size:15pt">
                            データがありません
                        </div>
                        <table v-else class="table-white" style="margin-top:20pt;font-size:12pt;">
                            <thead>
                                <tr>
                                    <th>休暇種別</th>
                                    <th>取得日</th>
                                    <th>日数</th>
                                    <th>時間数</th>
                                    <th>事由</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="item in unemployed_information" :key="item.index">
                                    <td>{{item.holiday_substitute_name}}</td>
                                    <td>{{item.target_date}}</td>
                                    <td>{{item.days}}</td>
                                    <td>―</td>
                                    <td>{{item.request_reason}}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="row" style="margin-top:20pt;">
            <div class="col-12 text-center">
                <div id="C012-01-06-13" class="card shadow h-100" style="background-color:#BCD2EE;">
                    <div class="card-body w-75">
                        <div class="card-title text-left">休暇付与一覧</div>
                        <div v-if="holiday_management.length <= 0" class="text-left" style="margin-left:50pt;color:#000000;font-size:15pt">
                            データがありません
                        </div>
                        <table v-else class="table-white" style="margin-top:20pt;font-size:12pt;">
                            <thead>
                                <tr>
                                    <th>休暇種別</th>
                                    <th>付与日</th>
                                    <th>付与日数</th>
                                    <th>期限</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="item in holiday_management" :key="item.index">
                                    <td>{{item.holiday_name}}</td>
                                    <td>{{item.valid_date_start}}</td>
                                    <td>{{item.grant_holiday_days}}</td>
                                    <td>{{item.valid_date_end}}</td>
                                </tr>
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'C012_01_06_board',
    props:{
        employee_id: Number,
        year_month: Number,
        session_data: Object,
    },
    data() {
        return {
            employeeID: 0,
            yearMonth: 0,
            yearMonthStart :0,
            yearMonthEnd: 0,
            obligatory_take_paid_leave_days: 0,
            grant_paid_leave_type_id: 0,
            manegement_target_class: 0,
            acquired_paid_leave_days: 0,
            accumulated_paid_leave_days: 0,
            remaining_paid_leave_days: 0,
            unused_accumulated_paid_leave_days: 0,
            unused_substitute_information_days: 0,
            substitute_information: [],
            unemployed_information: [],
            holiday_management: [],
            yearfirstMonth: 7,
            labor_situation_info: '',
            input_attendance_info_prev: {},
        };
    },
    methods: {
        getHolidayManagementList: async function(){
            this.substitute_information = [];
            this.unemployed_information = [];
            this.holiday_management = [];

            this.labor_situation_info = await this.getAttendanceInformationMonthly(this.employeeID, this.yearMonth);
            
            this.yearMonthStart = this.labor_situation_info.paid_leave_date_start_serial;
            this.yearMonthEnd = this.labor_situation_info.paid_leave_date_end_serial;
            this.obligatory_take_paid_leave_days = this.labor_situation_info.obligatory_take_paid_leave_days;
            this.grant_paid_leave_type_id = this.labor_situation_info.grant_paid_leave_type_id;
            this.manegement_target_class = this.labor_situation_info.manegement_target_class;
            this.acquired_paid_leave_days = this.labor_situation_info.acquired_paid_leave_days;
            this.accumulated_paid_leave_days = this.labor_situation_info.accumulated_paid_leave_days;
            this.remaining_paid_leave_days = this.labor_situation_info.remaining_paid_leave_days;
            this.unused_accumulated_paid_leave_days = this.labor_situation_info.unused_accumulated_paid_leave_days;
            this.unused_substitute_information_days = this.labor_situation_info.unused_substitute_information_days;
                        
            this.substitute_information = this.labor_situation_info.substitute_information_array;
            this.unemployed_information = this.labor_situation_info.unemployed_information;
            this.holiday_management = this.labor_situation_info.holiday_management;
            this.substitute_information = [];
            this.unemployed_information = [];
            this.holiday_management = [];
            for(let i = 0; i < this.labor_situation_info.substitute_information_array.length; i++)
            {
                this.substitute_information.push({
                    'holiday_substitute_date': this.serialToDateStr(this.labor_situation_info.substitute_information_array[i].holiday_substitute_date),
                    'substitute_holiday_date': this.labor_situation_info.substitute_information_array[i].substitute_holiday_date == 0 ? "-" : this.serialToDateStr(this.labor_situation_info.substitute_information_array[i].substitute_holiday_date),
                    'acquired_substitue_holiday_date': this.labor_situation_info.substitute_information_array[i].acquired_substitue_holiday_date == 0 ? "-" : this.serialToDateStr(this.labor_situation_info.substitute_information_array[i].acquired_substitue_holiday_date),
                    'substitute_reason': this.labor_situation_info.substitute_information_array[i].substitute_reason != '' ? this.labor_situation_info.substitute_information_array[i].substitute_reason : '-',
                });
            }
            for(let i = 0; i < this.labor_situation_info.unemployed_information.length; i++)
            {
                this.unemployed_information.push({
                    'holiday_substitute_name': this.labor_situation_info.unemployed_information[i].holiday_substitute_name,
                    'target_date': this.serialToDateStr(this.labor_situation_info.unemployed_information[i].target_date),
                    'days': this.labor_situation_info.unemployed_information[i].days,
                    'request_reason': this.labor_situation_info.unemployed_information[i].request_reason,
                });
            }
            for(let i = 0; i < this.labor_situation_info.holiday_management.length; i++)
            {
                this.holiday_management.push({
                    'holiday_name': this.labor_situation_info.holiday_management[i].holiday_name,
                    'valid_date_start': this.serialToDateStr(this.labor_situation_info.holiday_management[i].valid_date_start),
                    'grant_holiday_days': this.labor_situation_info.holiday_management[i].grant_holiday_days,
                    'valid_date_end': this.serialToDateStr(this.labor_situation_info.holiday_management[i].valid_date_end),
                });
            }

            //前月勤務データ取得
            this.getAttendanceInformationMonthly(this.employeeID, this.calcYearMonth(this.yearMonth, -1, "YYYYMM"), true).then(value =>{
                this.input_attendance_info_prev = value;
            });
        }
    },
    computed:{
        yearMonthStartStr: function(){
            return this.serialToDateStr(this.yearMonthStart);
        },
        yearMonthEndStr: function(){
            return this.serialToDateStr(this.yearMonthEnd);
        },
        obligatoryTakePaidLeaveText: function(){
            return this.obligatory_take_paid_leave_days == 0 || this.manegement_target_class == 1 ? '対象外' : (this.acquired_paid_leave_days - this.obligatory_take_paid_leave_days >= 0 ? '達成' : '達成まであと');
        },
        obligatoryTakePaidLeaveNumber: function(){
            return this.acquired_paid_leave_days - this.obligatory_take_paid_leave_days >= 0 || this.obligatory_take_paid_leave_days == 0 || this.manegement_target_class == 1 ? '' : String(this.obligatory_take_paid_leave_days - this.acquired_paid_leave_days);
        },
        obligatoryTakePaidLeaveUnit: function(){
            return this.acquired_paid_leave_days - this.obligatory_take_paid_leave_days >= 0 || this.obligatory_take_paid_leave_days == 0 || this.manegement_target_class == 1 ? '' : '日';
        },
        obligatoryTakePaidLeaveDate: function(){
            return this.acquired_paid_leave_days - this.obligatory_take_paid_leave_days >= 0 || this.obligatory_take_paid_leave_days == 0 || this.manegement_target_class == 1 ? '' : this.serialToDateStr(this.yearMonthEnd);
        },
        obligatoryTakePaidLeaveComment: function(){
            return this.acquired_paid_leave_days - this.obligatory_take_paid_leave_days >= 0 || this.obligatory_take_paid_leave_days == 0 || this.manegement_target_class == 1 ? '' : 'までに取得してください';
        },
        acquiredPaidLeaveDays: function(){
            return this.manegement_target_class == 1 ? '－' : (this.acquired_paid_leave_days <= 0 ? '0' : String(this.acquired_paid_leave_days));
        },
        accumulatedPaidLeaveDays: function(){
            return this.accumulated_paid_leave_days > 0 ? String(this.accumulated_paid_leave_days) : '0';
        },
        remainingPaidLeaveDays: function(){
            //前月全社締め済み
            if(this.manegement_target_class == 1){
                return "－";
            }else if(this.input_attendance_info_prev.attendance_aggregate){
                if(this.input_attendance_info_prev.attendance_aggregate.close_state_id == 5){
                    return this.remaining_paid_leave_days > 0 ? String(this.remaining_paid_leave_days) : '0';
                }
                else{
                    return "－";
                }
            }
            return "0";
        },
        unusedAccumulatedPaidLeaveDays: function(){
            //前月全社締め済み
            if(this.input_attendance_info_prev.attendance_aggregate){
                if(this.input_attendance_info_prev.attendance_aggregate.close_state_id == 5){
                    return this.unused_accumulated_paid_leave_days > 0 ? String(this.unused_accumulated_paid_leave_days) : '0';
                }
                else{
                    return "－";
                }
            }
            return "0";
        },
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
                        this.getHolidayManagementList();
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
                        this.getHolidayManagementList();
                    }
                }
            }
        }
    },
}
</script>