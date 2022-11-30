<template>
    <div id="C017-01-01" class="container-fluid p-3 h-100 w-100 shadow-sm board">
        <loading :active.sync="isLoading"
            :can-cancel="true"
            :on-cancel="onCancel"
            :is-full-page="fullPage"></loading> 
        <div class="row">
            <div class="px-2">
                <div id="C017-01-01-01" class="text-left">
                    一覧表示
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-3 text-center">
                <div id="C017-01-01-02" class="card shadow h-100" style="background-color:#BCD2EE;">
                    <div class="card-body">
                        <div class="card-title text-left">表示条件指定</div>
                        <div class="text-center" style="color:#000000;">
                            <select class="form-control" v-model="listValue">
                                <option value=1>勤怠状況一覧</option>
                                <option value=2>休日出勤者一覧</option>
                                <option value=3>36協定チェック一覧</option>
                                <option value=4>有休・休日条件一覧</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-2 text-center">
                <div id="C017-01-01-03" class="card shadow h-100" style="background-color:#BCD2EE;">
                    <div class="card-body">
                        <div class="card-title text-left">表示期間指定</div>
                        <vuejsDatepicker :format="datePickerFormat" :language="datePickerLanguage" minimum-view="month" :disabled-dates="datePickerDisabledDates" id="C017-01-01-03-002" class="dp-form-control" v-model="defaultDate"></vuejsDatepicker>
                    </div>
                </div>
            </div>
            <div class="col-3 text-center">
                <div id="C017-01-01-04" class="card shadow h-100" style="background-color:#BCD2EE;">
                    <div class="card-body">
                        <div class="card-title text-left">締め区分</div>
                        <div class="text-center" style="color:#000000;">
                            <select class="form-control" v-model="closeDateValue">
                                <option value=1>月末締め</option>
                                <option value=2>15日締め</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-4 text-center">
                <div id="C017-01-01-05" class="card shadow h-100" style="background-color:#BCD2EE;">
                    <div class="card-body">
                        <div class="card-title text-left">表示対象</div>
                        <div class="form-check form-check-inline">
                            <div class="form-check-label">
                                <input type="checkbox" class="form-check-input" v-on:change="onCheckBoxClick()" v-model="isCheckedThirtysixApply">36協定適用者
                            </div>
                        </div>
                        <div class="form-check form-check-inline">
                            <div class="form-check-label">
                                <input type="checkbox" class="form-check-input" v-on:change="onCheckBoxClick()" v-model="isCheckedThirtysixUnapply">36協定適用外
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row" style="margin-top:20pt;">
            <div class="col-12">
                <button id="C017-01-01-06" class="btn btn-primary float-right" style="font-size:15pt;width:100pt" v-on:click="onClickSelectList()" v-bind:disabled="is_searchable">{{searchText}}</button>
            </div>
        </div>

    </div>
</template>

<script>
import vuejsDatepicker from 'vuejs-datepicker'
import Loading from 'vue-loading-overlay';
import 'vue-loading-overlay/dist/vue-loading.css';

export default {
    name: "laborManagementSearch_board",
    props:{
        employee_id: Number,
        is_selected_single: Number,//1:単一;2:複数;デフォルト0で処理しないようにするため、数字を使います
        is_enable_select_office: Boolean,
        office_id: Number,
    },
    components: {
        "loading":Loading,
        vuejsDatepicker
    },
    data() {
        return {
            isSelectedTarget: false,
            isSelectedList: false,
            employeeID: 0, //ここでの値保持＆子へ渡す用
            yearMonth: 0, //ここでの値保持＆子へ渡す用
            datePickerFormat: 'yyyy/MM',
            datePickerLanguage:{
                language: 'Japanese', 
                months: ['1月', '2月', '3月', '4月', '5月', '6月', '7月', '8月', '9月', '10月', '11月', '12月'], 
                monthsAbbr: ['1月', '2月', '3月', '4月', '5月', '6月', '7月', '8月', '9月', '10月', '11月', '12月'], 
                days: ['日', '月', '火', '水', '木', '金', '土'],
                rtl: false, 
                ymd: false,
                yearSuffix: '年'
            },
            datePickerDisabledDates: {
                from: new Date(),
            },
            modalOption: {
                select_period_type: true, //true:複数選択、false:択一選択
                callback_select: (employee)=>{this.callback_select(employee);},
                callback_cancel: ()=>{this.callback_cancel();},
                isEnableSelectOffice: true,
                employeeID: 1, //ここでの値保持＆子へ渡す用
                closeDateId: 1,
                officeId: 0,
            },
            isEnableSelectOffice:true,
            officeId: 0,
            defaultDate: new Date(),
            selectedEmployeeID: 0,
            selectedEmployee: [],
            SelectedEmployeeIdList: [],
            closeDateId: 1,
            listId: 1,
            isSelectedSingle: 0,
            isCheckedThirtysixApply: true,
            isCheckedThirtysixUnapply: true,
            thirtysixApply: true,
            thirtysixUnapply: true,
            is_searchable: false,
            target_data_info: [],
            listValue: 1,
            closeDateValue: 1,
            isLoading: false,  //ローディング画面用プロパティ
            fullPage: true,    //ローディング画面用プロパティ
        };
    },
    methods: {
        //初期化メソッド（親から呼ばれる）
        initialize()
        {
            this.isSelectedTarget = false;
            this.isSelectedList = false;
            this.isCheckedThirtysixApply = true;
            this.isCheckedThirtysixUnapply = true;
            this.thirtysixApply = true;
            this.thirtysixUnapply = true;
        },
        onCheckBoxClick() {
            if(this.isCheckedThirtysixApply || this.isCheckedThirtysixUnapply){
                this.is_searchable = false;
            }else{
                this.is_searchable = true;
            }
        },
        onClickSelectList(){
            this.isSelectedList = true;
            this.listId = Number(this.listValue);
            this.yearMonth = Number(this.serialToDateStr(this.dateStrToSerial(this.defaultDate), "YYYYMM"));
            this.closeDateId = Number(this.closeDateValue);
            if(this.isSelectedSingle == 1){

                this.getInputAgentList();

            }else if(this.isSelectedSingle == 2){
                this.modalOption.closeDateId = this.closeDateId;
                this.modalOption.officeId = this.officeId;
                this.modalOption.isEnableSelectOffice = this.isEnableSelectOffice;
                
                //モーダルを開く
                this.openModal('m110_search_member','', this.modalOption);
            }
            this.isSelectedTarget = false;
        },
        //モーダルにてキャンセルされたときのコールバック
        callback_cancel(){
            
        },
        //対象者が選択されたときのコールバック
        callback_select(employee){
            this.selectedEmployee = employee;
            this.SelectedEmployeeIdList = [];
            for(let i = 0; i < employee.length; i++){

                this.SelectedEmployeeIdList[i] = employee[i].employee_id;
            }
            this.getInputAgentList();
        },
        getInputAgentList(){
            this.isLoading = true;
            if(this.listId === 1){
                this.listName = '勤怠状態一覧';
                axios.get('approvalTargetList', {
                    //年月を6桁で送信
                    params:{
                        'employeeID' : this.employeeID,
                        'approvalTargetDate' : this.yearMonth,
                        'closeDateId' : this.closeDateId,
                        'SelectedEmployeeIdList' : this.SelectedEmployeeIdList,
                        'isSelectedSingle' : this.isSelectedSingle,
                        'isCheckedThirtysixApply' : this.isCheckedThirtysixApply,
                        'isCheckedThirtysixUnapply' : this.isCheckedThirtysixUnapply,
                    }
                }).then(response => {
                    if(response.data.result)
                    {
                        this.target_data_info = [];
                        for(let i = 0; i < response.data.values.length; i++)
                        {
                            var violation_warning_name = '';
                            //違反、警告がある場合のみ文言表示
                            if((response.data.values[i].violation_warning == 3)||(response.data.values[i].violation_warning == 4)){
                                violation_warning_name = this.getMasterData().violation_warning[response.data.values[i].violation_warning-1].violation_warning_name + 'あり';
                            }
                            this.target_data_info.push({
                                'employee_id': response.data.values[i].employee_id,
                                'employee_code': response.data.values[i].employee_code,
                                'employee_name': response.data.values[i].employee_name,
                                'employee_post': response.data.values[i].post_name,
                                'employee_office': response.data.values[i].office_name,
                                'employee_dept': response.data.values[i].dept_tree,
                                'unapplied_cnt': response.data.values[i].unapplied_cnt,
                                'unapproved_cnt': response.data.values[i].unapproved_cnt,
                                'violation_warning': violation_warning_name,
                                'close_state_thismonth': response.data.values[i].close_state_thismonth ? this.getMasterData().close_state[response.data.values[i].close_state_thismonth - 1].close_state_name : '',
                                'close_state_lastmonth': response.data.values[i].close_state_lastmonth ? this.getMasterData().close_state[response.data.values[i].close_state_lastmonth - 1].close_state_name : '',
                            });
                        }
                        let data = {
                            target_data_info: this.target_data_info,
                            yearMonth : this.yearMonth,
                            listId : this.listId,
                        };
                        this.$emit('selectedList',data);
                        this.isLoading = false;
                    }
                    else
                    {
                        this.isLoading = false;
                        //取得失敗
                    }
                }).catch((error)=>{
                    this.isLoading = false;
                });
            }else if(this.listId === 2){
                this.listName = '休日出勤者一覧';
                axios.get('holidayWorkerInformationList', {
                    //年月を6桁で送信
                    params:{
                        'employeeID' : this.employeeID,
                        'approvalTargetDate' : this.yearMonth,
                        'closeDateId' : this.closeDateId,
                        'SelectedEmployeeIdList' : this.SelectedEmployeeIdList,
                        'isSelectedSingle' : this.isSelectedSingle,
                        'isCheckedThirtysixApply' : this.isCheckedThirtysixApply,
                        'isCheckedThirtysixUnapply' : this.isCheckedThirtysixUnapply,
                    }
                }).then(response => {
                    if(response.data.result)
                    {
                        this.target_data_info = [];
                        for(let i = 0; i < response.data.values.length; i++)
                        {
                            this.target_data_info.push({
                                'employee_id': response.data.values[i].employee_id,
                                'employee_code': response.data.values[i].employee_code,
                                'employee_name': response.data.values[i].employee_name,
                                'employee_post': response.data.values[i].post_name,
                                'employee_office': response.data.values[i].office_name,
                                'employee_dept': response.data.values[i].dept_tree,
                                'holiday_work_date': this.serialToDateStr(response.data.values[i].holiday_work_date),
                                'holiday_work_reason': response.data.values[i].holiday_work_reason,
                            });
                        }
                        let data = {
                            target_data_info: this.target_data_info,
                            yearMonth : this.yearMonth,
                            listId : this.listId,
                        };
                        this.$emit('selectedList',data);
                        this.isLoading = false;
                    }
                    else
                    {
                        this.isLoading = false;
                        //取得失敗
                    }
                }).catch((error)=>{
                    this.isLoading = false;
                });
            }else if(this.listId === 3){
                this.listName = '36協定チェック一覧';
                this.yearMonthList = this.getYearMonthListFromMonth(this.yearMonth,this.yearfirstMonth);
                let year_over_time = 0;
                let month_over_time = 0;
                let average = 0;
                let countAverage = 0;
                axios.get('thirtySixCheckList', {
                    //年月を6桁で送信
                    params:{
                        'employeeID' : this.employeeID,
                        'approvalTargetDate' : this.yearMonth,
                        'closeDateId' : this.closeDateId,
                        'SelectedEmployeeIdList' : this.SelectedEmployeeIdList,
                        'isSelectedSingle' : this.isSelectedSingle,
                        'yearMonthList' : this.yearMonthList,
                        'isCheckedThirtysixApply' : this.isCheckedThirtysixApply,
                        'isCheckedThirtysixUnapply' : this.isCheckedThirtysixUnapply,
                    }
                }).then(response => {
                    if(response.data.result)
                    {
                        this.target_data_info = [];
                        for(let i = 0; i < response.data.values.length; i++)
                        {
                            year_over_time = 0;
                            countAverage = 0;
                            average = 0;
                            for(let j = 0; j < response.data.values[i].over_time_year.length; j++)
                            {
                                if(response.data.values[i].over_time_year[j].month == this.yearMonth){
                                    month_over_time = response.data.values[i].over_time_year[j].holiday_work_time;
                                }
                                year_over_time += response.data.values[i].over_time_year[j].holiday_work_time;
                            }
                            for(let j = 0; j < response.data.values[i].over_time_six_month.length; j++)
                            {
                                countAverage += response.data.values[i].over_time_six_month[j].holiday_work_time;
                                if(j == 0){
                                    continue;
                                }
                                average = countAverage / (j + 1) > average ? (countAverage / (j + 1)) : average;
                            }
                            let target_month_check = '―';
                            let six_month_check = '―';
                            let target_year_check = '―';

                            let target_month_background_class = 'background_white';
                            let six_month_background_class = 'background_white';
                            let target_year_background_class = 'background_white';
                            let four_week_background_class = 'background_white';

                            if(month_over_time > 80 * 60){
                                target_month_check = '80時間超';
                                target_month_background_class = 'background_red';
                            }else if(month_over_time > 60 * 60){
                                target_month_check = '60時間超';
                                target_month_background_class = 'background_orange';
                            }else if(month_over_time > 45 * 60){
                                target_month_check = '45時間超';
                                target_month_background_class = 'background_yellow';
                            }else if(month_over_time > 20 * 60){
                                target_month_check = '20時間超';
                                target_month_background_class = 'background_green';
                            }else{
                                target_month_check = '―';
                                target_month_background_class = 'background_white';
                            }
                            if(average > 80 * 60){
                                six_month_check = '80時間超';
                                six_month_background_class = 'background_red';
                            }else if(average > 60 * 60){
                                six_month_check = '60時間超';
                                six_month_background_class = 'background_orange';
                            }else if(average > 45 * 60){
                                six_month_check = '45時間超';
                                six_month_background_class = 'background_yellow';
                            }else if(average > 20 * 60){
                                six_month_check = '20時間超';
                                six_month_background_class = 'background_green';
                            }else{
                                six_month_check = '―';
                                six_month_background_class = 'background_white';
                            }

                            if(year_over_time > 720 * 60){
                                target_year_check = '720時間超';
                                target_year_background_class = 'background_red';
                            }else if(year_over_time > 540 * 60){
                                target_year_check = '540時間超';
                                target_year_background_class = 'background_orange';
                            }else if(year_over_time > 360 * 60){
                                target_year_check = '360時間超';
                                target_year_background_class = 'background_yellow';
                            }else{
                                target_year_check = '―';
                                target_year_background_class = 'background_white';
                            }

                            if(response.data.values[i].four_week_count < 4){
                                four_week_background_class = 'background_red';
                            }else{
                                four_week_background_class = 'background_white';
                            }

                            this.target_data_info.push({
                                'target_month_background_class': target_month_background_class,
                                'six_month_background_class': six_month_background_class,
                                'target_year_background_class': target_year_background_class,
                                'four_week_background_class': four_week_background_class,
                                'employee_id': response.data.values[i].employee_id,
                                'employee_code': response.data.values[i].employee_code,
                                'employee_name': response.data.values[i].employee_name,
                                'employee_post': response.data.values[i].post_name,
                                'employee_office': response.data.values[i].office_name,
                                'employee_dept': response.data.values[i].dept_tree,
                                'month1': this.serialToHoursStr(response.data.values[i].over_time_six_month[0].holiday_work_time),
                                'month2': this.serialToHoursStr(response.data.values[i].over_time_six_month[1].holiday_work_time),
                                'month3': this.serialToHoursStr(response.data.values[i].over_time_six_month[2].holiday_work_time),
                                'month4': this.serialToHoursStr(response.data.values[i].over_time_six_month[3].holiday_work_time),
                                'month5': this.serialToHoursStr(response.data.values[i].over_time_six_month[4].holiday_work_time),
                                'month6': this.serialToHoursStr(response.data.values[i].over_time_six_month[5].holiday_work_time),
                                'target_month_check': target_month_check,
                                'six_month_check': six_month_check,
                                'target_year_check': target_year_check,
                                'four_week_check': String(response.data.values[i].four_week_count) + '日',
                            });
                        }
                        let data = {
                            target_data_info: this.target_data_info,
                            yearMonth : this.yearMonth,
                            listId : this.listId,
                        };
                        this.$emit('selectedList',data);
                        this.isLoading = false;
                    }
                    else
                    {
                        this.isLoading = false;
                        //取得失敗
                    }
                }).catch((error)=>{
                    this.isLoading = false;
                });
            }else if(this.listId === 4){
                this.listName = '有休・休日条件一覧';
                axios.get('acquiredAndHolidayList', {
                    //年月を6桁で送信
                    params:{
                        'employeeID' : this.employeeID,
                        'approvalTargetDate' : this.yearMonth,
                        'closeDateId' : this.closeDateId,
                        'SelectedEmployeeIdList' : this.SelectedEmployeeIdList,
                        'isSelectedSingle' : this.isSelectedSingle,
                        'isCheckedThirtysixApply' : this.isCheckedThirtysixApply,
                        'isCheckedThirtysixUnapply' : this.isCheckedThirtysixUnapply,
                    }
                }).then(response => {
                    if(response.data.result)
                    {
                        this.target_data_info = [];
                        for(let i = 0; i < response.data.values.length; i++)
                        {

                            //振替休日年月日が入力されているが、振替休日取得年月日が入力されていない日数をカウントし「未取得の振替休日」とする
                            //振替休日年月日が入力されていない日数をカウントし「未取得の振替休日」とする
                            const target_end_serial = response.data.values[i].target_end_serial;
                            var wk_unacquired_substitute_holiday = 0;
                            for(let j = 0; j < response.data.values[i].substitute_until_this_month_info.length; j++)
                            {
                                if( (response.data.values[i].substitute_until_this_month_info[j].substitute_holiday_date === 0)
                                || (response.data.values[i].substitute_until_this_month_info[j].substitute_holiday_date > target_end_serial)){
                                    wk_unacquired_substitute_holiday++;
                                }
                            }

                            this.target_data_info.push({
                                'employee_id': response.data.values[i].employee_id,
                                'employee_code': response.data.values[i].employee_code,
                                'employee_name': response.data.values[i].employee_name,
                                'employee_post': response.data.values[i].post_name,
                                'employee_office': response.data.values[i].office_name,
                                'employee_dept': response.data.values[i].dept_tree,
                                'acquired_paid_leave_days': response.data.values[i].acquired_paid_leave_days,
                                'obligatory_take_paid_leave_days': response.data.values[i].obligatory_take_paid_leave_days == 0 || response.data.values[i].manegement_target_class == 1 ? '対象外' : (response.data.values[i].acquired_paid_leave_days - response.data.values[i].obligatory_take_paid_leave_days >= 0 ? '達成' :　String(response.data.values[i].obligatory_take_paid_leave_days - response.data.values[i].acquired_paid_leave_days) + '日' + this.serialToDateStr(response.data.values[i].paid_leave_date_end_serial) + 'まで'),
                                'unused_substitute_information_days': String(wk_unacquired_substitute_holiday),
                            });
                        }
                        let data = {
                            target_data_info: this.target_data_info,
                            yearMonth : this.yearMonth,
                            listId : this.listId,
                        };
                        this.$emit('selectedList',data);
                        this.isLoading = false;
                    }
                    else
                    {
                        this.isLoading = false;
                        //取得失敗
                    }
                }).catch((error)=>{
                    this.isLoading = false;
                });
            }
        },
        onCancel() {
            //Loading画面のキャンセル
        }
    },
    computed:{
        searchText: function(){
            return this.isSelectedSingle == 1? '一覧表示' : '対象検索';
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
                    this.employeeID = Number(value);
                }
            }
        },
        is_selected_single: { // 外からプロパティの中身が変更になったら実行される
            immediate: true,
            handler(value) {
                this.isSelectedSingle = Number(value);
            }
        },
        is_enable_select_office: { // 外からプロパティの中身が変更になったら実行される
            immediate: true,
            handler(value) {
                this.isEnableSelectOffice = value;
            }
        },
        office_id: { // 外からプロパティの中身が変更になったら実行される
            immediate: true,
            handler(value) {
                this.officeId = Number(value);
            }
        },

    },
}

</script>