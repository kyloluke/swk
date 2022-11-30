<!DOCTYPE HTML>
<html lang="ja">
<head>
<!-- Required meta tags -->
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title></title>

    <!--Bootstrap CSS -->
    <link rel="stylesheet" href="/css/libs/bootstrap-4.2.1-dist/bootstrap.min.css">

    <!--public/css -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <!--jquery-ui/css -->
    <link rel="stylesheet" href="/css/libs/jquery/jquery-ui.css">
    <link rel="stylesheet" href="/css/libs/jquery/jquery-ui.structure.css">
    <link rel="stylesheet" href="/css/libs/jquery/jquery-ui.theme.css">

</head>
<body>

<div id="test_goto_modal">

    <!-- m101_login -->
    <div><br></div>
    <div><button class="btn btn-success" v-on:click="openModal('m101_login')">ログイン</button></div>
    <div><br></div>

    <!-- m102_information -->
    <div class="form-group">
        <input type="radio" name="info" v-on:click="sis" checked/>システム情報
        <input type="radio" name="info" v-on:click="sou"         >総務情報
        <button class="btn btn-primary" v-on:click="openModal('m102_information','', op1)">m102_information</button>
    </div>
    <div><br></div>

    <!-- m103_record_time_result -->
    <div>
        <button class="btn btn-primary" v-on:click="openModal('m103_record_time_result','', '出勤')">出勤</button>
        <button class="btn btn-primary" v-on:click="openModal('m103_record_time_result','', '退勤')">退勤</button>
        <button class="btn btn-primary" v-on:click="openModal('m103_record_time_result','', '外出')">外出</button>
        <button class="btn btn-primary" v-on:click="openModal('m103_record_time_result','', '戻り')">戻り</button>
    </div>
    <div><br></div>

    <!-- m104_record_time_list -->
    <div><button class="btn btn-success" v-on:click="openModal('m104_record_time_list')">WEB打刻一覧</button></div>
    <div><br></div>

    <!-- aggrigateAttendanceList -->
    <button class="btn btn-primary" v-on:click="openModal('aggrigateAttendanceList', 'modal-lg', op1)">aggrigateAttendanceList</button>
    <div><br></div>

    <!-- m105_input_attendance_details -->
    <div class="form-group">
        <input type="radio" name="m105" v-on:click="hon" checked/>本人入力画面
        <input type="radio" name="m105" v-on:click="kan"         >管理者画面
        <input id="C105-datesample" type="text" class="form-control" style="width:150px" value="">
        <button class="btn btn-success" v-on:click="openModal('m105_input_attendance_details', 'modal-lg', op1); m105buttonClick()">m105_input_attendance_details</button>
    </div>
    <div><br></div>

    <!-- m106_daily_report -->
    <button class="btn btn-danger" v-on:click="openModal('m106_daily_report', 'modal-xl', op1)">m106_daily_report</button>
    <div><br></div>

    <!-- m107_change_password -->
    <button class="btn btn-danger" v-on:click="openModal('m107_change_password')">m107_change_password</button>
    <div><br></div>

    <!-- m108_setting_target -->
    <div class="form-group">
        <input type="radio" name="m108" v-on:click="substitute" checked/>代理入力(入力対象者設定)画面
        <input type="radio" name="m108" v-on:click="manager"            >勤怠管理者 (承認対象者設定)画面
        <button class="btn btn-danger" v-on:click="openModal('m108_setting_target', 'modal-xl', op1)">m108_setting_target</button>
    </div>
    <div><br></div>

    <!-- m109_select_daily_report -->
    <button class="btn btn-danger" v-on:click="openModal('m109_select_daily_report')">m109_select_daily_report</button>
    <div><br></div>

    <!-- m110_search_member -->
    <div><button class="btn btn-success" v-on:click="openModal('m110_search_member','', '択一')">（択一）m110_search_member</button></div>
    <div><button class="btn btn-success" v-on:click="openModal('m110_search_member','', '選択')">（選択）m110_search_member</button></div>
    <div><br></div>

    <!-- m111_give_paid_absents -->
    <button class="btn btn-danger" v-on:click="m111edit(), openModal('m111_give_paid_absents', '', op1)">修正</button>
    <button class="btn btn-danger" v-on:click="m111give(), openModal('m111_give_paid_absents', '', op1)">その他休暇付与</button>
    <div>m111モーダルで入力した付与日数：@{{giveNums}}</div>
    <div><br></div>

    <!-- m112_common_message -->
    <div>
        <button class="btn btn-primary" v-on:click="openModal('m112_common_message', '', op1)">m112_common_message</button>
    </div>
    <div>m112モーダルで押したボタン：@{{btn}}</div>
    <div><br></div>

    <!-- m113_select_period -->
    <div class="form-group">
        <input type="radio" name="m113" v-on:click="yymmdd" checked/>年月日選択
        <input type="radio" name="m113" v-on:click="yymm"         >年月選択
        <button class="btn btn-danger" v-on:click="openModal('m113_select_period', '', op1)">m113_select_period</button>
    </div>
    <div>m113モーダルで選択した日付：@{{start}}～@{{end}}</div>
    <div><br></div>

    <!-- m114_register_work_pattern -->
    <button class="btn btn-success" v-on:click="m114edit(), openModal('m114_register_work_pattern', '', op1)">勤務帯修正</button>
    <button class="btn btn-success" v-on:click="m114regist(), openModal('m114_register_work_pattern', '', op1)">勤務帯登録</button>
    <div>m114モーダルで入力した所定実働：@{{m114_data.prescribedActual}}</div>
    <div><br></div>

    <!-- m115_check_upload -->
    <button class="btn btn-danger" v-on:click="openModal('m115_check_upload')">m115_check_upload</button>
    <div><br></div>

    <!-- m116_select_download -->
    <button class="btn btn-danger" v-on:click="openModal('m116_select_download', '', op1)">m116_select_download</button>
    <div>m116モーダルで選択した事業所：@{{officeInput}}、所属：@{{adjectiveInput}}</div>
</div>

<!-- Optional JavaScript -->
<!--jquery,jquery-ui/js -->
<script src="/js/libs/jquery/jquery-3.3.1.js"></script>
<script src="/js/libs/jquery/jquery-ui.js"></script>
<script src="/js/libs/jquery/datepicker-ja.js"></script>

<!-- Popper.js, Bootstrap JS -->
<script src="/js/libs/popper-1.14.7/popper.min.js" ></script>
<script src="/js/libs/bootstrap-4.2.1-dist/bootstrap.min.js" ></script>

<!--Font Awesome5-->
<script src="/js/libs/fontawesome-free-5.15.2-web/all.min.js"></script>

<!-- Vue.js, 共通js -->
<script src=" {{ asset('/js/app.js') }} "></script>

<script>
    var test_goto_modal = new Vue({
        el: '#test_goto_modal',

        data() {
            return {
                btn: '', // m112_common_message
                start: '', // m113_select_period
                end: '', // m113_select_period

                // m114_register_work_pattern
                m114_data:{
                    targetOffice: '',
                    workZoneName: '',
                    prescribedTimeStart: '',
                    prescribedTimeEnd: '',
                    prescribedRest1Start: '',
                    prescribedRest1End: '',
                    prescribedRest2Start: '',
                    prescribedRest2End: '',
                    prescribedRest3Start: '',
                    prescribedRest3End: '',
                    prescribedActual: '',
                },

                absentType: '', // m111_give_paid_absents
                giveDay: '', // m111_give_paid_absents
                limitDay: '', // m111_give_paid_absents
                giveNums: '', // m111_give_paid_absents
                usedNums: '', // m111_give_paid_absents
                officeInput: '', // m116_select_download
                adjectiveInput: '', // m116_select_download
                op1:{
                    // m102_information  1:システム情報、2:総務情報
                    information_type_id: 1,

                    // m104_record_time_list, aggrigateAttendanceList
                    year: 2021,
                    month: 4,

                    // m105_input_attendance_details
                    date: 0,
                    datestr: '',
                    page_id: 1, // 1:本人入力画面、2:管理者画面

                    // m108_setting_target
                    setting_target_page_type: 1, // 1:代理入力画面、2:勤怠管理者画面

                    // m112_common_message モーダルに配置するボタンを最大２つまで指定する。caption：ボタンに表示する文字、btnclass：bootstrapの定義済みボタンスタイルを指定
                    buttons:[　//【暫定】どのボタン指定するかは呼び出し元などから決定し指定する
                    {
                        exec : ()=>{
                            this.btn="OK"; //ボタンが押された時の処理をここに記載
                        },
                        caption : "OK",  //'OK','はい','いいえ','キャンセル','再試行','閉じる'
                        btnclass : "btn-success"
                    },
                    {
                        exec : ()=>{
                            this.btn="キャンセル";
                        },
                        caption : "キャンセル",
                        btnclass : "btn-danger"
                    }],

                    // m113_select_period
                    select_period_type: 1, // 1:年月日選択、2:年月選択
                    m113_select_date : (startdate, enddate)=>{
                            this.start = startdate;
                            this.end = enddate;
                        },
                    // m114_register_work_pattern
                    register_work_pattern_type: true, // true:勤務帯修正、false:勤務帯登録
                    targetOffice: null,
                    workZoneName: null,
                    prescribedTimeStart: null,
                    prescribedTimeEnd: null,
                    prescribedRest1Start: null,
                    prescribedRest1End: null,
                    prescribedRest2Start: null,
                    prescribedRest2End: null,
                    prescribedRest3Start: null,
                    prescribedRest3End: null,
                    m114_callback_regist: (m114_data)=>{this.m114_callback_regist(m114_data);},
                    m114_callback_cancel: ()=>{this.m114_callback_cancel();},

                    // m111_give_paid_absents
                    give_paid_absents_type: true, // true:修正、false:その他休暇付与
                    callback_regist: (absentType, giveDay, limitDay, giveNums)=>{this.m111_callback_regist(absentType, giveDay, limitDay, giveNums);},
                    callback_edit: (giveNums, usedNums)=>{this.m111_callback_edit(giveNums, usedNums);},
                    callback_cancel: ()=>{this.m111_callback_cancel();},

                    // m116_select_download
                    callback_select: (officeInput, adjectiveInput)=>{this.callback_select(officeInput, adjectiveInput);},
                    callback_cancel: ()=>{this.callback_cancel();},
                  }
            };
        },
        methods: {

            // m102_information
            sis: function() {
                this.op1.information_type_id = 1;
            },
            sou: function() {
                this.op1.information_type_id = 2;
            },

            // m105_input_attendance_details
            m105buttonClick() {
            this.op1.datestr = $("#C105-datesample").val();
            this.op1.date = Date.parse(this.op1.datestr);
            this.op1.date = Math.floor((this.op1.date + (9 * 60 * 60 * 1000)) / (24 * 60 * 60 * 1000) + 25569);
            },
            hon: function() {
                this.op1.page_id = 1;
            },
            kan: function() {
                this.op1.page_id = 2;
            },

            // m108_setting_target
            substitute: function() {
                this.op1.setting_target_page_type = 1;
            },
            manager: function() {
                this.op1.setting_target_page_type = 2;
            },

            // m111_give_paid_absents
            m111edit: function() {
                this.op1.give_paid_absents_type = true;
                this.op1.absentType = "年次有給休暇";
                this.op1.giveDay = 44000;
                this.op1.limitDay = 45000;
                this.op1.giveNums = 1;
                this.op1.usedNums = 0;
            },
            m111give: function() {
                this.op1.give_paid_absents_type = false;
            },
            m111_callback_regist(absentType, giveDay, limitDay, giveNums){
                this.absentType = absentType;
                this.giveDay = giveDay;
                this.limitDay = limitDay;
                this.giveNums = giveNums;
            },
            m111_callback_edit(giveNums, usedNums){
                this.giveNums = giveNums;
                this.usedNums = usedNums;
            },
            m111_callback_cancel(){
            
            },

            // m113_select_period
            yymmdd: function() {
                this.op1.select_period_type = 1;
            },
            yymm: function() {
                this.op1.select_period_type = 2;
            },

            // m114_register_work_pattern
            m114edit: function() {
                this.op1.register_work_pattern_type = true;
                this.op1.targetOffice = "全社共通";
                this.op1.workZoneName = "基本勤務A";
                this.op1.prescribedTimeStart = 530;
                this.op1.prescribedTimeEnd = 1040;
                this.op1.prescribedRest1Start = 720;
                this.op1.prescribedRest1End = 765;
            },
            m114regist: function() {
                this.op1.register_work_pattern_type = false;
            },
            m114_callback_regist(m114_data){
                this.m114_data = m114_data;
            },
            m114_callback_cancel(){
            
            },

            // m116_select_download
            callback_select(officeInput, adjectiveInput){
                this.officeInput = officeInput;
                this.adjectiveInput = adjectiveInput;
            },
            callback_cancel(){
            
            },
        },
        mounted(){
            $(function() {
                $("#C105-datesample").datepicker().datepicker('setDate','today'); //カレンダー表示
            });
        },
});
</script>

</body>
</html>