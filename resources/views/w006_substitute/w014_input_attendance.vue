<template>
    <div id="C014-01" class="container-fluid p-3 h-100 w-100">
        <loading :active.sync="isLoading" :can-cancel="true" :on-cancel="onCancel" :is-full-page="fullPage"></loading> 
        
        <div v-show="!isSelectedTarget">
            <div id="C014-01-03" class="container-fluid p-3 h-100 w-100 board">
                <div class="container-fluid h-100 w-100" style="margin-top: 10pt">
                    <button class="btn btn-primary" style="font-size:15pt; width:100pt; text-align: left; padding-left: 22px;" v-on:click="onClickPrev()">◀　前月</button>
                    <span class="text-left" style="color: #000000; font-size: 24px; margin-left: 60px; margin-right: 60px; vertical-align: middle;" v-html="targetYearMonth"></span>
                    <button class="btn btn-primary" style="font-size: 15pt; width: 100pt; text-align: right; padding-right: 22px;" v-on:click="onClickNext()">翌月　▶</button>
                </div>
            </div> 
            <div id="C014-01-01" class="container-fluid p-3 h-100 w-100 shadow-sm board" style="margin-top:20pt;">
                <targetPersonStatusList :target_type="targetType" :unapplied_cnt="unapplied_cnt" :unapproved_cnt="unapproved_cnt" :warning_cnt="warning_cnt" :violation_cnt="violation_cnt" :close_state_cnt="close_state_cnt" :session_data="session_data"></targetPersonStatusList>
            </div>
            <div id="C014-01-02" class="container-fluid p-3 h-100 w-100 shadow-sm board" style="margin-top:20pt;">
                <div class="row">
                    <div class="px-2">
                        <div id="C014-01-02-01" class="text-left">
                            対象者選択
                        </div>
                    </div>
                </div>
                <div id="C014-01-02-02" class="card shadow h-100" style="background-color:#BCD2EE;">
                    <div class="card-body">
                        <div class="card-title">代理入力対象者を選択してください</div>
                        <inputAgentList_board :inputAgentListInfoList="inputAgentListInfoList" :isTarget="isTarget" :employee_id="targetEmployeeID" :year_month_day="yearMonthDay" :session_data="session_data" :target_type="targetType" ref='inputAgentList_board' @selectTarget="selectTarget"></inputAgentList_board>
                    </div>
                </div>
            </div>
        </div>
        <div v-show="isSelectedTarget">
            <div id="C014-02-01" class="container-fluid px-3 h-100 w-100 shadow-sm board nameselect-header">
                <div class="row">
                    <div class="p-3 col-10 text-left">
                        <div class="row">
                            <div id="C014-02-01-01" style="font-size:11pt;">対象者選択</div>
                        </div>
                        <div class="row pl-5">
                            <div id="C014-02-01-03" style="font-size:15pt;color:black;" v-html="selected_employee_message"></div>
                        </div>
                    </div>
                    <div class="p-3 col-2 text-center mt-3">
                        <button id="C014-02-01-04" style="font-size:11pt;width:100pt" class="btn btn-primary" v-on:click="exitInput()">代理入力終了</button>
                    </div>
                </div>
            </div>
            <inputAttendance_board :employee_id="targetEmployeeID" :year_month="yearMonth" :is_manager="false" :background_type="backgroundType" :information_attendance_mode="0" :session_data="session_data"></inputAttendance_board>
        </div>
    </div>
</template>
<script>
import Loading from 'vue-loading-overlay';
import 'vue-loading-overlay/dist/vue-loading.css';

export default {
    props: {
        employee_id: Number, //親からもらった社員番号 Numberで来る
        session_data: Object,
    },
    components: {
        "loading":Loading
    },
    data() {
        return {
            inputAgentListInfoList: [],
            targetType: 2,
            isTarget: false,
            employeeID: 0, //ここでの値保持＆子へ渡す用
            yearMonth: Number(this.serialToDateStr(this.todaySerial(), "YYYYMM")), //ここでの値保持＆子へ渡す用
            yearMonthDay: this.todaySerial(), //ここでの値保持＆子へ渡す用
            targetEmployeeID: 0,
            selected_employee_message: '',
            isSelectedTarget: false,
            backgroundType: 2,  //背景色
            unapplied_cnt: 0,
            unapproved_cnt: 0,
            warning_cnt: 0,
            violation_cnt: 0,
            close_state_cnt: 0,
            isLoading: false,  //ローディング画面用プロパティ
            fullPage: true,    //ローディング画面用プロパティ
        };
    },
    methods: {
        //初期化メソッド（親から呼ばれる）
        initialize()
        {
            this.isSelectedTarget = false;
            this.getInputAgentList();
        },
        getInputAgentList(){
            this.isLoading = true;
            axios.get('inputAgentList', {
                //年月日を8桁で送信
                params:{
                    'targetDate' : this.yearMonthDay,
                    'targetType': this.targetType,
                }
            }).then(response => {
                if(response.data.result)
                {
                    const employee_array = response.data.values.employee_array;
                    this.unapplied_cnt = 0;
                    this.unapproved_cnt = 0;
                    this.warning_cnt = 0;
                    this.violation_cnt = 0;
                    this.close_state_cnt = 0;
                    this.inputAgentListInfoList = [];
                    if(employee_array.length === 0)
                    {
                        this.isTarget = false;
                    }
                    else
                    {
                        this.isTarget = true;
                        this.inputAgentListInfoList = [];
                
                        for(let key in employee_array)
                        {
                            var violation_warning_name = '';
                            //違反、警告がある場合のみ文言表示
                            if((employee_array[key].violation_warning == 3)||(employee_array[key].violation_warning == 4)){
                                violation_warning_name = this.session_data.master_data.violation_warning[employee_array[key].violation_warning-1].violation_warning_name + 'あり';
                            }
                            this.inputAgentListInfoList.push({
                                'list_background_class': this.listRowColorClass(employee_array[key]),
                                'employee_id': employee_array[key].employee_id,
                                'employee_code': employee_array[key].employee_code,
                                'employee_name': employee_array[key].employee_name,
                                'employee_close_date_id': employee_array[key].close_date_id,
                                'post': employee_array[key].post?.post_name,
                                'office_dept': employee_array[key].office.office_name + '/' + employee_array[key].dept.dept_name,
                                'unapplied_cnt': employee_array[key].unapplied_cnt,
                                'unapproved_cnt': employee_array[key].unapproved_cnt,
                                'violation_warning': violation_warning_name,
                                'close_state_thismonth': employee_array[key].close_state_thismonth ? this.session_data.master_data.close_state[employee_array[key].close_state_thismonth - 1].close_state_name : '',
                                'close_state_lastmonth': employee_array[key].close_state_lastmonth ? this.session_data.master_data.close_state[employee_array[key].close_state_lastmonth - 1].close_state_name : '',
                                'overtime_working_time': employee_array[key].overtime_working_time ? employee_array[key].overtime_working_time : 0,
                                'midnight_time': employee_array[key].midnight_time,
                                'deduction_time': employee_array[key].deduction_time,
                                'over_time_non_statutory_holiday_work_time': employee_array[key].over_time_non_statutory_holiday_work_time,
                                'over_time_statutory_holiday_work_time': employee_array[key].over_time_statutory_holiday_work_time,
                                'absent_deduction_target_time': employee_array[key].absent_deduction_target_time,
                                'chlid_deduction_time': employee_array[key].chlid_deduction_time, 
                                'employee_sort': employee_array[key].employee_sort,
                            });
                            if(!(employee_array[key].unapplied_cnt == 0)){
                                this.unapplied_cnt++;
                            }
                            if(!(employee_array[key].unapproved_cnt == 0)){
                                this.unapproved_cnt++;
                            }
                            if(employee_array[key].close_state_lastmonth == 1){
                                this.close_state_cnt++;
                            }
                            if(employee_array[key].violation_warning == 3){
                                this.violation_cnt++;
                            }
                            if(employee_array[key].violation_warning == 4){
                                this.warning_cnt++;
                            }
                        }
                        this.inputAgentListInfoList.sort(this.ascendingOrder);
                    }
                }
                else
                {
                    //取得失敗
                }
                //ローディング画面隠す
                this.isLoading = false;
            }).catch(error => {
                //ローディング画面隠す
                this.isLoading = false;
            });
        },
        onCancel() {
            //Loading画面のキャンセル
        },
        ascendingOrder(a, b) { //社員ID昇順並べ替え
            if (a.employee_sort < b.employee_sort) return -1;
            if (a.employee_sort > b.employee_sort) return 1;
            return 0;
        },
        listRowColorClass:function(employeeInfo){
            if(employeeInfo.violation_warning == 4)
            {
                //警告状態あり
                return "list_background_red";
            }
            if(employeeInfo.violation_warning == 3)
            {
                //違反状態あり
                return "list_background_orange";
            }
            if(employeeInfo.unapplied_cnt > 0)
            {
                //未申請・もしくは未承認あり
                return "list_background_yellow";
            }
            //初期状態
            return "list_background_white";
        },
        selectTarget(data){
            this.targetEmployeeID = data.targetEmployeeID;
            let close_date = this.getMasterData().close_date[data.targetEmployeeCloseDateId - 1].close_date;
            let display_switch_date = this.getMasterData().close_date[data.targetEmployeeCloseDateId - 1].display_switch_date;
            display_switch_date = display_switch_date == 0 ? close_date + 1 : display_switch_date;
            this.yearMonth = this.getDisplaySwitchDate(display_switch_date, this.yearMonthDay);
            this.selected_employee_message = data.selected_employee_message;
            this.isSelectedTarget = true;
        },
        exitInput()
        {
            this.isSelectedTarget = false;
        },
        onClickPrev() {
            this.yearMonth = this.calcYearMonth(this.yearMonth, -1);
        },
        onClickNext() {
            this.yearMonth = this.calcYearMonth(this.yearMonth, 1);
        },
        yearMonthChanged(value) {
            let date = new Date();
            let res = date.getFullYear()+''+(('00'+(date.getMonth()+1)).slice(-2));
            // 未来の年月は何も表示しないように
            if(value > res) {
                this.inputAgentListInfoList = [];
                this.unapplied_cnt = 0;
                this.unapproved_cnt = 0;
                this.warning_cnt = 0;
                this.violation_cnt = 0;
                this.close_state_cnt = 0;
                this.isTarget = false;
                return false;
            }
            this.yearMonth = value;
            let yearMonthObj = new String(value)
            let month = yearMonthObj.slice(4)
            let year = yearMonthObj.slice(0,4)
            // 選択された月の日数を取得する
            let day = this.getDayOfMonth(year, month)
            // 選択された月の最後の日を利用する
            this.yearMonthDay = Number(this.dateStrToSerial(year+'/'+month+'/'+day))
            this.getInputAgentList();
        }
    },
    mounted(){
    },
    computed:{
        targetYearMonth: function(){
            return this.yearMonthNumberToText(this.yearMonth);       
        },
    },
    watch: {
        employee_id: { // 外からプロパティの中身が変更になったら実行される
            immediate: true,
            handler(value) {
                if(!value || value.length <= 0)
                {
                    // do nothing
                }
                else
                {
                    this.employeeID = value;
                }
            }
        },
        yearMonth: {
            immediate: false,
            handler(value) {
                if(!value || value.length <= 0) {
                    // do nothing
                } else {
                    this.yearMonthChanged(value);
                }
            }
        }
    },
}
</script>