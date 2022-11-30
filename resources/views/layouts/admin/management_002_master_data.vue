<template>
    <div id="loginCheckMenu" style="font-family: 'Noto Sans JP', sans-serif;">
        <div class="container-fluid h-100" style="min-height:600pt">
            <!-- Nav pills -->
            <div class="row ml-5 mt-5" style="min-width:800pt;height:50pt">
                <div class="col-sm-1 text-center" style="width:150pt;">
                    <label class="modal-form-label">会社ID<br>指定</label>
                </div>
                <div class="col-sm-2" style="width:150pt;">
                    <input type="text" v-model="companyIdInput" class="form-control" placeholder="会社ID">
                </div>
            </div>
            <div class="row ml-5 mt-5" style="min-width:800pt;height:50pt">
                <div class="col-sm-2" style="width:150pt;">
                    <button style="font-size:11pt;width:130pt" class="btn btn-primary" v-on:click="resetPasswordAll()">パスワード一斉初期化</button>
                    <VueJsonToCsv :json-data="csvData" :csv-title="csvFileName" :labels="labelBuff">
                        <button id="doDownload" style="display: none;" v-on:click="download()"></button>
                    </VueJsonToCsv>
                </div>
                <div class="col-sm-4" style="width:150pt;">
                    ※連続してパスワード一斉初期化する場合は画面を更新してください。
                </div>
            </div>
            <div class="row ml-5 mt-5" style="min-width:800pt;height:50pt">
                <div class="col-sm-2" style="width:150pt;">
                    <button style="font-size:11pt;width:130pt" class="btn btn-primary" v-on:click="resetPasswordUser()">パスワード初期化</button>
                </div>
                <div class="col-sm-1 text-center" style="width:150pt;">
                    <label class="modal-form-label">社員コード<br>指定</label>
                </div>
                <div class="col-sm-2" style="width:150pt;">
                    <input type="text" v-model="employeeCodeInput" class="form-control" placeholder="社員コード">
                </div>
                <div class="col-sm-1 text-center" style="width:100pt;">
                    <label class="modal-form-label">社員ID<br>指定</label>
                </div>
                <div class="col-sm-2" style="width:150pt;">
                    <input type="text" v-model="employeeIdInput" class="form-control" placeholder="社員ID">
                </div>
                <div class="col-sm-1 text-center" style="width:100pt;">
                    <label class="modal-form-label">パスワード<br>指定</label>
                </div>
                <div class="col-sm-2" style="width:150pt;">
                    <input type="text" v-model="employeePassword" class="form-control" placeholder="パスワードを指定">
                </div>
            </div>
            <div class="row ml-5 mt-5" style="min-width:800pt;height:50pt">
                <div class="col-sm-2" style="width:150pt;">
                    <button style="font-size:11pt;width:130pt" class="btn btn-primary" v-on:click="createAttendanceTable()">勤務テーブル作成</button>
                </div>
                <div class="col-sm-1 text-center" style="width:150pt;">
                    <label class="modal-form-label">対象年月</label>
                </div>
                <div class="col-sm-2" style="width:150pt;">
                    <input type="text" v-model="targetYeraMonth" class="form-control" placeholder="期間開始">
                </div>
                <div class="col-sm-1 text-center" style="width:150pt;">
                    <select class="form-control" v-model="selectedCloseDateID">
                        <option value=1>月末締め</option>
                        <option value=2>15日締め</option>
                    </select>
                </div>
            </div>
            <div class="row ml-5 mt-5" style="min-width:800pt;height:50pt">
                <div class="col-sm-2" style="width:150pt;">
                    <button style="font-size:11pt;width:130pt" class="btn btn-primary" v-on:click="createAttendanceTableByEmployeeID()">勤務テーブル作成　（社員ID指定）</button>
                </div>
                <div class="col-sm-1 text-center" style="width:100pt;">
                    <label class="modal-form-label">社員ID<br>指定</label>
                </div>
                <div class="col-sm-2" style="width:150pt;">
                    <input type="text" v-model="catByEIDEmployeeId" class="form-control" placeholder="社員ID">
                </div>
                <div class="col-sm-1 text-center" style="width:150pt;">
                    <label class="modal-form-label">対象年月</label>
                </div>
                <div class="col-sm-2" style="width:150pt;">
                    <input type="text" v-model="catByEIDtargetYeraMonth" class="form-control" placeholder="期間開始">
                </div>
            </div>
            <div class="row ml-5 mt-5" style="min-width:800pt;height:50pt">
                <div class="col-sm-2" style="width:150pt;">
                    <button style="font-size:11pt;width:130pt" class="btn btn-primary" v-on:click="aggregate_attendance()">T003集計</button>
                </div>
                <div class="col-sm-1 text-center" style="width:150pt;">
                    <label class="modal-form-label">年月</label>
                </div>
                <div class="col-sm-2" style="width:150pt;">
                    <div class="text-center" style="color:#000000;display:inline-block">
                        <vuejsDatepicker :format="datePickerFormat" :language="datePickerLanguage" minimum-view="month" :disabled-dates="datePickerDisabledDates" class="dp-form-control" v-model="defaultDate"></vuejsDatepicker>
                    </div>
                </div>
                <div class="col-sm-1 text-center" style="width:150pt;">
                </div>
                <div class="col-sm-2" style="width:150pt;">
                </div>
            </div>
            <div class="row ml-5 mt-5" style="min-width:800pt;height:50pt">
                <div class="col-sm-2" style="width:150pt;">
                    <button style="font-size:11pt;width:130pt" class="btn btn-primary" v-on:click="aggregate_attendance_byid()">T003集計(社員ID指定)</button>
                </div>
                <div class="col-sm-1 text-center" style="width:150pt;">
                    <label class="modal-form-label">年月</label>
                </div>
                <div class="col-sm-2" style="width:150pt;">
                    <div class="text-center" style="color:#000000;display:inline-block">
                        <vuejsDatepicker :format="datePickerFormat" :language="datePickerLanguage" minimum-view="month" :disabled-dates="datePickerDisabledDates" class="dp-form-control" v-model="aggregateTargetYearMonth"></vuejsDatepicker>
                    </div>
                </div>
                <div class="col-sm-1 text-center" style="width:150pt;">
                    <label class="modal-form-label">社員ID<br>指定</label>
                </div>
                <div class="col-sm-2" style="width:150pt;">
                    <input type="text" v-model="aggregateEmployeeId" class="form-control" placeholder="社員ID">
                </div>
            </div>
            <div v-if="isLoading" style="text-align: center;">
                <button style="font-size:11pt;width:130pt" class="btn btn-danger" v-on:click="cancelCreateAttendanceTable">キャンセル</button>
            </div>
            <div class="loader" v-if="isLoading">Loading...</div>
        </div>
    </div>
</template>

<script>
import vuejsDatepicker from 'vuejs-datepicker'
import VueJsonToCsv from 'vue-json-to-csv'
export default {
    components: {
        VueJsonToCsv,
        vuejsDatepicker,
    },
    props: {

    },
    data() {
        return {
            isLoading: false,
            companyIdInput: '',
            employeeIdInput: '',
            employeeCodeInput: '',
            employeePassword: '',
            targetYeraMonth: '',
            selectedCloseDateID: '',
            modalOption: {
                message: '',
                buttons:[　//【暫定】どのボタン指定するかは呼び出し元などから決定し指定する
                    {
                        exec : ()=>{
                            this.btn="OK"; //ボタンが押された時の処理をここに記載
                            //M107モーダルを閉じる
                            this.cleanModal();
                            $('.modal-backdrop').remove();
                        },
                        caption : "OK",  //'OK','はい','いいえ','キャンセル','再試行','閉じる'
                        btnclass : "btn-success"
                    }],
            },
            csvData: [],
            recieved: [],
            labelBuff: {
                employee_id: {title: 'employee_id'},
                employee_code: {title: 'employee_code'},
                password: {title: 'password'}
            },
            csvFileName: "password.csv",
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
            defaultDate: new Date(),
            jobStateID: 0,
            catByEIDEmployeeId: 0,
            catByEIDtargetYeraMonth: "",
            aggregateEmployeeId: 0,
            aggregateTargetYearMonth: new Date(),
            isContinue: false,
        };
    },
    methods: {
        resetPasswordAll() {
            if(!this.companyIdInput) {
                this.modalOption.message = '会社IDを入力してください';
                this.openModal("m112_common_message", "", this.modalOption);
                return;
            }
            if(!confirm("指定された会社IDの全ユーザーのパスワードをリセットします。よろしいですか？"))
            {
                return;
            }
            this.isLoading = true;
            this.validateMessage = null;
            axios.post('/admin/reset_password_all', {
                companyIdInput: this.companyIdInput
            }).then(response => {
                if(response.data.result)
                {
                    this.modalOption.message = 'パスワードの初期化開始しました';
                    this.openModal("m112_common_message", "", this.modalOption);
                    this.jobStateID = response.data.values.job_state_id;
                    this.startCheckJobState(response.data.values.job_state_id, 1, 1);
                }
                else
                {
                    this.modalOption.message = 'パスワードの初期化失敗しました';
                    this.modalOption.message = response.data.values.message;
                    this.openModal("m112_common_message", "", this.modalOption);
                }
            })
            .catch(function(error){
                //何らかのエラー
                console.log(error.response.data);
            });
        },

        //
        resetPasswordUser() {
            axios.post('/admin/reset_password_user', {
                employeeCode: this.employeeCodeInput,
                employeeId: this.employeeIdInput,
                password: this.employeePassword,
                companyIdInput: this.companyIdInput
            })
            .then(response => {
                if(response.data.result)
                {
                    //alert('パスワードの初期化が完了しました');
                    //モーダルを開く
                    let displayMessage = '社員ID: ' + response.data.values.message[0].employee_id + ' パスワード [' +  response.data.values.message[0].password + ']で登録しました。';
                    this.modalOption.message = displayMessage;
                    this.openModal("m112_common_message", "", this.modalOption);
                }
                else
                {
                    //alert('パスワードの初期化に失敗しました');
                    //モーダルを開く
                    let displayMessage = '入力データが間違っています。入力データをご確認ください。'
                    this.modalOption.message = displayMessage;
                    this.openModal("m112_common_message", "", this.modalOption);
                }
                return response.data.result;
            })
            .catch(function(error){
                //何らかのエラー
            });
        },

        download(){
            //結果をcsvでダウンロード
            //データ
            this.recieved.forEach((data) => {
                this.csvData.push({
                    employee_id: data.employee_id,
                    employee_code: data.employee_code,
                    password: data.password,
                });
            });
            //データ
            //this.csvBuff = this.recieved;
        },
        //勤務表作成実行
        createAttendanceTable() {
            this.isLoading = true;
            axios.post('/admin/create_attendance_table', {
                closeDateID: this.selectedCloseDateID,
                targetYearMonth: this.targetYeraMonth,
                companyIdInput: this.companyIdInput,
            }).then(response => {
                if(response.data.result)
                {
                    let displayMessage = '処理を開始しました'
                    this.modalOption.message = displayMessage;
                    this.openModal("m112_common_message", "", this.modalOption);
                    const jobStateID = response.data.values.job_state_id;
                    this.jobStateID = jobStateID;
                    this.startCheckJobState(jobStateID);
                }
                else
                {
                    alert("エラー：" + JSON.stringify(response.data.values));
                    this.isLoading = false;
                }
            })
            .catch(error => {
                //何らかのエラー
                console.log(error.response.data);
                this.isLoading = false;
                this.jobStateID = 0;
            });
        },
        createAttendanceTableByEmployeeID(){
            this.isLoading = true;
            axios.post('/admin/create_attendance_table_byeid', {
                targetYearMonth: this.catByEIDtargetYeraMonth,
                employeeID: this.catByEIDEmployeeId,
            }).then(response => {
                if(response.data.result)
                {
                    axios.get('/update_violation_warning_id', {
                        params:{
                            attendanceYearMonth: this.catByEIDtargetYeraMonth,
                            employeeID: this.catByEIDEmployeeId,
                        }
                    }).then(response =>{
                        alert("完了しました (" + response.data.result + ")");
                    });
                }
                else
                {
                    alert("エラー" + response.data.values.val);
                }
                this.isLoading = false;
            })
            .catch(error => {
                //何らかのエラー
                console.log(error.response.data);
                this.isLoading = false;
                this.jobStateID = 0;
            });
        },
        //ジョブの状態確認開始（タイムアウトないので注意）
        async startCheckJobState(jobStateID, csvDownload, type){
            this.isContinue = true;
            let message = "";
            csvDownload = csvDownload !== undefined ? csvDownload : 0;
            type = type !== undefined ? type : 0
            while(this.isContinue)
            {
                await this.sleep(3000);
                let data = await this.getJobState(jobStateID, csvDownload, type);
                if(!data.result) {
                    console.log(data.values.message)
                    this.isContinue = false;
                    return;
                }
                switch(data.values.state)
                {
                    case 0:
                    case 1:
                        //継続中
                        break;
                    case 2:
                       //終了
                        this.isContinue = false;
                        message = "処理が終了しました";
                        //受信バッファへ保持
                        if(csvDownload == 1) {
                            // 現在はパスワード一斉初期化機能のみです
                            this.recieved  = data.values.data;
                            $('#doDownload').click();
                        }
                        break;
                    case 9:
                        //異常終了
                        this.isContinue = false;
                        message = "処理が異常終了しました";
                        break;
                }
            }
            
            if(message != '') {
                this.modalOption.message = message;
                this.openModal("m112_common_message", "", this.modalOption);
            }
          
            this.isLoading = false;
            this.jobStateID = 0;
        },
        //定周期で呼ばれる、ジョブの状態確認
        async getJobState(jobStateID, csvDownload, type)
        {
            return new Promise((resolve, reject) =>{
                axios.get('/admin/getJobState',{
                    params:{
                        'jobStateID' : jobStateID,
                        'csvDownload' : csvDownload,
                        'type': type
                    }
                }).then(response => {
                    resolve(response.data)
                }).catch(err => {
                    reject(err);
                });
            });
        },
        //キャンセル実行
        cancelCreateAttendanceTable(){
            axios.post('/admin/cancelJob', {
                jobStateID : this.jobStateID,
            }).then(response => {
                this.isContinue = false;
                if(response.data.result)
                {
                    let displayMessage = '処理を中断しました'
                    this.modalOption.message = displayMessage;
                    this.openModal("m112_common_message", "", this.modalOption);
                }
                else
                {
                    let displayMessage = '処理の中断に失敗しました'
                    this.modalOption.message = displayMessage;
                    this.openModal("m112_common_message", "", this.modalOption);
                }
                this.isLoading = false;
            }).catch(error => {
                this.isLoading = false;
            });
        },
        async aggregate_attendance(){
            this.isLoading = true;
            let exec_count = 0;
            for(let count = 0; count < 1000; count += 100)
            {
                const result = await this.exec_aggregate_attendance(count, count + 100);
                exec_count += result;
                if(result == 0)
                {
                    break;
                }
            }
            alert("finished!!  exec >> " + exec_count);
            this.isLoading = false;
        },
        async exec_aggregate_attendance(start, end){
            return new Promise((resolve, reject) => {
                axios.post('/admin/aggregate_attendance', {
                        yearMonth : this.serialToDateStr(this.dateStrToSerial(this.defaultDate), "YYYYMM"),
                        companyIdInput: this.companyIdInput,
                        start_count: start,
                        end_count: end,
                }).then(response => {
                    if(response.data.result)
                    {
                        if(0 < response.data.values.failed.length)
                        {
                            alert("exec failed >> employee_id >> " + response.data.values.failed);
                        }
                        resolve(response.data.values.exec_count);
                    }
                });
            });
        },
        aggregate_attendance_byid(){
            axios.post('/admin/aggregate_attendance_byid', {
                yearMonth : this.serialToDateStr(this.dateStrToSerial(this.aggregateTargetYearMonth), "YYYYMM"),
                employeeID: this.aggregateEmployeeId,
            }).then(response => {
                if(response.data.result){
                    alert("完了");
                }
                else{
                    alert("失敗");
                }
            });
        }
    },
    computed:{

    },
    watch: {

    },
    mounted() {
        this.employeeIdInput = '',
        this.employeeCodeInput = '',
        this.employeePassword = ''
    }
}
</script>
