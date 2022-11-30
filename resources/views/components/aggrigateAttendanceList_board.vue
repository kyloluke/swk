<template>
    <div>
        <div id="C011-01-01-11" class="card shadow w-100 h-100" :class="$style[backgroundClass]">
            <div class="card-body" v-if="isExistData">
                <div class="card-title float-left" :class="$style[fontColorClass]">集計（{{this.attendance_start}}～{{this.attendance_end}}）</div>
                <table class="table-record-time" style="margin-top:20pt;font-size:12pt;font-weight: 100;">
                    <thead>
                        <tr class="text-white" :class="$style[listColorClass]">
                            <th style="width:14%;font-weight: 100;">所定就業日数</th>
                            <th style="width:14%;font-weight: 100;">実働日数</th>
                            <th style="width:14%;font-weight: 100;"></th>
                            <th style="width:14%;font-weight: 100;"></th>
                            <th style="width:14%;font-weight: 100;">休日出勤日数</th>
                            <th style="width:14%;font-weight: 100;"></th>
                            <th style="width:14%;font-weight: 100;"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr style="background-color:#ffffff;">
                            <td>{{attendanceAggregateInfo.scheduled_working_days}}</td>
                            <td>{{attendanceAggregateInfo.actual_working_days}}</td>
                            <td></td>
                            <td></td>
                            <td>{{attendanceAggregateInfo.holiday_working_days}}</td>
                            <td></td>
                            <td></td>
                        </tr>
                    </tbody>
                    <thead>
                        <tr class="text-white" :class="$style[listColorClass]">
                            <th style="width:14%;font-weight: 100;">実働時間</th>
                            <th style="width:14%;font-weight: 100;">時間外時間(法定内)</th>
                            <th style="width:14%;font-weight: 100;">時間外時間(法定外)</th>
                            <th style="width:14%;font-weight: 100;">控除時間</th>
                            <th style="width:14%;font-weight: 100;">休日勤務時間</th>
                            <th style="width:14%;font-weight: 100;">深夜時間</th>
                            <th style="width:14%;font-weight: 100;">60時間超過時間</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr style="background-color:#ffffff;">
                            <td>{{attendanceAggregateInfo.actual_working_time}}</td>
                            <td>{{attendanceAggregateInfo.statutory_working_time}}</td>
                            <td>{{attendanceAggregateInfo.non_statutory_working_time}}</td>
                            <td>{{attendanceAggregateInfo.deduction_time}}</td>
                            <td>{{attendanceAggregateInfo.holiday_work_time}}</td>
                            <td>{{attendanceAggregateInfo.midnight_time}}</td>
                            <td>{{attendanceAggregateInfo.over_60hours}}</td>
                        </tr>
                    </tbody>
                    <thead>
                        <tr class="text-white" :class="$style[listColorClass]">
                            <th style="width:14%;font-weight: 100;">有休取得日数</th>
                            <th style="width:14%;font-weight: 100;">有休残日数</th>
                            <th style="width:14%;font-weight: 100;">遅早回数</th>
                            <th style="width:14%;font-weight: 100;">遅早回数(欠勤)</th>
                            <th style="width:14%;font-weight: 100;">特別休暇日数(有給)</th>
                            <th style="width:14%;font-weight: 100;">特別休暇日数(無給)</th>
                            <th style="width:14%;font-weight: 100;">振替休日取得日数</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr style="background-color:#ffffff;">
                            <td>{{attendanceAggregateInfo.acquired_paid_leave_days}}</td>
                            <td>{{remaining_paid_leave_days}}</td>
                            <td>{{attendanceAggregateInfo.early_leave_late_arrival_days}}</td>
                            <td>{{attendanceAggregateInfo.early_leave_late_arrival_days_absent}}</td>
                            <td>{{attendanceAggregateInfo.special_paid_holiday_days}}</td>
                            <td>{{attendanceAggregateInfo.special_non_paid_holiday_days}}</td>
                            <td>{{attendanceAggregateInfo.acquired_substitute_holidays}}</td>
                        </tr>
                    </tbody>
                    <thead>
                        <tr class="text-white" :class="$style[listColorClass]">
                            <th style="width:14%;font-weight: 100;">保存休取得日数</th>
                            <th style="width:14%;font-weight: 100;">保存休残日数</th>
                            <th style="width:14%;font-weight: 100;">欠勤日数</th>
                            <th style="width:14%;font-weight: 100;">欠勤時間</th>
                            <th style="width:14%;font-weight: 100;"></th>
                            <th style="width:14%;font-weight: 100;"></th>
                            <th style="width:14%;font-weight: 100;"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr style="background-color:#ffffff;">
                            <td>{{attendanceAggregateInfo.accumulated_paid_leave_days}}</td>
                            <td>{{unused_accumulated_paid_leave_days}}</td>
                            <td>{{attendanceAggregateInfo.absent_days}}</td>
                            <td>{{attendanceAggregateInfo.absent_time}}</td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                    </tbody>
                    <thead>
                        <tr class="text-white" :class="$style[listColorClass]">
                            <th class="actual-working-hours-year" style="width:14%;font-weight: 100;">実働時間(年間)</th>
                            <th class="get-paid-holiday-year" style="width:14%;font-weight: 100;">有休取得日数(年間)</th>
                            <th class="off-hours-year" style="width:14%;font-weight: 100;">時間外時間(法定外・年間)</th>
                            <th class="sp-condition-count-year" style="width:14%;font-weight: 100;">特別条項適用回数(年間)</th>
                            <th class="lastrow-empty-column-1" style="width:14%;font-weight: 100;"></th>
                            <th class="lastrow-empty-column-2" style="width:14%;font-weight: 100;"></th>
                            <th class="lm-lw-aw-hours" style="width:14%;font-weight: 100;">前月最終週実働時間</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="agree-year-detail" style="background-color:#ffffff;">
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="card-body" v-if="!isExistData">
                <div class="text-center" style="color:#000000;font-size:15pt">
                データがありません
                </div>
            </div>
        </div>
    </div>
</template>
<script>


export default {
    name: "aggrigateAttendancelist_board",
    props:{
        year_month: Number,
        employee_id: Number,
        background_type: {type: Number, default: 1},
        response_data: Object,
        is_prev_closed: Boolean,
    },
    data() {
        return {
            attendanceAggregateInfo: {
                attendance_year_month: "",
                scheduled_working_days: 0,
                actual_working_days: 0,
                holiday_working_days: 0,
                actual_working_time: 0,
                statutory_working_time: 0,
                non_statutory_working_time: 0,
                deduction_time: 0,
                holiday_work_time: 0,
                midnight_time: 0,
                over_60hours: 0,
                last_grant_paid_leave_pattern_id: 0,
                acquired_paid_leave_days: 0,
                remaining_paid_leave_days: 0,
                early_leave_late_arrival_days: 0,
                early_leave_late_arrival_days_absent: 0,
                special_paid_holiday_days: 0,
                special_non_paid_holiday_days: 0,
                accumulated_paid_leave_days: 0,
                unused_accumulated_paid_leave_days: 0,
                acquired_substitute_holidays: 0,
                absent_days: 0,
                absent_time: 0,
                close_state_id: 0,
            },
            yearMonth: 0,
            employeeID: 0,
            backgroundType: 1,
            attendance_start: '',
            attendance_end: '',
            response_data_exist: false,
            input_attendance_info: null,
        };
    },
    mounted() {
        
    },
    methods:{
        getAggrigateAttendanceList()
        {
            if(this.input_attendance_info)
            {
                //プロパティにセット                    
                this.attendanceAggregateInfo.attendance_year_month = this.input_attendance_info.attendance_aggregate != null ?this.input_attendance_info.attendance_aggregate.attendance_year_month :'';
                this.attendanceAggregateInfo.scheduled_working_days = this.input_attendance_info.attendance_aggregate != null ?this.input_attendance_info.attendance_aggregate.scheduled_working_days :'';
                this.attendanceAggregateInfo.actual_working_days = this.input_attendance_info.attendance_aggregate != null ?this.input_attendance_info.attendance_aggregate.actual_working_days :'';
                this.attendanceAggregateInfo.holiday_working_days = this.input_attendance_info.attendance_aggregate != null ?this.input_attendance_info.attendance_aggregate.holiday_working_days :'';
                this.attendanceAggregateInfo.actual_working_time = this.input_attendance_info.attendance_aggregate != null ?this.serialToHoursStr(this.input_attendance_info.attendance_aggregate.actual_working_time) :'';
                this.attendanceAggregateInfo.statutory_working_time = this.input_attendance_info.attendance_aggregate != null ?this.serialToHoursStr(this.input_attendance_info.attendance_aggregate.statutory_working_time) :'';
                this.attendanceAggregateInfo.non_statutory_working_time = this.input_attendance_info.attendance_aggregate != null ?this.serialToHoursStr(this.input_attendance_info.attendance_aggregate.non_statutory_working_time) :'';
                this.attendanceAggregateInfo.deduction_time = this.input_attendance_info.attendance_aggregate != null ?this.serialToHoursStr(this.input_attendance_info.attendance_aggregate.deduction_time) :'';
                this.attendanceAggregateInfo.holiday_work_time = this.input_attendance_info.attendance_aggregate != null ?this.serialToHoursStr(this.input_attendance_info.attendance_aggregate.holiday_work_time) :'';
                this.attendanceAggregateInfo.midnight_time = this.input_attendance_info.attendance_aggregate != null ?this.serialToHoursStr(this.input_attendance_info.attendance_aggregate.midnight_time) :'';
                this.attendanceAggregateInfo.over_60hours = this.input_attendance_info.attendance_aggregate != null ?this.serialToHoursStr(this.input_attendance_info.attendance_aggregate.over_60hours) :'';
                this.attendanceAggregateInfo.last_grant_paid_leave_pattern_id = this.input_attendance_info.attendance_aggregate != null ?this.input_attendance_info.attendance_aggregate.last_grant_paid_leave_pattern_id :'';
                this.attendanceAggregateInfo.acquired_paid_leave_days = this.input_attendance_info.attendance_aggregate != null ?this.input_attendance_info.attendance_aggregate.acquired_paid_leave_days + this.input_attendance_info.attendance_aggregate.acquired_paid_leave_half_days/2 :'';
                this.attendanceAggregateInfo.remaining_paid_leave_days = this.input_attendance_info.attendance_aggregate != null ?this.input_attendance_info.attendance_aggregate.remaining_paid_leave_days + this.input_attendance_info.attendance_aggregate.remaining_paid_leave_half_days/2 :'';
                this.attendanceAggregateInfo.early_leave_late_arrival_days = this.input_attendance_info.attendance_aggregate != null ?this.input_attendance_info.attendance_aggregate.early_leave_late_arrival_days :'';
                this.attendanceAggregateInfo.early_leave_late_arrival_days_absent = this.input_attendance_info.attendance_aggregate != null ?this.input_attendance_info.attendance_aggregate.early_leave_late_arrival_days_absent :'';    
                this.attendanceAggregateInfo.special_paid_holiday_days = this.input_attendance_info.attendance_aggregate != null ?this.input_attendance_info.attendance_aggregate.special_paid_holiday_days + this.input_attendance_info.attendance_aggregate.special_paid_holiday_half_days/2 :'';
                this.attendanceAggregateInfo.special_non_paid_holiday_days = this.input_attendance_info.attendance_aggregate != null ?this.input_attendance_info.attendance_aggregate.special_non_paid_holiday_days + this.input_attendance_info.attendance_aggregate.special_non_paid_holiday_half_days/2 :'';
                this.attendanceAggregateInfo.accumulated_paid_leave_days = this.input_attendance_info.attendance_aggregate != null ?this.input_attendance_info.attendance_aggregate.accumulated_paid_leave_days + this.input_attendance_info.attendance_aggregate.accumulated_paid_leave_half_days/2 :'';
                this.attendanceAggregateInfo.unused_accumulated_paid_leave_days = this.input_attendance_info.attendance_aggregate != null ?this.input_attendance_info.attendance_aggregate.unused_accumulated_paid_leave_days + this.input_attendance_info.attendance_aggregate.unused_accumulated_paid_leave_half_days/2 :''; 
                this.attendanceAggregateInfo.acquired_substitute_holidays = this.input_attendance_info.attendance_aggregate != null ?this.input_attendance_info.attendance_aggregate.acquired_substitute_holidays :'';
                this.attendanceAggregateInfo.absent_days = this.input_attendance_info.attendance_aggregate != null ?this.input_attendance_info.attendance_aggregate.absent_days + this.input_attendance_info.attendance_aggregate.absent_half_days/2 :'';
                this.attendanceAggregateInfo.absent_time = this.input_attendance_info.attendance_aggregate != null ?this.serialToHoursStr(this.input_attendance_info.attendance_aggregate.absent_time) :'';
                this.attendanceAggregateInfo.close_state_id = this.input_attendance_info.attendance_aggregate ?this.input_attendance_info.attendance_aggregate.close_state_id :'';

                this.attendance_start = this.serialToDateStr(this.input_attendance_info.target_start_serial);
                this.attendance_end = this.serialToDateStr(this.input_attendance_info.target_end_serial);
            }
        }
    },
    computed:{
        backgroundClass: function() {
            switch(this.backgroundType)
            {
                case 1: //本人入力
                    return "card_background_blue";
                case 2: //代理入力
                    return "card_background_orange";
                case 3: //事業所・総務
                    return "card_background_green";
            }
            //未指定は青
            return "card_background_blue";
        },
        fontColorClass:function(){
            switch(this.backgroundType)
            {
                case 1: //本人入力
                    return "card_fontcolor_blue";
                case 2: //代理入力
                    return "card_fontcolor_orange";
                case 3: //事業所・総務
                    return "card_fontcolor_green";
            }
            //未指定は青
            return "card_background_blue";
        },
        listColorClass:function(){
            switch(this.backgroundType)
            {
                case 1: //本人入力
                    return "card_listcolor_blue";
                case 2: //代理入力
                    return "card_listcolor_orange";
                case 3: //事業所・総務
                    return "card_listcolor_green";
            }
            //未指定は青
            return "card_listcolor_blue";
        },
        isExistData: function(){
            return !!this.input_attendance_info;
        },
        remaining_paid_leave_days: function(){
            if(this.is_prev_closed)
            {
                return this.attendanceAggregateInfo.remaining_paid_leave_days;
            }
            else
            {
                return "－";
            }
        },
        unused_accumulated_paid_leave_days: function(){
            if(this.is_prev_closed)
            {
                return this.attendanceAggregateInfo.unused_accumulated_paid_leave_days;
            }
            else
            {
                return "－";
            }
        }
    },
    watch: {
        background_type: { // 外からプロパティの中身が変更になったら実行される
            immediate: true,
            handler(value) {
                this.backgroundType = value;
            }
        },
        response_data: { // 外からプロパティの中身が変更になったら実行される
            handler(value) {
                if(value)
                {
                    this.input_attendance_info = value;
                    this.getAggrigateAttendanceList();
                }
            }
        }
    }
}
</script>
<style module> 
.card_background_blue{
    background-color: #BCD2EE !important;
}
.card_background_orange{
    background-color: #F8CBAD !important;
}
.card_background_green{
    background-color: #C5E0B4 !important;
}
.card_fontcolor_blue{
    color: #27408B !important;
}
.card_fontcolor_orange{
    color: #C55A11 !important;
}
.card_fontcolor_green{
    color: #385723 !important;
}
.card_listcolor_blue{
    background-color: #3490dc !important;
}
.card_listcolor_orange{
    background-color: #FF6600 !important;
}
.card_listcolor_green{
    background-color: #548235 !important;
}

</style>