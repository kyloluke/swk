<template>
    <div>
        <div class="row">
            <div class="col-2 text-center">
                <card_1line title="対象年月" :text="displayYearMonth" :background_type="backgroundType"></card_1line>
            </div>
            <div class="col-6">
            </div>
            <div class="col-2 text-center" v-if="isExistData">
                <card_1line title="締め状態" :text="close_state" :background_type="backgroundType"></card_1line>
            </div>
            <div class="col-2 text-center" v-if="isExistData">
                <card_1line title="締め区分" :text="close_date_name" :background_type="backgroundType"></card_1line>
            </div>
        </div>
        <div class="row" style="margin-top:20pt;" v-if="isExistData">
            <div class="col-2 text-center" v-on:click="openDailyReportModal" :class="numberOfViolationDivClass">
                <card title="要申請" :number="number_of_violation_warning" unit="件" :comment="violation_warning_comment" :background_type="backgroundType" :blink="violation_warning_blink"></card>
            </div>
            <div class="col-2 text-center">
                <card title="申請中" :number="number_of_applications" unit="件" :background_type="backgroundType"></card>
            </div>
            <div class="col-2 text-center">
                <card title="当月取得済み有休" :number="acquired_paid_leave_days" unit="日" :comment="'5日取得義務達成まであと'+until_achieve_days+'日'" :background_type="backgroundType"></card>
            </div>
            <div class="col-2 text-center">
                <card title="当月時間外時間(法定外)" :time="non_statutory_working_time" :comment="until_non_statutory_working_time" :background_type="backgroundType"></card>
            </div>
            <div class="col-2 text-center">
                <card title="当月遅刻・早退"  :number="early_leave_late_arrival_days" unit="回" :background_type="backgroundType"></card>
            </div>
            <div class="col-2 text-center">
                <card  title="未取得の振替休日" :number="unacquired_substitute_holiday" unit="日" :comment="substitute_holiday_comment" :background_type="backgroundType" :blink="substitute_holiday_blink"></card>
            </div>
        </div>
        <div class="row" style="margin-top:20pt;" v-if="!isExistData">
            <div class="col-12 text-center">
                <card_1line title="" text="データがありません" :background_type="backgroundType"></card_1line>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    name: "workingStatus_board",
    props:{
        employee_id: Number,
        year_month: Number,
        session_data: Object,
        background_type: Number,
        response_data: Object,
        is_manager: Boolean,
        updateInputAttendanceBoard: Function,
    },
    data() {
        return {
            employeeID: 0,
            yearMonth: 0,
            backgroundType: 1,
            acquired_paid_leave_days: '-',
            until_achieve_days: '-',
            non_statutory_working_time: '--:--',
            until_non_statutory_working_time: '--:--',
            early_leave_late_arrival_days: '-',
            unacquired_substitute_holiday: '-',
            substitute_holiday_comment: '',
            close_date_name: '',
            close_state: '',
            number_of_applications: '0',
            number_of_violation_warning: '0',
            violation_warning_comment: '',
            violation_warning_blink: false,
            substitute_holiday_blink: false,
            input_attendance_info: null,
            attendance_information_ids_of_violation: [],
            number_of_violation_div_class: '',
            isManager: false,
        };
    },
    methods: {
        getWorkingTimeManagementMonthly(){
            if(this.input_attendance_info.attendance_aggregate)
            {
                //締め状態
                this.close_state = this.input_attendance_info.attendance_aggregate?.close_state_id ? this.session_data.master_data.close_state[this.input_attendance_info.attendance_aggregate.close_state_id-1].close_state_name : '';

                //締め区分
                this.close_date_name = this.session_data.master_data.close_date[this.input_attendance_info.close_date_id-1].close_date_name;

                //要申請件数（乖離の件数+差戻状態の件数）,申請中件数
                var wk_number_of_violation_warning = 0;
                var wk_number_of_applications = 0;
                this.number_of_violation_warning = '0';
                this.number_of_applications = '0';
                this.violation_warning_comment = '';
                this.violation_warning_blink = false;
                this.number_of_violation_div_class = '';
                //乖離を処理待ちのIDリストを初期化する
                this.attendance_information_ids_of_violation = [];
                for(let i = 0; i < this.input_attendance_info.attendance_information.length; i++)
                {
                    //要申請件数（乖離の件数+差戻状態の件数）
                    if(this.input_attendance_info.attendance_information[i].violation_warning_id === 2 || this.input_attendance_info.attendance_information[i].approval_state_id === 4 || this.input_attendance_info.attendance_information[i].approval_state_id === 5){
                         wk_number_of_violation_warning++;
                         //乖離を処理待ちのIDリストを作成する
                         this.attendance_information_ids_of_violation.push(this.input_attendance_info.attendance_information[i].attendance_information_id);
                         this.number_of_violation_warning = String(wk_number_of_violation_warning);
                         this.violation_warning_comment = '申請・修正を行ってください';
                         this.violation_warning_blink = true;
                    }
                    //申請中件数
                    if(this.input_attendance_info.attendance_information[i].approval_state_id === 2){
                         wk_number_of_applications++;
                         this.number_of_applications = String(wk_number_of_applications);
                    }
                }
                //一番古いのから順番にダイアログして処理するから、乖離を処理待ちのIDリストをソート順する、
                this.attendance_information_ids_of_violation.sort(function(a, b){return a - b});
                //当月取得済み有休
                this.acquired_paid_leave_days = String(Number(this.input_attendance_info.attendance_aggregate.acquired_paid_leave_days) + Number(this.input_attendance_info.attendance_aggregate.acquired_paid_leave_half_days)/2);
                //5日取得義務達成まで残り有休取得日数
                this.until_achieve_days = Math.max(5 - Number(this.input_attendance_info.acquired_paid_leave_days_until_this_month), 0);

                //当月時間外時間(法定外)
                this.non_statutory_working_time = this.serialToHoursStr(this.input_attendance_info.attendance_aggregate.non_statutory_working_time + this.input_attendance_info.holiday_work_time);
                //45時間まで残り時間
                this.until_non_statutory_working_time = this.input_attendance_info.max_time_month <= 0 ? "" : 
                ((this.input_attendance_info.max_time_month - this.input_attendance_info.attendance_aggregate.non_statutory_working_time - this.input_attendance_info.holiday_work_time) <= 0 ? this.serialToHoursStr(this.input_attendance_info.max_time_month) + '時間まであと 00:00' : 
                this.serialToHoursStr(this.input_attendance_info.max_time_month) + '時間まであと' + this.serialToHoursStr(this.input_attendance_info.max_time_month - this.input_attendance_info.attendance_aggregate.non_statutory_working_time - this.input_attendance_info.holiday_work_time));
                //当月遅刻・早退
                this.early_leave_late_arrival_days = String(this.input_attendance_info.attendance_aggregate.early_leave_late_arrival_days + this.input_attendance_info.attendance_aggregate.early_leave_late_arrival_days_absent);

                //振替休日年月日が入力されているが、振替休日取得年月日が入力されていない日数をカウントし「未取得の振替休日」とする
                var wk_unacquired_substitute_holiday = 0;
                this.unacquired_substitute_holiday = '0';
                this.substitute_holiday_comment = '';
                this.substitute_holiday_blink = false;
                const target_end_serial = this.input_attendance_info.target_end_serial;
                for(let i = 0; i < this.input_attendance_info.substitute_until_this_month_info.length; i++)
                {
                    if( (this.input_attendance_info.substitute_until_this_month_info[i].substitute_holiday_date === 0)
                     || (this.input_attendance_info.substitute_until_this_month_info[i].substitute_holiday_date > target_end_serial)){
                         wk_unacquired_substitute_holiday++;
                         this.unacquired_substitute_holiday = String(wk_unacquired_substitute_holiday);
                         this.substitute_holiday_comment = '振替休日を取得して下さい';
                         this.substitute_holiday_blink = true;
                     }
                }
            }
        },

        openDailyReportModal()
        {
            if(this.number_of_violation_warning < 1) {
                return;
            }
            let itemOption = {
                'isManager':this.isManager,
                'session_data': this.session_data,
                'attendance_information_id': this.attendance_information_ids_of_violation.shift(),
                'reflectChange': this.onClickUpdate,
                'updateViolation': this.updateViolationWarningId,
                'isBunch':false,
            } 
            
            this.cleanModal();
            this.openModal('m105_input_attendance_details', 'modal-lg', itemOption);
        },

        //更新ボタン押す
        async onClickUpdate()
        {
            //勤怠入力の他のボード含め表示更新
            await this.updateInputAttendanceBoard(true);
            //休暇情報取得 重複のリクエストあるので、こちらはコメントアウトする
            // await this.getAbsentList();
        },

         //乖離判定実施
        async updateViolationWarningId()
        {
            let ret = null;
            await axios.get('/update_violation_warning_id', {
                //年月を6桁で送信
                params:{
                    'attendanceYearMonth' : this.yearMonth,
                    'employeeID': this.employeeID,
                }
            }).then(response => {
                ret = response.data.result
            });
            return ret;
        },
    },
    computed:{
        displayYearMonth: function() {
            return this.yearMonthNumberToText(this.year_month);
        },
        isExistData: function(){
            return !!this.input_attendance_info?.attendance_aggregate;
        },
        numberOfViolationDivClass: function(){
            return this.number_of_violation_warning != 0 ? 'number-of-violation-div' : '';
        }
    },
    watch: {
        background_type: {
            immediate: true,
            handler(value) {
                if(value)
                {
                    this.backgroundType = value;
                }
            }
        },
        response_data: {
            handler(value) {
                if(value)
                {
                    this.input_attendance_info = value;
                    this.getWorkingTimeManagementMonthly();
                }
            }
        },
        is_manager: { // 外からプロパティの中身が変更になったら実行される
            immediate: true,
            handler(value) {
                this.isManager = value;
            }
        },
    },
}

</script>

<style> 
    .number-of-violation-div {
        cursor: pointer !important;
    }
</style>