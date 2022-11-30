<template>
    <div id="C022-01" class="container-fluid p-3 h-100 w-100">
        <loading :active.sync="isLoading" :can-cancel="false" :on-cancel="onCancel" :is-full-page="fullPage"></loading> 
        <div id="C022-01-01" class="container-fluid p-4 h-100 w-100 shadow-sm board">
            <div class="row">
                <div class="px-2">
                    <div id="C022-01-01-01" class="text-left">
                        事業所締め処理
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="p-2 col-12 text-left">
                    <div id="C022-01-01-02" class="card shadow h-100" style="background-color:#BCD2EE;">
                        <div class="card-body">
                            <div id="C022-01-01-02-001" style="color:#000000;margin-top:5pt;display:inline-block">
                                締め年月
                            </div>
                            <div id="C022-01-01-02-002" class="text-center" style="color:#000000;display:inline-block">
                                <select class="form-control" v-model="targetYearMonth" v-on:change="onChangeCloseClass">
                                    <option v-for="option in closeYearMonthOptions" :key="option.value" v-bind:value="option.value">
                                        {{option.text}}
                                    </option>
                                </select>
                            </div>
                            <div id="C022-01-01-02-003" style="color:#000000;margin-top:5pt;margin-left:10pt;display:inline-block">
                                締め区分
                            </div>
                            <div id="C022-01-01-02-004" class="text-center" style="color:#000000;display:inline-block">
                                <select class="form-control" v-model="closeDateId" v-on:change="onChangeCloseClass">
                                    <option value="1">月末締め</option>
                                    <option value="2">15日締め</option>
                                </select>
                            </div>
                            <div id="C022-01-01-02-005" style="color:#000000;margin-top:5pt;margin-left:10pt;display:inline-block">
                                事業所
                            </div>
                            <div id="C022-01-01-02-006" class="text-center" style="color:#000000;display:inline-block">
                                <select style="border:none;" class="form-control" v-on:change="onChangeCloseClass" v-model="selectedOfficeId" v-bind:disabled="isSelectOffice">
                                    <option v-for="option in officeList" :key="option.office_id" v-bind:value="option.office_id">{{ option.office_name }}</option>
                                </select>
                            </div>
                            <div style="margin-left:200pt">
                                <div id="C022-01-01-02-007" class="card-text" style="font-size:8pt;display:inline-block">対象期間：</div>
                                <div id="C022-01-01-02-008" class="card-text" style="font-size:8pt;display:inline-block">{{targetDateTermStart}}～{{targetDateTermEnd}}</div>
                            </div>
                            <div class="mt-2" style="text-align:right">
                                <button id="C022-01-01-02-009" class="btn btn-danger float-right ml-5" v-on:click="openModal('m112_common_message', '', modalOptionForced)" v-bind:disabled="!isEnableForcedClose">強制締め実行</button>
                                <button id="C022-01-01-02-010" class="btn float-right ml-5" :class="changeButtonTypeClass" v-on:click="onClickClose(false)" :disabled="!isEnableClose">{{closeButtonName}}</button>
                                <button id="C022-01-01-02-011" class="btn btn-primary float-right" v-on:click="onClickCheck">社員締め状態確認</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-5" v-if="isSelectedTarget">
                <div class="row">
                    <div class="p-2 col-12 text-center">
                        <div id="C022-01-01-03" class="card shadow h-100" style="background-color:#BCD2EE;">
                            <div class="card-body" v-if="employee_closing_status.length">
                                <div id="C022-01-01-03-001" class="card-title text-left">締め状況一覧</div>
                                <table class="table-white" style="margin-top:20pt;font-size:12pt;">
                                    <thead>
                                        <tr>
                                            <th>社員番号</th>
                                            <th>名前</th>
                                            <th>役職</th>
                                            <th>所属</th>
                                            <th>
                                                <select style="border:none;">
                                                    <option>本人締め</option>
                                                </select>
                                            </th>
                                            <th>
                                                <select style="border:none;">
                                                    <option>管理者締め</option>
                                                </select>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="item in employee_closing_status" v-bind:key="item.employee_id">
                                            <td>{{item.employee_code}}</td>
                                            <td>{{item.employee_name}}</td>
                                            <td>{{item.post_name}}</td>
                                            <td>{{item.office_dept_names}}</td>
                                            <td>{{item.himself_close}}</td>
                                            <td>{{item.manager_close}}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div v-if="!employee_closing_status.length" class="text-center" style="background-color:#ffffff;font-size:15pt;color:#000000;line-height: 46px;">対象の社員がいません</div>
                        </div>
                    </div>
                </div>
            </div>
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
            isSelectedTarget: false,
            employeeID: 0,
            closeDateId: 1, //月末締め
            employee_closing_status: [],
            isEnableClose: false,
            targetYearMonth: 0,
            officeList: [],
            selectedOfficeId: 0,
            modalOptionError: {
                message: '',
                buttons:[{
                        exec : ()=>{
                            this.btn="OK"; //ボタンが押された時の処理をここに記載
                            //モーダルを閉じる
                            $('body').removeClass('modal-open');
                            this.cleanModal();
                            $('.modal-backdrop').remove();
                            this.isEnableForcedClose = true;
                        },
                        caption : "OK",  //'OK','はい','いいえ','キャンセル','再試行','閉じる'
                        btnclass : "btn-success"
                    }],
            },
            modalOptionSuccess: {
                message: '',
                buttons:[{
                        exec : ()=>{
                            this.btn="OK"; //ボタンが押された時の処理をここに記載
                            //モーダルを閉じる
                            $('body').removeClass('modal-open');
                            this.cleanModal();
                            $('.modal-backdrop').remove();
                        },
                        caption : "OK",  //'OK','はい','いいえ','キャンセル','再試行','閉じる'
                        btnclass : "btn-success"
                    }],
            },
            modalOptionForced: {
                message: '強制締めを実施しますか？',
                buttons:[{
                        exec : ()=>{
                            this.btn="はい"; //ボタンが押された時の処理をここに記載
                            //モーダルを閉じる
                            $('body').removeClass('modal-open');
                            this.cleanModal();
                            $('.modal-backdrop').remove();
                            this.onClickClose(true);
                        },
                        caption : "はい",  //'OK','はい','いいえ','キャンセル','再試行','閉じる'
                        btnclass : "btn-primary"
                    },
                    {
                        exec : ()=>{
                            this.btn="いいえ"; //ボタンが押された時の処理をここに記載
                            //モーダルを閉じる
                            $('body').removeClass('modal-open');
                            this.cleanModal();
                            $('.modal-backdrop').remove();
                        },
                        caption : "いいえ",  //'OK','はい','いいえ','キャンセル','再試行','閉じる'
                        btnclass : "btn-danger"
                    }],
            },
            modalOptioncheckedHoliday: {
                message: '',
                buttons:[{
                    exec : ()=>{
                        this.btn="OK"; //ボタンが押された時の処理をここに記載
                        //モーダルを閉じる
                        $('body').removeClass('modal-open');
                        this.cleanModal();
                        $('.modal-backdrop').remove();
                    },
                    caption : "OK",  //'OK','はい','いいえ','キャンセル','再試行','閉じる'
                    btnclass : "btn-success"
                }],
            },
            isEnableForcedClose: false,
            is_forced_close: false,
            isLoading: false,  //ローディング画面用プロパティ
            fullPage: true,    //ローディング画面用プロパティ
            absentInfoList: [],
            employeeCode: [],
            employeeCodeMsg: '',
        };
    },
    mounted(){
        //初期選択
        this.targetYearMonth = this.lastYearMonth;
        //事業所初期選択
        this.selectedOfficeId = this.session_data.office_id;
        //対象期間取得
        this.getOfficeClosingStatus();
        //所属マスタ
        const officeData =  this.getMasterData().office;
        for(let i = 0; i < officeData.length; i++)
        {
            this.officeList.push({
                'office_id': officeData[i].office_id,
                'office_name': officeData[i].office_name,
            });
        }
    },
    methods:{
        //初期化メソッド（親から呼ばれる）
        initialize()
        {
            this.isSelectedTarget = false; //　要実装　一覧を閉じる？
        },
        exitInput()
        {
            this.initialize();
            this.isSelectedTarget = false;
        },
        onClickCheck(){
            this.isLoading = true;
            this.isSelectedTarget = true;
            this.getEmployeeClosingStatus();
            this.getOfficeClosingStatus();
        },
        getOfficeClosingStatus(){
            axios.get('getOfficeClosingStatus', {
                //年月を6桁で送信
                params:{
                    'targetDate' : this.targetYearMonth,
                    'closeDateId' : this.closeDateId,
                    'officeId' : this.selectedOfficeId,
                }
            }).then(response => {
                //事業所締め済み、取得失敗の場合は締めボタン押せない
                this.isEnableClose = response.data.result && 0 < response.data.values.length && response.data.values[0].closing_status_class <= 3;
            })
        },
        async getEmployeeClosingStatus(){
            await axios.get('getEmployeeClosingStatus', {
                //年月を6桁で送信
                params:{
                    'targetDate' : this.targetYearMonth,
                    'closeDateId' : this.closeDateId,
                    'employeeID' : this.employeeID,
                    'officeId' : this.selectedOfficeId,
                    'companyId' : this.session_data.company_id,
                }
            }).then(response => {
                if(response.data.result)
                {
                    this.employee_closing_status = [];
                    for(let i = 0; i < response.data.values.length; i++)
                    {
                        var himself_close = '';
                        var manager_close = '';

                        if(response.data.values[i].close_state_id > 2){
                            himself_close = '済み';
                            manager_close = '済み';
                        }
                        else if(response.data.values[i].close_state_id === 2){
                            himself_close = '済み';
                            manager_close = '未';
                        }
                        else{
                            himself_close = '未';
                            manager_close = '未';
                        }

                        this.employee_closing_status.push({
                            'employee_id': response.data.values[i].employee_id,
                            'employee_code': response.data.values[i].employee_code,
                            'employee_name': response.data.values[i].employee_name,
                            'post_name': response.data.values[i].post_name,
                            'office_dept_names': response.data.values[i].office_dept_names,
                            'himself_close': himself_close,
                            'manager_close': manager_close,
                        });
                    }
                }
                else
                {
                    //取得失敗
                }
                this.isLoading = false;
            })
        },
        async getAbsentInfoList(){

            this.employeeCode = [];

            var employeeIdList = new Array();
            // 従業員IDを配列に詰め直す
            if(this.employee_closing_status.length > 0) {
                for(let i = 0 ; i < this.employee_closing_status.length ; i++){
                    employeeIdList.push(this.employee_closing_status[i].employee_id);
                }
                await axios.get('getAbsentInfoList', {
                    params:{
                        'targetdate' : this.targetYearMonth,
                        'employeeInfo' : employeeIdList,
                    }
                }).then(response => {
                    this.absentInfoList = [];
                    if(response.data.result){
                        for(let i =0 ; i < response.data.values.length ; i++){
                            if(response.data.values[i].target_acquired_holidays != null){
                                var remainingHolidayDays = 0;
                                var remainingHolidayHalfDays = 0;
                                for(let j =0 ; j < response.data.values[i].target_acquired_holidays.length ;j++){
                                    remainingHolidayDays = remainingHolidayDays + response.data.values[i].target_acquired_holidays[j].remaining_holiday_days;
                                    remainingHolidayHalfDays = remainingHolidayHalfDays + response.data.values[i].target_acquired_holidays[j].remaining_holiday_half_days;
                                }
                            }
                            if(response.data.values[i].target_accumulated_holidays != null){
                                var unusedAccumulatedPaidLeaveDays = 0;
                                var unusedAccumulatedPaidLeaveHalfDays = 0;
                                for(let j =0 ; j < response.data.values[i].target_accumulated_holidays.length ; j++){
                                    unusedAccumulatedPaidLeaveDays = unusedAccumulatedPaidLeaveDays + response.data.values[i].target_accumulated_holidays[j].remaining_holiday_days;
                                    unusedAccumulatedPaidLeaveHalfDays = unusedAccumulatedPaidLeaveHalfDays + response.data.values[i].target_accumulated_holidays[j].remaining_holiday_half_days;
                                }
                            }
                            this.absentInfoList.push({
                                'employee_id' : response.data.values[i].employee_id,
                                'employee_code' : response.data.values[i].employee_code,
                                'remaining_holiday_days' : remainingHolidayDays,
                                'remaining_holiday_half_days' : remainingHolidayHalfDays,
                                'unused_accumulated_paid_leave_days' : unusedAccumulatedPaidLeaveDays,
                                'unused_accumulated_paid_leave_half_days' : unusedAccumulatedPaidLeaveHalfDays,
                                'holiday_days_count' : response.data.values[i].holiday_count,
                                'holiday_half_days_count' : response.data.values[i].holiday_half_count,
                                'accumulated_holiday_count' : response.data.values[i].accumulated_holiday_count,
                                'accumulated_holiday_half_count' : response.data.values[i].accumulated_holiday_half_count,
                            });
                        }
                        // 対象者の有休残チェックを実施する
                        for(let i =0; i < this.absentInfoList.length; i++){
                            let remainingHolidayDayFlg = this.absentInfoList[i].remaining_holiday_days + (this.absentInfoList[i].remaining_holiday_half_days / 2) >= this.absentInfoList[i].holiday_days_count + (this.absentInfoList[i].holiday_half_days_count / 2);
                            let unusedAccumulatedPaidLeaveDayFlg = this.absentInfoList[i].unused_accumulated_paid_leave_days + (this.absentInfoList[i].unused_accumulated_paid_leave_half_days / 2) >= this.absentInfoList[i].accumulated_holiday_count + (this.absentInfoList[i].accumulated_holiday_half_count / 2);
                            if(!(remainingHolidayDayFlg && unusedAccumulatedPaidLeaveDayFlg)){
                                // 従業員コードを退避
                                this.employeeCode.push(this.absentInfoList[i].employee_code);
                            }
                        }

                    }else{
                        //取得失敗
                        this.isEnableClose = true;
                        this.modalOptionError.message = response.data.values.message;
                        this.openModal("m112_common_message", "", this.modalOptionError);
                    }

                }).catch(error => {
                    console.log(error.response);
                });
            }
        },
        onChangeCloseClass(){
            this.isSelectedTarget = false;
            this.getOfficeClosingStatus();
        },
        async onClickClose(is_forced_close){
            this.isLoading = true;

            let remainingPaidHolidayFlg = true;

            await this.getEmployeeClosingStatus();

            await this.getAbsentInfoList();

            //有休残チェック対象者全員問題なし
            if(!this.employeeCode.length){
                 await axios.get('updateOfficeClosingStatus', {
                    //年月を6桁で送信
                    params:{
                        'targetDate' : this.targetYearMonth,
                        'closeDateId' : this.closeDateId,
                        'officeId' : this.selectedOfficeId,
                        'companyId' : this.session_data.company_id,
                        'toState' : 4, //事業所締め
                        'is_forced_close' : is_forced_close,
                    }
                }).then(response => {
                    if(response.data.result)
                    {
                        //事業所締め済みの場合は締めボタン押せない
                        this.isEnableClose = false;
                        this.isEnableForcedClose = false;
                        this.modalOptionSuccess.message = response.data.values.message;
                        this.openModal("m112_common_message", "", this.modalOptionSuccess);
                    }
                    else
                    {
                        //取得失敗
                        this.isEnableClose = true;
                        this.modalOptionError.message = response.data.values.message;
                        this.openModal("m112_common_message", "", this.modalOptionError);
                    }
                    //ローディング画面隠す
                    this.isLoading = false;
                }).catch(error => {
                    console.log(error.response);
                    this.isLoading = false;
                });
            }
            // 有休残チェック不足
            else
            {

                this.employeeCodeMsg = "従業員コード：";
                this.employeeCodeMsg = this.employeeCodeMsg + this.employeeCode.join("、\n従業員コード：");

                var message = "有休数エラー。有休申請数が有休残数を超えないことを確認してください。"

                this.modalOptioncheckedHoliday.message = message + this.employeeCodeMsg;

                //有休の数が有休残数を超えた場合
                this.openModal("m112_common_message", "", this.modalOptioncheckedHoliday);
                //ローディング画面隠す
                this.isLoading = false;
            }
        },
        onCancel() {
            //Loading画面のキャンセル
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
    },
    computed:{
        changeButtonTypeClass(){
            return this.isEnableClose ? "btn-danger" : "btn-secondary";
        },
        closeButtonName(){
            return this.isEnableClose ? "事業所締め実行" : "事業所締め済み";
        },
        thisYearMonth(){
            return this.serialToDateStr(this.todaySerial(), "YYYYMM");
        },
        lastYearMonth(){
            return this.calcYearMonth(this.serialToDateStr(this.todaySerial(), "YYYYMM"), -1);
        },
        thisYearMonthStr(){
            return this.serialToDateStr(this.todaySerial(), "YYYY年MM月");
        },
        lastYearMonthStr(){
            return this.calcYearMonth(this.serialToDateStr(this.todaySerial(), "YYYYMM"), -1, "YYYY年MM月");
        },
        closeYearMonthOptions(){
            return [
                {value: this.thisYearMonth, text: this.thisYearMonthStr},
                {value: this.lastYearMonth, text: this.lastYearMonthStr},
            ];
        },
        targetDateTermStart(){
            return this.targetYearMonth == 0 ? 0 : this.serialToDateStr(this.getCloseDateTerm(this.targetYearMonth, this.closeDateId)?.target_term_start, "YYYY/MM/DD");
        },
        targetDateTermEnd(){
            return this.targetYearMonth == 0 ? 0 : this.serialToDateStr(this.getCloseDateTerm(this.targetYearMonth, this.closeDateId)?.target_term_end, "YYYY/MM/DD");
        },
        isSelectOffice:function(){
            return !(this.session_data.authority_pattern.general_affairs_closing);
        },
    },
}
</script>
