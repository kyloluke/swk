<template>
    <div id="C031-01" class="container-fluid p-3 h-100 w-100">
    <loading :active.sync="isLoading" :can-cancel="false" :on-cancel="onCancel" :is-full-page="fullPage"></loading> 
        <div id="C031-01-01" class="container-fluid py-2 px-4 h-100 w-100 shadow-sm board" style="margin-top:20pt;">
            <div class="row">
                <div class="px-2">
                    <div id="C031-01-01-01" class="text-left">
                        給与連携
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="px-2">
                    <div id="C031-01-01-02" class="text-left text-dark ml-2">
                        期間を指定した、給与連携ファイルの出力を行います。
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-6 text-center">
                    <div id="C031-01-01-03" class="card shadow h-100" style="background-color:#BCD2EE;">
                        <div class="card-body">
                            <div class="card-title text-left">条件指定</div>
                            <div style="color:#000000;margin-top:5pt;display:inline-block">
                                締め年月
                            </div>
                            <div class="text-center" style="color:#000000;display:inline-block">
                                <vuejsDatepicker :format="datePickerFormat" :language="datePickerLanguage" minimum-view="month" :disabled-dates="datePickerDisabledDates" class="dp-form-control" v-model="defaultDate"></vuejsDatepicker>
                            </div>
                            <div style="color:#000000;margin-top:5pt;display:inline-block">
                                締め区分
                            </div>
                            <div class="text-center" style="color:#000000;display:inline-block">
                                <select class="form-control" v-model="closeDateId">
                                    <option value="1">月末締め</option>
                                    <option value="2">15日締め</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="row" style="height:65px"></div>
                    <div class="row">
                        <div class="col-4 d-flex justify-content-end" style="padding-top:12px">給与連携ファイル</div>
                        <div class="col-4" style="font-size:13pt;">
                            <button class="btn btn-primary w-100" v-on:click="downloadSomethings('#C031-01-01-04')">ダウンロード <i class="fas fa-download fa-2x"></i></button>
                            <VueJsonToCsv :json-data="csvBuff" :csv-title="csvFileName" :labels="labelBuff">
                                <button id="C031-01-01-04" v-show="false" v-on:click="getList('C031-01-01-04')"></button>
                            </VueJsonToCsv>
                        </div>
                        <div class="col-4" style="font-size:13pt;">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="C031-01-02" class="container-fluid py-2 px-4 h-100 w-100 shadow-sm board" style="margin-top:20pt;">
            <div class="row">
                <div class="px-2">
                    <div id="C031-01-02-01" class="text-left">
                        社員情報一括登録・修正
                    </div>
                </div>
            </div>
            <div class="row">
                <div id="C031-01-02-02" class="px-2">
                    <div class="text-left text-dark ml-2">
                        社員情報をCSVファイルにより一括登録できます。社員IDが重複した場合、上書きされます。
                    </div>
                    <div class="text-left text-dark ml-2">
                        修正したい基準となる日を指定してダウンロードし、修正した後に期間を指定してアップロードを行ってください。
                    </div>
                    <div class="text-left text-dark ml-2">
                        ※「事業所コード」「事業所名」などのコードと名前がセットになっている項目はコードを修正してください。
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-6 text-center">
                    <div class="card shadow h-100" style="background-color:#BCD2EE;">
                        <div class="card-body">
                            <div class="card-title text-left">条件指定</div>
                            <div class="card-text d-inline-block" style="color:#000000;">有効期限開始（基準日）</div>
                            <input id="C031-01-02-05" v-model="valid_date_start" type="date" style="font-size:15pt" min="1901-01-01"/>
                            <div class="card-text d-inline-block" style="color:#000000;">　有効期限終了</div>
                            <input id="C031-01-02-06" v-model="valid_date_end" type="date" style="font-size:15pt" min="1901-01-01"/>
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="row" style="height:65px"></div>
                    <div class="row">
                        <div class="col-4 d-flex align-items-center justify-content-end" style="padding-top:10px">社員情報CSVファイル</div>
                        <div class="col-4" style="font-size:13pt;">
                            <button class="btn btn-primary w-100" v-on:click="downloadSomethings('#C031-01-02-03')">ダウンロード <i class="fas fa-download fa-2x"></i></button>
                            <VueJsonToCsv :json-data="csvBuff" :csv-title="csvFileName" :labels="labelBuff">
                                <button id="C031-01-02-03" v-show="false" v-on:click="getList('C031-01-02-03')"></button>
                            </VueJsonToCsv>
                        </div>
                        <div class="col-4" style="font-size:13pt;">
                            <input type="file" ref="employeeInformation" v-show="false" accept="text/csv,.csv" v-on:change="selectedFile('employeeInformation')"/>
                            <button id="C031-01-02-04" class="btn btn-primary w-100" v-on:click="openFileDialog('employeeInformation')">アップロード <i class="fas fa-upload fa-2x"></i></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="C031-01-03" class="container-fluid py-2 px-4 h-100 w-100 shadow-sm board" style="margin-top:20pt;">
            <div class="row">
                <div class="px-2">
                    <div id="C031-01-03-01" class="text-left">
                        承認対象者一括登録・修正
                    </div>
                </div>
            </div>
            <div class="row">
                <div id="C031-01-03-02" class="px-2">
                    <div class="text-left text-dark ml-2">
                        承認対象者をCSVファイルにより一括登録できます。基準日以降の既存の承認対象者情報は全て削除され、新しい承認対象者が設定されます。
                    </div>
                    <div class="text-left text-dark ml-2">
                        最新の状態をダウンロードし、修正した後にアップロードを行ってください。
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-4 text-center" >
                    <div class="card shadow h-100" style="background-color:#BCD2EE;">
                        <div class="card-body">
                            <div class="card-title text-left">条件指定</div>
                            <div class="card-text d-inline-block" style="color:#000000;">基準日</div>
                            <input id="C031-01-03-03" v-model="approvedDate" type="date" style="font-size:15pt" min="1901-01-01"/>
                        </div>
                    </div>
                </div>
                <div class="col-2"></div>
                <div class="col-6">
                    <div class="row">
                        <div class="col-4 d-flex align-items-center justify-content-end">未設定一覧</div>
                        <div class="col-4" style="font-size:13pt;">
                            <button class="btn btn-primary w-100" v-on:click="downloadSomethings('#C031-01-03-04')">ダウンロード <i class="fas fa-download fa-2x"></i></button>
                            <VueJsonToCsv :json-data="csvBuff" :csv-title="csvFileName" :labels="labelBuff">
                                <button id="C031-01-03-04" v-show="false" v-on:click="getList('C031-01-03-04')"></button>
                            </VueJsonToCsv>
                        </div>
                        <div class="col-4" style="font-size:13pt;">
                        </div>
                    </div>
                    <div class="row pt-3">
                        <div class="col-4 d-flex align-items-center justify-content-end">承認対象者CSVファイル</div>
                        <div class="col-4" style="font-size:13pt;">
                            <button class="btn btn-primary w-100" v-on:click="downloadSomethings('#C031-01-03-05')">ダウンロード <i class="fas fa-download fa-2x"></i></button>
                            <VueJsonToCsv :json-data="csvBuff" :csv-title="csvFileName" :labels="labelBuff">
                                <button id="C031-01-03-05" v-show="false" v-on:click="getList('C031-01-03-05')"></button>
                            </VueJsonToCsv>
                        </div>
                        <div class="col-4" style="font-size:13pt;">
                            <input type="file" ref="inputApprover" v-show="false" accept="text/csv,.csv" v-on:change="selectedFile('inputApprover')"/>
                            <button id="C031-01-03-06" class="btn btn-primary w-100" v-on:click="openFileDialog('inputApprover')">アップロード <i class="fas fa-upload fa-2x"></i></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="C031-01-04" class="container-fluid py-2 px-4 h-100 w-100 shadow-sm board" style="margin-top:20pt;">
            <div class="row">
                <div class="px-2">
                    <div id="C031-01-04-01" class="text-left">
                        代理入力者一括登録・修正
                    </div>
                </div>
            </div>
            <div class="row">
                <div id="C031-01-04-02" class="px-2">
                    <div class="text-left text-dark ml-2">
                        代理入力者をCSVファイルにより一括登録できます。基準日以降の既存の代理入力者情報は全て削除され、新しい承認対象者が設定されます。
                    </div>
                    <div class="text-left text-dark ml-2">
                        最新の状態をダウンロードし、修正した後にアップロードを行ってください。
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-4 text-center">
                    <div class="card shadow h-100" style="background-color:#BCD2EE;">
                        <div class="card-body">
                            <div class="card-title text-left">条件指定</div>
                            <div class="card-text d-inline-block" style="color:#000000;">基準日</div>
                            <input id="C031-01-04-03" v-model="agentDate" type="date" style="font-size:15pt" min="1901-01-01"/>
                        </div>
                    </div>
                </div>
                <div class="col-2"></div>
                <div class="col-6">
                    <div class="row">
                        <div class="col-4 d-flex align-items-center justify-content-end">未設定一覧</div>
                        <div class="col-4" style="font-size:13pt;">
                            <button class="btn btn-primary w-100" v-on:click="downloadSomethings('#C031-01-04-04')">ダウンロード <i class="fas fa-download fa-2x"></i></button>
                            <VueJsonToCsv :json-data="csvBuff" :csv-title="csvFileName" :labels="labelBuff">
                                <button id="C031-01-04-04" v-show="false" v-on:click="getList('C031-01-04-04')"></button>
                            </VueJsonToCsv>
                        </div>
                        <div class="col-4" style="font-size:13pt;">
                        </div>
                    </div>
                    <div class="row pt-3">
                        <div class="col-4 d-flex align-items-center justify-content-end">代理入力者CSVファイル</div>
                        <div class="col-4" style="font-size:13pt;">
                            <button class="btn btn-primary w-100" v-on:click="downloadSomethings('#C031-01-04-05')">ダウンロード <i class="fas fa-download fa-2x"></i></button>
                            <VueJsonToCsv :json-data="csvBuff" :csv-title="csvFileName" :labels="labelBuff">
                                <button id="C031-01-04-05" v-show="false" v-on:click="getList('C031-01-04-05')"></button>
                            </VueJsonToCsv>
                        </div>
                        <div class="col-4" style="font-size:13pt;">
                            <input type="file" ref="inputAgent" v-show="false" accept="text/csv,.csv" v-on:change="selectedFile('inputAgent')"/>
                            <button id="C031-01-04-06" class="btn btn-primary w-100" v-on:click="openFileDialog('inputAgent')">アップロード <i class="fas fa-upload fa-2x"></i></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="C031-01-05" class="container-fluid py-2 px-4 h-100 w-100 shadow-sm board" style="margin-top:20pt;">
            <div class="row">
                <div class="px-2">
                    <div id="C031-01-05-01" class="text-left">
                        有休一括付与
                    </div>
                </div>
            </div>
            <div class="row">
                <div id="C031-01-05-02" class="px-2">
                    <div class="text-left text-dark ml-2">
                        CSVファイルにより、社員を指定した有休付与が一括して行えます。
                    </div>
                    <div class="text-left text-dark ml-2">
                        テンプレートファイルをダウンロードし、付与情報を付加した上でアップロードを行ってください。
                    </div>
                    <div class="text-left text-dark ml-2">
                        ※年次有給休暇、保存休暇は全社締めで自動付与されるため、この機能での付与はシステム移行時のみとしてください
                    </div>
                    <div class="text-left text-dark ml-2">
                        ※付与済みの休暇の修正は休暇管理画面で行ってください
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="p-2 col-6 text-center"></div>
                <div class="col-6">
                    <div class="row">
                        <div class="col-4 d-flex align-items-center justify-content-end">テンプレートファイル</div>
                        <div class="col-4" style="font-size:13pt;">
                            <a id="C031-01-05-03" class="btn btn-primary w-100" href="template/paidLeave.csv" download="有休一括テンプレート.csv">テンプレート <i class="fas fa-download fa-2x"></i></a>
                        </div>
                        <div class="col-4" style="font-size:13pt;">
                        </div>
                    </div>
                    <div class="row pt-3">
                        <div class="col-4 d-flex align-items-center justify-content-end">有休付与CSVファイル</div>
                        <div class="col-4" style="font-size:13pt;">
                        </div>
                        <div class="col-4" style="font-size:13pt;">
                            <input type="file" ref="inputGrantPaidLeave" v-show="false" accept="text/csv,.csv" v-on:change="selectedFile('inputGrantPaidLeave')"/>
                            <button id="C031-01-05-04" class="btn btn-primary w-100" v-on:click="openFileDialog('inputGrantPaidLeave')">アップロード <i class="fas fa-upload fa-2x"></i></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="C031-01-06" class="container-fluid py-2 px-4 h-100 w-100 shadow-sm board" style="margin-top:20pt;">
            <div class="row">
                <div class="px-2">
                    <div id="C031-01-06-01" class="text-left">
                        社員情報変更履歴出力
                    </div>
                </div>
            </div>
            <div class="row">
                <div id="C031-01-06-02" class="px-2">
                    <div class="text-left text-dark ml-2">
                        期間を指定し、該当期間に変更された社員情報を一覧で出力します。
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-6 text-center">
                    <div id="C031-01-06-03" class="card shadow h-100" style="background-color:#BCD2EE;">
                        <div class="card-body">
                            <div class="card-title text-left">条件指定</div>
                            <div class="card-text d-inline-block" style="color:#000000;">対象期間</div>
                            <input type="date" style="font-size:15pt" min="1901-01-01"/>
                            <div class="card-text d-inline-block" style="color:#000000;">～</div>
                            <input type="date" style="font-size:15pt" min="1901-01-01"/>
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="row" style="height:65px"></div>
                    <div class="row">
                        <div class="col-4 d-flex justify-content-end" style="padding-top:12px">変更履歴</div>
                        <div class="col-4" style="font-size:13pt;">
                            <VueJsonToCsv :json-data="csvBuff" :csv-title="csvFileName" :labels="labelBuff">
                                <button id="C031-01-06-04" class="btn btn-primary w-100" v-on:click="downloadSomething" v-bind:disabled="session_data.is_production">ダウンロード <i class="fas fa-download fa-2x"></i></button>
                            </VueJsonToCsv>
                        </div>
                        <div class="col-4" style="font-size:13pt;">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</template>
<script>
import VueJsonToCsv from 'vue-json-to-csv'
import vuejsDatepicker from 'vuejs-datepicker'
import Loading from 'vue-loading-overlay';
import 'vue-loading-overlay/dist/vue-loading.css';
export default {
    components: {
        VueJsonToCsv,
        vuejsDatepicker,
        "loading":Loading
    },
    props: {
        employee_id: Number, //親からもらった社員番号 Numberで来る
        session_data: Object, //Sessionから取得した社員情報・マスタ情報
    },
    data() {
        return {
            employeeID: 0, //ここでの値保持＆子へ渡す用
            yearMonth: 0, //ここでの値保持＆子へ渡す用
            fileName: '',
            data: [],
            csvBuff: [],
            recievedData: [],
            labelBuff: {},
            recievedLabel: {},
            csvFileName: '',
            recievedName: '',
            approvedDate: '',
            agentDate: '',
            comformModalOption: {
                signupdata: 0,
                changedata: 0,
                deletedata: 0,
                buttons:[　//【暫定】どのボタン指定するかは呼び出し元などから決定し指定する
                    {
                        exec : ()=>{
                            this.btn="OK"; //ボタンが押された時の処理をここに記載

                            this.selectedOk(this.fileName);
                        },
                    },
                    {
                        exec : ()=>{
                            this.btn="キャンセル";
                        },
                    }],
            },
            modalOption: {
                message: '',
                buttons:[　//【暫定】どのボタン指定するかは呼び出し元などから決定し指定する
                    {
                        exec : ()=>{
                            this.btn="OK"; //ボタンが押された時の処理をここに記載
                        },
                        caption : "OK",  //'OK','はい','いいえ','キャンセル','再試行','閉じる'
                        btnclass : "btn-success"
                    }],
            },
            closeDateId: 1, //月末締め
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
            isLoading: false,  //ローディング画面用プロパティ
            fullPage: true,    //ローディング画面用プロパティ
            valid_date_start: '',
            valid_date_end: '',
        };
    },
    methods:{
        //ダウンロード処理サンプル
        downloadSomething(){
            axios.get('getSalaryAlignmentList', {
                params:{
                    'targetDate' : Number(this.serialToDateStr(this.dateStrToSerial(this.defaultDate), "YYYYMM")),
                    'closeDateId' : this.closeDateId,
                }
            }).then(response => {
                if(response.data.result)
                {
                    let json = [];
                    for(let i = 0; i < response.data.values.length; i++){
                        json.push({id:response.data.values[i].employee_code, name:response.data.values[i].employee_name});
                    }

                    //テスト用ラベル
                    this.labelBuff = {
                        id: {title: "社員番号"},
                        name: {title: "社員名"},
                    }
                    //テスト用ファイル名
                    this.csvFileName = "test.csv";
                    //jsonに反映
                    this.csvBuff = json;
                    this.is_csv = true;
                }
                else{
                }
            });
        },
        //ダウンロード処理
        downloadSomethings(buttonId){
            this.isLoading = true;
            this.csvBuff= [];
            this.labelBuff= {};
            var downloadAxiosName = '';
            var inputDate = '';

            if(buttonId == '#C031-01-01-04'){
                let closeDate = "月末締め";
                if(this.closeDateId == 2){
                    closeDate = "15日締め";
                }
                inputDate = Number(this.serialToDateStr(this.dateStrToSerial(this.defaultDate), "YYYYMM"));
                this.csvFileName = "給与連携_" + inputDate +"_"+ closeDate;
                downloadAxiosName = '/getSalaryAlignmentList';
            }else if(buttonId == '#C031-01-03-05'){
                if(this.checkDate(this.approvedDate) == -1){
                    this.modalOption.message = "基準日の入力形式を確認してください。";
                    this.openModal("m112_common_message", "", this.modalOption);
                    return;
                }
                this.csvFileName = "承認対象者_" + this.approvedDate;
                downloadAxiosName = '/get_all_approved_list';
                inputDate = this.checkDate(this.approvedDate);
            }else if(buttonId == '#C031-01-04-05'){
                if(this.checkDate(this.agentDate) == -1){
                    this.modalOption.message = "基準日の入力形式を確認してください。";
                    this.openModal("m112_common_message", "", this.modalOption);
                    return;
                }
                this.csvFileName = "代理対象者_" + this.agentDate;
                downloadAxiosName = '/get_all_agent_list';
                inputDate = this.checkDate(this.agentDate);
            }else if(buttonId == '#C031-01-02-03'){
                if(this.checkDate(this.valid_date_start) == -1){
                    this.modalOption.message = "有効期限開始（基準日）の入力形式を確認してください。";
                    this.openModal("m112_common_message", "", this.modalOption);
                    return;
                }
                this.csvFileName = "社員情報";
                downloadAxiosName = '/get_all_employee_list';
                inputDate = this.checkDate(this.valid_date_start);
            }else if(buttonId == '#C031-01-03-04'){
                if(this.checkDate(this.approvedDate) == -1){
                    this.modalOption.message = "基準日の入力形式を確認してください。";
                    this.openModal("m112_common_message", "", this.modalOption);
                    return;
                }
                this.csvFileName = "承認対象者未設定一覧_" + this.approvedDate;
                downloadAxiosName = '/get_unset_approved_list';
                inputDate = this.checkDate(this.approvedDate);

            }else if(buttonId == '#C031-01-04-04'){
                if(this.checkDate(this.agentDate) == -1){
                    this.modalOption.message = "基準日の入力形式を確認してください。";
                    this.openModal("m112_common_message", "", this.modalOption);
                    return;
                }
                this.csvFileName = "代理入力者未設定一覧_" + this.agentDate;
                downloadAxiosName = '/get_unset_agent_list';
                inputDate = this.checkDate(this.agentDate);
            }

            if(inputDate == null){
                inputDate = this.todaySerial();
            }
            axios.get(downloadAxiosName, {
                params:{
                    'inputDate': inputDate,
                    'closeDateId' : this.closeDateId,
                }
            }).then(response => {
                if(response.data.result)
                {
                    if(!$.isEmptyObject(response.data.values)){
                        //受信バッファへ保持
                        this.recievedData  = response.data.values;
                        $(buttonId).click();
                    }else{
                        this.modalOption.message = "データなし";
                        this.openModal("m112_common_message", "", this.modalOption);
                    }
                }
                else
                {
                    this.modalOption.message = "ダウンロード失敗";
                    this.openModal("m112_common_message", "", this.modalOption);
                }
                //ローディング画面隠す
                this.isLoading = false;
                return response.data.result;
            })
            .catch(function(error){
                //何らかのエラー
                //ローディング画面隠す
                this.isLoading = false;
            });  


        },
        getList(buttonId){
            if(buttonId == 'C031-01-01-04'){
                this.labelBuff.employee_code = {title: "社員コード"};
                this.labelBuff.employee_name = {title: "氏名"};
                this.labelBuff.absence_days = {title: "(変動)欠勤日数"};
                this.labelBuff.absent_time = {title: "(変動)欠勤時間"};
                this.labelBuff.over_time1 = {title: "(変動)残業休出時間１"};
                this.labelBuff.over_time2 = {title: "(変動)残業休出時間２"};
                this.labelBuff.legal_over_time1 = {title: "(変動)法定休出時間１"};
                this.labelBuff.legal_over_time2 = {title: "(変動)法定休出時間２"};
                this.labelBuff.transfer_over_time1 = {title: "(変動)振替残業休出時間１"};
                this.labelBuff.transfer_over_time2 = {title: "(変動)振替残業休出時間２"};
                this.labelBuff.transfer_legal_over_time1 = {title: "(変動)振替法定休出時間１"};
                this.labelBuff.transfer_legal_over_time2 = {title: "(変動)振替法定休出時間２"};
                this.labelBuff.hourly_workingtime_thismonth = {title: "(変動)時給者出勤時間"};
                this.labelBuff.hourly_workingtime_lastmonth = {title: "(変動)時給者前月出勤時間"};
                this.labelBuff.hourly_working_days = {title: "(変動)時給者出勤日数"};
                this.labelBuff.paid_days = {title: "(変動)有休取得日数"};
                this.labelBuff.accumulated_days = {title: "(変動)保存休暇取得日数"};
                this.labelBuff.midnight_time = {title: "(変動)深夜残業時間"};
                this.labelBuff.deduction_days = {title: "(変動)育児等控除日数"};
                this.labelBuff.deduction_time = {title: "(変動)育児等控除時間"};
                this.labelBuff.remain_paid_days = {title: "(変動)有休残日数"};
                this.labelBuff.remain_accumulated_days = {title: "(変動)保存休暇残日数"};
                this.labelBuff.excess60hours_time1 = {title: "(変動)60時間超過時間１"};
                this.labelBuff.excess60hours_time2 = {title: "(変動)60時間超過時間２"};
                this.labelBuff.morning_duty = {title: "(変動)早朝当番回数"};

                //データ
                this.recievedData.forEach((data) => {
                    this.csvBuff.push({
                        employee_code: data.employee_code,
                        employee_name: data.employee_name,
                        absence_days: data.absence_days,
                        absent_time: data.absent_time,
                        over_time1: data.over_time1,
                        over_time2: data.over_time2,
                        legal_over_time1: data.legal_over_time1,
                        legal_over_time2: data.legal_over_time2,
                        transfer_over_time1: data.transfer_over_time1,
                        transfer_over_time2: data.transfer_over_time2,
                        transfer_legal_over_time1: data.transfer_legal_over_time1,
                        transfer_legal_over_time2: data.transfer_legal_over_time2,
                        hourly_workingtime_thismonth: data.hourly_workingtime_thismonth,
                        hourly_workingtime_lastmonth: data.hourly_workingtime_lastmonth,
                        hourly_working_days: data.hourly_working_days,
                        paid_days: data.paid_days,
                        accumulated_days: data.accumulated_days,
                        midnight_time: data.midnight_time,
                        deduction_days: data.deduction_days,
                        deduction_time: data.deduction_time,
                        remain_paid_days: data.remain_paid_days,
                        remain_accumulated_days: data.remain_accumulated_days,
                        excess60hours_time1: data.excess60hours_time1,
                        excess60hours_time2: data.excess60hours_time2,
                        morning_duty: data.morning_duty,
                    });
                });
            }else if(buttonId == 'C031-01-03-05'){
                this.labelBuff.approver = {title: "承認者"};
                this.labelBuff.approved_person = {title: "被承認者"};
                //データ
                this.recievedData.forEach((data) => {
                    this.csvBuff.push({
                        approver: data.approver,
                        approved_person: data.approved_person,
                    });     
                }); 
            }else if(buttonId == 'C031-01-04-05'){
                this.labelBuff.agent = {title: "代理者"};
                this.labelBuff.delegator = {title: "被代理者"};
                //データ
                this.recievedData.forEach((data) => {
                    this.csvBuff.push({
                        agent: data.agent,
                        delegator: data.delegator,
                    });
                });
            }else if(buttonId == 'C031-01-03-04' || buttonId == 'C031-01-04-04'){
                this.labelBuff.employee_id = {title: "社員ID"};
                this.labelBuff.employee_code = {title: "社員コード"};
                this.labelBuff.employee_name = {title: "氏名"};
                this.labelBuff.office_name = {title: "事業所名"};
                this.labelBuff.dept_name = {title: "部署名"};
                this.labelBuff.post_name = {title: "役職名"};
                //データ
                this.recievedData.forEach((data) => {
                    this.csvBuff.push({
                        employee_id: data.employee_id,
                        employee_code: data.employee_code,
                        employee_name: data.employee_name,
                        office_name: data.office_name,
                        dept_name: data.dept_name,
                        post_name: data.post_name,
                    });
                });
            }else if(buttonId == 'C031-01-02-03'){
                this.labelBuff.employee_id = {title: "社員ID"};
                this.labelBuff.employee_code = {title: "社員コード"};
                this.labelBuff.office_code = {title: "事務所コード"};
                this.labelBuff.office_name = {title: "事業所名"};
                this.labelBuff.dept_code = {title: "部署コード"};
                this.labelBuff.dept_name = {title: "部署名"};
                this.labelBuff.work_closing_belonging_office_code = {title: "勤怠締め所属事業所コード"};
                this.labelBuff.work_closing_belonging_office_name = {title: "勤怠締め所属事業所名"};
                this.labelBuff.post_code = {title: "役職コード"};
                this.labelBuff.post_name = {title: "役職名"};
                this.labelBuff.employee_name = {title: "氏名"};
                this.labelBuff.employee_kana_name = {title: "カナ氏名"};
                this.labelBuff.gender = {title: "性別"};
                this.labelBuff.joined_company_date = {title: "入社年月日"};
                this.labelBuff.retirement_company_date = {title: "退職年月日"};
                this.labelBuff.calendar_id = {title: "カレンダID"};
                this.labelBuff.calendar_name = {title: "カレンダ名"};
                this.labelBuff.personal_calendar_id = {title: "個人カレンダID"};
                this.labelBuff.personal_calendar_name = {title: "個人カレンダ名"};
                this.labelBuff.work_zone_code = {title: "勤務帯コード"};
                this.labelBuff.work_zone_name = {title: "勤務帯名"};
                this.labelBuff.week_scheduled_working_days = {title: "週所定日数"};
                this.labelBuff.scheduled_working_hours = {title: "所定労働時間"};
                this.labelBuff.overtime_base_time = {title: "時間外基準時間"};
                this.labelBuff.available_input_class = {title: "入力可否区分"};
                this.labelBuff.thirtysix_agreement_apply_id = {title: "36協定適用ID"};
                this.labelBuff.thirtysix_agreement_apply_name = {title: "36協定適用名"};
                this.labelBuff.employment_style_id = {title: "雇用形態ID"};
                this.labelBuff.employment_style_name = {title: "雇用形態名"};
                this.labelBuff.close_date_id = {title: "締日ID"};
                this.labelBuff.close_date_name = {title: "締日名"};
                this.labelBuff.grant_paid_leave_pattern_id = {title: "有給付与日数パターンID"};
                this.labelBuff.authority_pattern_id = {title: "権限パターンID"};
                this.labelBuff.first_paid_leave_date = {title: "初年度有給付与年月日"};
                this.labelBuff.stamping_target_class = {title: "打刻対象区分"};
                this.labelBuff.email_address = {title: "電子メールアドレス"};
                this.labelBuff.grant_starting_date = {title: "付与起算入社日"};
                this.labelBuff.work_management_target_class = {title: "勤務管理対象区分"};
                this.labelBuff.is_delete = {title: "削除"};
                //データ
                this.recievedData.forEach((data) => {
                    this.csvBuff.push({
                        employee_id: data.employee_id,
                        employee_code: data.employee_code,
                        office_code: data.office_code,
                        office_name: data.office_name,
                        dept_code: data.dept_code,
                        dept_name: data.dept_name,
                        work_closing_belonging_office_code: data.work_closing_belonging_office_code,
                        work_closing_belonging_office_name: data.work_closing_belonging_office_name,
                        post_code: data.post_code,
                        post_name: data.post_name,
                        employee_name: data.employee_name,
                        employee_kana_name: data.employee_kana_name,
                        gender: data.gender,
                        joined_company_date: this.serialToDateStr(data.joined_company_date),
                        retirement_company_date: this.serialToDateStr(data.retirement_company_date),
                        calendar_id: data.calendar_id,
                        calendar_name: data.calendar_name,
                        personal_calendar_id: data.personal_calendar_id,
                        personal_calendar_name: data.personal_calendar_name,
                        work_zone_code: data.work_zone_code,
                        work_zone_name: data.work_zone_name,
                        week_scheduled_working_days: data.week_scheduled_working_days,
                        scheduled_working_hours: this.serialToHoursStr(data.scheduled_working_hours),
                        overtime_base_time: this.serialToHoursStr(data.overtime_base_time),
                        available_input_class: data.available_input_class,
                        thirtysix_agreement_apply_id: data.thirtysix_agreement_apply_id,
                        thirtysix_agreement_apply_name: data.thirtysix_agreement_apply_name,
                        employment_style_id: data.employment_style_id,
                        employment_style_name: data.employment_style_name,
                        close_date_id: data.close_date_id,
                        close_date_name: data.close_date_name,
                        grant_paid_leave_pattern_id: data.grant_paid_leave_pattern_id,
                        authority_pattern_id: data.authority_pattern_id,
                        first_paid_leave_date: this.serialToDateStr(data.first_paid_leave_date),
                        stamping_target_class: data.stamping_target_class,
                        email_address: data.email_address,
                        grant_starting_date: this.serialToDateStr(data.grant_starting_date),
                        work_management_target_class: data.work_management_target_class,
                        is_delete: data.is_delete,
                    });     
                }); 
            }
        },
        //ファイル選択ダイアログオープン
        openFileDialog(refName){
            if(refName == 'inputApprover'){
                this.$refs.inputApprover.click();
            }else if(refName == 'inputAgent'){
                this.$refs.inputAgent.click();
            }else if(refName == 'employeeInformation'){
                this.$refs.employeeInformation.click();
            }else if(refName == 'inputGrantPaidLeave'){
                this.$refs.inputGrantPaidLeave.click();
            }
        },
        async selectedOk(fileName){
            //情報更新
            const isUpdatedData = await this.updateData(fileName);
            if(isUpdatedData && fileName == 'employeeInformation')
            {
                //履歴更新実施
                this.updateHistory();
            }else{
                
            }
        },
        async updateData(fileName){
            this.isLoading = true;
            var UploadAxiosName = '';
            var inputDate = '';
            let ret = null;

            if(fileName == 'inputApprover'){
                UploadAxiosName = '/upload_all_approved_list';
                if(this.checkDate(this.approvedDate) == -1){
                    this.modalOption.message = "基準日の入力形式を確認してください。";
                    this.openModal("m112_common_message", "", this.modalOption);
                    return;
                }
                inputDate = this.checkDate(this.approvedDate);
            }else if(fileName == 'inputAgent'){
                UploadAxiosName = '/upload_all_agent_list';
                if(this.checkDate(this.agentDate) == -1){
                    this.modalOption.message = "基準日の入力形式を確認してください。";
                    this.openModal("m112_common_message", "", this.modalOption);
                    return;
                }
                inputDate = this.checkDate(this.agentDate);
            }else if(fileName == 'employeeInformation'){
                UploadAxiosName = '/upload_employee_information_list';
            }else if(fileName == 'inputGrantPaidLeave'){
                UploadAxiosName = '/upload_grant_paid_leave_list';
            }

            await axios.post(UploadAxiosName, {
                data: this.data,
                employeeID: this.employeeID,
                inputDate: inputDate,
            }).then(response => {
                if(response.data.result)
                {
                    if(fileName != 'employeeInformation'){
                        this.modalOption.message = "アップロードしました。";
                        this.openModal("m112_common_message", "", this.modalOption);
                    }
                }
                else
                {
                    this.modalOption.message = "アップロード失敗";
                    this.openModal("m112_common_message", "", this.modalOption);
                }
                ret = response.data.result;
            })
            .catch(function(error){
                //何らかのエラー
            }); 
            this.isLoading = false;
            return ret;
        },
        updateHistory(){
            this.isLoading = true;
            axios.post('/update_history_table_from_file', {
                data: this.data,
                valid_date_start: this.dateStrToSerial(this.getValidDateStr(this.valid_date_start)),
                valid_date_end: this.dateStrToSerial(this.getValidDateStr(this.valid_date_end)),
                employeeID: this.employeeID,
            }).then(response => {
                this.isLoading = false;
                if(response.data.result)
                {
                    this.modalOption.message = "アップロードしました。";
                    this.openModal("m112_common_message", "", this.modalOption);
                }
                else
                {
                    this.modalOption_m112.message = "社員情報アップロードしましたが、履歴更新失敗";
                    this.openModal("m112_common_message", "", this.modalOption_m112);
                }
                return response.data.result;
            })
            .catch(function(error){
                //何らかのエラー
                this.isLoading = false;
            });
        },

        //ファイル選択ダイアログオープンサンプル
        openSomethingFileDialog(){
            this.$refs.inputSomething.click();
        },
        //ファイル選択されたときの動作サンプル
        async selectedSomethingFile(){
            const file = this.$refs.input.files[0]
            if (!file) {
                return;
            }
            //ここ以降にファイルアップロード処理
        },
        //ファイル選択されたときの動作
        async selectedFile(fileName){
            this.fileName = fileName;
            this.data = [];
            var file = null;
            var UploadAxiosName = '';
            var inputDate = '';
            if(fileName == 'inputApprover'){
                file = this.$refs.inputApprover.files[0];
                this.$refs.inputApprover.value = null;
                if(this.checkDate(this.approvedDate) == -1){
                    this.modalOption.message = "基準日の入力形式を確認してください。";
                    this.openModal("m112_common_message", "", this.modalOption);
                    return;
                }
                inputDate = this.checkDate(this.approvedDate);
                UploadAxiosName = '/check_approver_agent';
            }else if(fileName == 'inputAgent'){
                file = this.$refs.inputAgent.files[0];
                this.$refs.inputAgent.value = null;
                if(this.checkDate(this.agentDate) == -1){
                    this.modalOption.message = "基準日の入力形式を確認してください。";
                    this.openModal("m112_common_message", "", this.modalOption);
                    return;
                }
                inputDate = this.checkDate(this.agentDate);
                UploadAxiosName = '/check_approver_agent';
            }else if(fileName == 'employeeInformation'){
                file = this.$refs.employeeInformation.files[0];
                UploadAxiosName = '/check_employee';
                this.$refs.employeeInformation.value = null;
                if(this.checkDate(this.valid_date_start) == -1 || this.checkDate(this.valid_date_end) == -1){
                    this.modalOption.message = "有効期限の入力形式を確認してください。";
                    this.openModal("m112_common_message", "", this.modalOption);
                    return;
                }
                inputDate = this.checkDate(this.valid_date_start) || this.checkDate(this.valid_date_end);
            }else if(fileName == 'inputGrantPaidLeave'){
                file = this.$refs.inputGrantPaidLeave.files[0];
                UploadAxiosName = '/check_grant_paid_leave';
                this.$refs.inputGrantPaidLeave.value = null;

                inputDate = this.checkDate(this.todaySerial());
            }
            if (!file) {
                return;
            }
            if(file.name.substring(file.name.lastIndexOf(".")) != ".csv"){
                this.modalOption.message = "アップロードしたファイルのフォーマットが異なります。ファイルを確認してください。";
                this.openModal("m112_common_message", "", this.modalOption);
                return;
            }
            this.isLoading = true;
            var reader = new FileReader();
            reader.readAsText(file);
            reader.onload = (val) => {
                var relArr = reader.result.split("\r\n");
                if(!$.isEmptyObject(relArr) && relArr.length > 1) {
                    for(var key = 1, len = relArr.length; key < len; key++) {
                        var values = relArr[key];
                        if(!$.isEmptyObject(values)) {
                            var obj = {};
                            var objArr = values.split(",");
                            if(fileName == 'inputApprover' || fileName == 'inputAgent'){
                                this.data.push({
                                    approver_agent_code: objArr[0].replace(new RegExp('"','g'),""),
                                    approved_delegator_code: objArr[1].replace(new RegExp('"','g'),""),
                                });
                            }else if(fileName == 'employeeInformation'){
                                this.data.push({
                                    employee_id: objArr[0],
                                    employee_code: objArr[1].replace(new RegExp('"','g'),""),
                                    office_code: objArr[2],
                                    dept_code: objArr[4],
                                    work_closing_belonging_office_code: objArr[6],
                                    post_code: objArr[8],
                                    employee_name: objArr[10].replace(new RegExp('"','g'),""),
                                    employee_kana_name: objArr[11].replace(new RegExp('"','g'),""),
                                    gender: objArr[12].replace(new RegExp('"','g'),""),
                                    joined_company_date: objArr[13].replace(new RegExp('"','g'),""),
                                    retirement_company_date: objArr[14].replace(new RegExp('"','g'),""),
                                    calendar_id: objArr[15],
                                    personal_calendar_id: objArr[17],
                                    work_zone_code: objArr[19],
                                    week_scheduled_working_days: objArr[21],
                                    scheduled_working_hours: objArr[22].replace(new RegExp('"','g'),""),
                                    overtime_base_time: objArr[23].replace(new RegExp('"','g'),""),
                                    available_input_class: objArr[24],
                                    thirtysix_agreement_apply_id: objArr[25],
                                    employment_style_id: objArr[27],
                                    close_date_id: objArr[29],
                                    grant_paid_leave_pattern_id: objArr[31],
                                    authority_pattern_id: objArr[32],
                                    first_paid_leave_date: objArr[33].replace(new RegExp('"','g'),""),
                                    stamping_target_class: objArr[34],
                                    email_address: objArr[35].replace(new RegExp('"','g'),""),
                                    grant_starting_date: objArr[36].replace(new RegExp('"','g'),""),
                                    work_management_target_class: objArr[37],
                                    is_delete: objArr[38],
                                });
                            }else if(fileName == 'inputGrantPaidLeave'){
                                this.data.push({
                                    employee_code: objArr[0].replace(new RegExp('"','g'),""),
                                    holiday_id: objArr[1],
                                    valid_date_start: objArr[2].replace(new RegExp('"','g'),""),
                                    valid_date_end: objArr[3].replace(new RegExp('"','g'),""),
                                    grant_holiday_days: objArr[4],
                                });
                            }
                        }
                    }
                }
                axios.post(UploadAxiosName, {
                    data: this.data,
                    fileName: this.fileName,
                    inputDate: inputDate,
                }).then(response => {
                    this.isLoading = false;
                    if(response.data.result)
                    {
                        if(fileName == 'inputApprover' || fileName == 'inputAgent'){

                            this.comformModalOption.signupdata = response.data.NewCount;
                            this.comformModalOption.changedata = response.data.UpdateCount;

                            this.openModal("m115_check_upload", "", this.comformModalOption);
                        }else if(fileName == 'employeeInformation'){

                            this.comformModalOption.signupdata = response.data.NewCount;
                            this.comformModalOption.changedata = response.data.UpdateCount;
                            
                            this.openModal("m115_check_upload", "", this.comformModalOption);
                        }else if(fileName == 'inputGrantPaidLeave'){
                            this.comformModalOption.signupdata = response.data.NewCount;
                            this.comformModalOption.changedata = response.data.UpdateCount;

                            this.openModal("m115_check_upload", "", this.comformModalOption);
                        }
                    }
                    else
                    {
                        if(fileName == 'inputApprover' || fileName == 'inputAgent'){
                            this.modalOption.message = "アップロードできません";
                            this.openModal("m112_common_message", "", this.modalOption);
                        }else if(fileName == 'employeeInformation'){
                            this.modalOption.message = (response.data.count+1) + response.data.errorMessage;
                            this.openModal("m112_common_message", "", this.modalOption);
                        }else if(fileName == 'inputGrantPaidLeave'){
                            this.modalOption.message = (response.data.count+7) + response.data.errorMessage;
                            this.openModal("m112_common_message", "", this.modalOption);
                        }
                    }
                    return response.data.result;
                })
                .catch(function(error){
                    //何らかのエラー
                    this.isLoading = false;
                }); 
            }

        },
        onCancel() {
            //Loading画面のキャンセル
        },
    },
    computed:{

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
        }
    },
    mounted() {
        this.approvedDate = this.serialToDateStr(this.todaySerial(),'YYYY-MM-DD');
        this.agentDate = this.serialToDateStr(this.todaySerial(),'YYYY-MM-DD');
        this.valid_date_start = this.serialToDateStr(this.todaySerial(),'YYYY-MM-DD');
    }
}
</script>