<template>
    <div id="C105-01" class="modal-content" style="width: 640px">
        <div class="modal-body">
            <attendanceDetailHeader :session_data="op1.session_data" :attendance_information="attendance_information"></attendanceDetailHeader>
            <attendanceDetailList :session_data="op1.session_data" :attendance_information="attendance_information" :is_manager="op1.isManager" :information_attendance_mode="op1.informationAttendanceMode" @setParams="setInputValues"></attendanceDetailList>
            <div class="board-C105-1">
                <a type="button" class="btn btn-sm btn-success text-white text-left" style="width: 12%" href="/achievement_manual"><i class="fas fa-question-circle" style="margin-right: 10px;"></i>説明</a>
                <button v-if="isApplyButton" type="button" class="btn btn-sm btn-success" style="width: 12%" v-on:click="dailyreportbuttonClick">日報登録</button>
                <div class="accordion" id="accordion1">
                    <attendanceDetailAchievement :session_data="op1.session_data" :attendance_information="attendance_information" :is_manager="op1.isManager" :is_bunch="op1.isBunch" @setParams="setInputValues" ref="attendanceDetailAchievement"></attendanceDetailAchievement>
                    <div id="C105-04" class="card C105-card">
                        <div class="card-header" id="headingTwo" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                            <h5 id="C105-04-01" class="mb-0">不就業申請
                                <span style="position: absolute; right: 30px;"><i class="fas fa-chevron-down fa-2x" data-fa-transform="up-3"></i></span>
                            </h5>
                        </div>
                        <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion1">
                            <attendanceDetailUnemployed :session_data="op1.session_data" :attendance_information="attendance_information" :is_manager="op1.isManager" @setParams="setInputValues"></attendanceDetailUnemployed>
                        </div>
                    </div>
                    <div id="C105-05" class="card C105-card">
                        <div class="card-header" id="headingThree" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                            <h5 id="C105-05-01" class="mb-0">残業・控除申請
                                <span style="position: absolute; right: 30px;"><i class="fas fa-chevron-down fa-2x" data-fa-transform="up-3"></i></span>
                            </h5>
                        </div>
                        <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordion1">
                            <attendanceDetailOverTime :session_data="op1.session_data" :attendance_information="attendance_information" :is_manager="op1.isManager" @setParams="setInputValues" @setError="setError"></attendanceDetailOverTime>
                        </div>
                    </div>
                    <div id="C105-06" class="card C105-card">
                        <div class="card-header" id="headingFour" data-toggle="collapse" data-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                            <h5 id="C105-06-01" class="mb-0">打刻
                                <span style="position: absolute; right: 30px;"><i class="fas fa-chevron-down fa-2x" data-fa-transform="up-3"></i></span>
                            </h5>
                        </div>
                        <div id="collapseFour" class="collapse" aria-labelledby="headingFour" data-parent="#accordion1">
                            <attendanceDetailWebPunch :session_data="op1.session_data" :is_manager="op1.isManager" :attendance_information="attendance_information" @setParams="setInputValues"></attendanceDetailWebPunch>
                        </div>
                    </div>
                </div>
            </div>

      　    <div class="message-group row ml-1 mr-1 pt-3">
                <div id="C105-07-03" class="error-message text-center col-sm-12">
                    <div>{{message}}</div>
                </div>
            </div>
            <div>
                <div class="modal-footer d-flex justify-content-around">
                    <button v-if="isApplyButton" id="C105-07-01" type="button" class="btn btn-primary mb-3" style="width:92%" v-on:click="savebuttonClick" v-bind:disabled="!isEnableApply">申請</button>
                    <button v-if="isApplyApprovalButton" id="C105-07-07" type="button" class="btn btn-primary w-25" v-on:click="saveandapprovalbuttonClick" v-bind:disabled="!isEnableApplyAndApprove">申請・承認</button>
                    <button v-if="isAllApplyButton" id="C105-07-01" type="button" class="btn btn-primary mb-3" style="width:92%" v-on:click="savebuttonBunchClick(2)" v-bind:disabled="!isEnableApply">一括申請</button>
                    <button v-if="isSaveButton" id="C105-07-02" type="button" class="btn btn-secondary w-25" v-on:click="provisionalSavebuttonClick" v-bind:disabled="!isEnableApply || op1.isBunch">申請せずに保存</button>
                    <button v-if="isAllSaveButton" id="C105-07-09" type="button" class="btn btn-secondary w-26" v-on:click="savebuttonBunchClick(5)" v-bind:disabled="!isEnableApply">申請せずに一括保存</button>
                    <button v-if="isInquiryApprovalModeButton" id="C105-07-04" type="button" class="btn btn-primary w-25" v-on:click="approvalbuttonClick" v-bind:disabled="!isEnableApprove">承認</button>
                    <button v-if="isDisengageApprovalModeButton" id="C105-07-04" type="button" class="btn btn-primary w-25" v-on:click="approveUnrecognizedButtonClick">承認解除</button>
                    <button v-if="isCancelApplyApprovalButton" id="C105-07-08" type="button" class="btn btn-orange w-25" v-on:click="remandandcancelapplybuttonClick" v-bind:disabled="!op1.isRemand">申請・承認解除</button>
                    <button v-if="isApplyModeButton" id="C105-07-06" type="button" class="btn btn-orange w-25" v-on:click="cancelapplybuttonClick" v-bind:disabled="!isEnableCancelApply || op1.isBunch">申請取り消し</button>
                    <button v-if="isTrunBackApprovalModeButton" id="C105-07-05" type="button" class="btn btn-orange w-25" v-on:click="remandbuttonClick" v-bind:disabled="!isEnableRemand">差戻し</button>
                    <button id="C105-07-06" type="button" class="btn btn-danger w-25" v-on:click="cancelbuttonClick">キャンセル</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import attendanceDetailHeader from '../components/inputAttendanceDetailContents/attendanceDetailHeader.vue';
import attendanceDetailList from '../components/inputAttendanceDetailContents/attendanceDetailList.vue';
import attendanceDetailAchievement from '../components/inputAttendanceDetailContents/attendanceDetailAchievement.vue';
import attendanceDetailUnemployed from '../components/inputAttendanceDetailContents/attendanceDetailUnemployed.vue';
import attendanceDetailOverTime from '../components/inputAttendanceDetailContents/attendanceDetailOverTime.vue';
import attendanceDetailWebPunch from '../components/inputAttendanceDetailContents/attendanceDetailWebPunch.vue';
export default {
    name: "M105data",
    props: ['op1', 'id'],
    components: {
        'attendanceDetailHeader': attendanceDetailHeader,
        'attendanceDetailList': attendanceDetailList,
        'attendanceDetailAchievement': attendanceDetailAchievement,
        'attendanceDetailUnemployed': attendanceDetailUnemployed,
        'attendanceDetailOverTime': attendanceDetailOverTime,
        'attendanceDetailWebPunch': attendanceDetailWebPunch,
    },
    data() {
        return {
            inputParams: {
                work_achievement_id: 0,
            },
            message: '',
            op1_1: {
                buttons: [　//【暫定】メッセージ、ボタンの文言などは暫定
                    {
                        exec: ()=>{
                            this.op1.reflectChange();
                            //M105モーダルを閉じる
                            $('.modal-backdrop').remove();
                            $('#' + this.id).modal('hide');
                            $("#C105-01").remove();
                        },
                        caption: "はい",
                        btnclass: "btn-primary"
                    },
                    {
                        exec: ()=>{
                            //いいえボタンが押された時の処理をここに記載
                        },
                        caption: "いいえ",
                        btnclass: "btn-danger"
                    }
                ],
                message: '保存されていない入力情報があります。情報が失われますがウインドウを閉じてよろしいですか？'
            },
            attendance_information: {},
            error_code: 0,
            isEnableApply: true,
            isEnableCancelApply: true,
            isEnableApprove: true,
            isEnableRemand: true,
            isEnableApplyAndApprove: true,
            isEnableApproveUnrecognized: false,
        };
    },
    computed:{
        // ボタン表示条件をプロパティ化
        isApplyButton: function(){
            // 申請ボタン
            return ((this.op1.informationAttendanceMode == 0) && !this.op1.isBunch);
        },
        isApplyApprovalButton: function(){
            // 承認・申請
            return (this.attendance_information.approval_state_id == 1 && this.op1.isManager && !this.op1.isBunch)
                || (this.attendance_information.approval_state_id == 5 && this.op1.isManager && !this.op1.isBunch);
        },
        isAllApplyButton: function(){
            // 一括申請ボタン
            return ((this.op1.informationAttendanceMode == 0) && this.op1.isBunch);
        },
        isAllSaveButton: function(){
            // 一括申請せず保存ボタン
            return ((this.op1.informationAttendanceMode == 0) && this.op1.isBunch);
        },
        isSaveButton: function(){
            // 申請せずに保存ボタン
            return (this.op1.informationAttendanceMode == 0 && !this.op1.isBunch);
        },
        isApplyModeButton: function(){
            // 申請取り消しボタン
            return (this.op1.informationAttendanceMode == 0);
        },
        isInquiryApprovalModeButton: function(){
            // 承認ボタン
            return (this.attendance_information.approval_state_id == 2 && !this.isEnableApproveUnrecognized && this.op1.isManager)
                || (this.attendance_information.approval_state_id == 4 && !this.isEnableApproveUnrecognized && this.op1.isManager);
        },
        isDisengageApprovalModeButton: function() {
            // 承認解除ボタン
            return (this.attendance_information.approval_state_id == 3 && this.isEnableApproveUnrecognized && this.op1.isManager);
        },
        isTrunBackApprovalModeButton: function(){
            // 差戻しボタン
            return (this.attendance_information.approval_state_id == 2 && this.op1.isManager)
                || (this.attendance_information.approval_state_id == 4 && this.op1.isManager);
        },

        isCancelApplyApprovalButton: function(){
            // 申請・承認解除ボタン
            return (this.attendance_information.approval_state_id == 1 && this.op1.isManager) 
            || (this.attendance_information.approval_state_id == 3 && this.op1.isManager)
            || (this.attendance_information.approval_state_id == 5 && this.op1.isManager);
        },
    },
    methods: {
        //子から呼ばれる想定のエラーコードセッター
        setError: function(errorCode){
            this.error_code = errorCode;
        },
        //値更新
        setInputValues: function(keyValue){
            if(!this.attendance_information.attendance_information_id)
            {
                return;
            }
            for(let key in keyValue)
            {
                if(this.attendance_information[key] == undefined)
                {
                    this.$set(this.attendance_information, key, keyValue[key]);
                }
                else
                {
                    this.attendance_information[key] = keyValue[key];
                }
            }
        },
        savebuttonClick: async function() {
            if(this.validate())
            {
                let info = await this.base64Encode(JSON.stringify(this.attendance_information));
                //DB更新処理
                axios.post('m105_input_attendance_details_save', {
                    params: {
                        info: info,
                        type: 1,// 申請:1 承認:2 差戻:3 申請取り消し:4
                    }
                }).then(response => {
                    if(response.data.result)
                    {
                        this.op1.reflectChange();
                        //M105モーダルを閉じる
                        $('.modal-backdrop').remove();
                        $('#' + this.id).modal('hide');
                        $("#C105-01").remove();
                    }
                    else
                    {
                        this.message = response.data.values.message;
                    }
                }).catch(error => {
                    console.log(error.response);
                });
            }
            else
            {
                //nothing to do
            }
            return;
        },
        saveandapprovalbuttonClick: async function() {
            if(this.validate())
            {
                let info = await this.base64Encode(JSON.stringify(this.attendance_information));
                // 承認処理を呼び出す
                axios.post('m105_input_attendance_details_save', {
                    params: {
                        info: info,
                        type: 7,// 申請・承認
                        closeStateId: this.op1.close_state_id,
                    }
                }).then(response => {
                    if(response.data.result)
                    {
                        this.op1.reflectChange();
                        //M105モーダルを閉じる
                        $('.modal-backdrop').remove();
                        $('#' + this.id).modal('hide');
                        $("#C105-01").remove();
                    }
                    else
                    {
                        this.message = response.data.values.message;
                    }
                }).catch(error => {
                    console.log(error.response);
                });
            }
            else
            {
                //nothing to do
            }
            return;
        },
        /**
         * 一括申請・一括申請せずに保存
         * @param type approval_state_idに当てはまる
         */
        savebuttonBunchClick: async function(type) {
            if(type != 2 && type != 5) {
                alert('不正な呼び出しが行われました。ページを読み込みなおしてください。');
                return;
            }
            if(this.validate())
            {
                let info = await this.base64Encode(JSON.stringify(this.attendance_information));
                //DB更新処理
                axios.post('m105_input_attendance_details_save_bunch', {
                    params: {
                        info: info,
                        checked_list: this.op1.checked_list,
                        type: type
                    }
                }).then(response => {
                    if(response.data.result)
                    {
                        this.op1.reflectChange();
                        //M105モーダルを閉じる
                        $('.modal-backdrop').remove();
                        $('#' + this.id).modal('hide');
                        $("#C105-01").remove();
                    }
                    else
                    {
                        this.message = response.data.values.message;
                    }
                }).catch(error => {
                    console.log(error.response);
                });
            }
            else
            {
                //nothing to do
            }
            return;
        },
        provisionalSavebuttonClick: async function() {
            if(this.validate())
            {
                let info = await this.base64Encode(JSON.stringify(this.attendance_information));
                //DB更新処理
                axios.post('m105_input_attendance_details_save', {
                    params: {
                        info: info,
                        type: 5,// 申請:1 承認:2 差戻:3 申請取り消し:4 仮申請:5
                    }
                }).then(response => {
                    if(response.data.result)
                    {
                        this.op1.reflectChange();
                        //M105モーダルを閉じる
                        $('.modal-backdrop').remove();
                        $('#' + this.id).modal('hide');
                        $("#C105-01").remove();
                    }
                    else
                    {
                        this.message = response.data.values.message;
                    }
                }).catch(error => {
                    console.log(error.response);
                });
            }
            else
            {
                //nothing to do
            }
            return;
        },
        cancelbuttonClick: function() {
            this.openModal('m112_common_message', '', this.op1_1); //【要対応】保存されていない入力情報がある場合に共通メッセージモーダルを開く
        },
        dailyreportbuttonClick: function() {
            const option_m106 = {
                employee_id: this.attendance_information.employee_id,
                session_data: this.op1.session_data,
                date: this.attendance_information.attendance_date,
             }
            this.openModal('m106_daily_report', 'modal-xl',  option_m106);
        },
        approvalbuttonClick: async function() {
            //承認ボタン押下時の処理
            let info = await this.base64Encode(JSON.stringify(this.attendance_information));
            axios.post('m105_input_attendance_details_save', {
                params: {
                    info: info,
                    type: 2,// 申請:1 承認:2 差戻:3 申請取り消し:4
                    closeStateId: this.op1.close_state_id,
                }
            }).then(response => {
                if(response.data.result)
                {
                    this.op1.reflectChange();
                    //M105モーダルを閉じる
                    $('.modal-backdrop').remove();
                    $('#' + this.id).modal('hide');
                    $("#C105-01").remove();
                }
                else
                {
                    this.message = response.data.values.message;
                }
            }).catch(error => {
                console.log(error.response);
            });
        },
        remandbuttonClick: async function() {
            //差戻事由の確認
            //半角、全角ブランクを除去した内容で入力チェック
            if(this.attendance_information['remand_reason'] == null || this.attendance_information['remand_reason'].replace(/\s+/g, "").length == 0)
            {
                this.message = '差戻事由を入力してください';
                return;
            }
            let info = await this.base64Encode(JSON.stringify(this.attendance_information));
            axios.post('m105_input_attendance_details_save', {
                params: {
                    info: info,
                    type: 3,// 申請:1 承認:2 差戻:3 申請取り消し:4
                }
            }).then(response => {
                if(response.data.result)
                {
                    this.op1.reflectChange();
                    //M105モーダルを閉じる
                    $('.modal-backdrop').remove();
                    $('#' + this.id).modal('hide');
                    $("#C105-01").remove();
                }
                else
                {
                    this.message = response.data.values.message;
                }
            }).catch(error => {
                console.log(error.response);
            });
        },
        remandandcancelapplybuttonClick: async function() {

            // 承認・申請取り消しの実施
            let info = await this.base64Encode(JSON.stringify(this.attendance_information));
            //DB更新処理(申請・承認取り消し)
            axios.post('m105_input_attendance_details_save', {
                params: {
                    info: info,
                    type: 8,// 承認・申請取り消し
                    closeStateId: this.op1.close_state_id,
                }
            }).then(response => {
                if(response.data.result)
                {
                    this.op1.updateViolation();
                    this.op1.reflectChange();
                    //M105モーダルを閉じる
                    $('.modal-backdrop').remove();
                    $('#' + this.id).modal('hide');
                    $("#C105-01").remove();
                }
                else
                {
                    this.message = response.data.values.message;
                }
            }).catch(error => {
                console.log(error.response);
            });

        },
        // 承認解除
        approveUnrecognizedButtonClick: async function()
        {
            let info = await this.base64Encode(JSON.stringify(this.attendance_information));
            axios.post('m105_input_attendance_details_save', {
                params: {
                    info: info,
                    type: 6,// 申請:1 承認:2 差戻:3 申請取り消し:4 仮申請:5 承認解除:6
                    closeStateId: this.op1.close_state_id,
                }
            }).then(response => {
                if(response.data.result)
                {
                    // this.op1.updateViolation();
                    this.op1.reflectChange();
                    //M105モーダルを閉じる
                    $('.modal-backdrop').remove();
                    $('#' + this.id).modal('hide');
                    $("#C105-01").remove();
                }
                else
                {
                    this.message = response.data.values.message;
                }
            }).catch(error => {
                console.log(error.response);
            });
        },

        cancelapplybuttonClick: async function() {
            //DB更新処理
            let info = await this.base64Encode(JSON.stringify(this.attendance_information));
            axios.post('m105_input_attendance_details_save', {
                params: {
                    info: info,
                    type: 4,// 申請:1 承認:2 差戻:3　申請取り消し:4
                    closeStateId: this.op1.close_state_id,
                }
            }).then(response => {
                if(response.data.result)
                {
                    this.op1.updateViolation();
                    this.op1.reflectChange();
                    //M105モーダルを閉じる
                    $('.modal-backdrop').remove();
                    $('#' + this.id).modal('hide');
                    $("#C105-01").remove();
                }
                else
                {
                    this.message = response.data.values.message;
                }
            }).catch(error => {
                console.log(error.response);
            });
        },
        validate: function(){
            //実績と申請日の関係、振替休日指定の場合の指定先の日の関係は、登録時にDB参照

            this.message = '';
            let over_time_class_array = this.attendance_information.over_time_class_array;
            let over_time_class_array_valid = [];
            let unemployed_array = this.attendance_information.unemployed_array;
            let unemployed_array_valid = [];

            let times_array = [];//時間被り検証用（就業・時間外）
            let times_array_un = [];//時間被り検証用（不就業）

            let is_need_check_dupulicate = true; //時間被り検証必要かどうかフラグ
            let master_data = this.getMasterData();//頻繁的に利用するから、一発で取得する

            //就業時間（連続性チェックのために0.1ずらす）
            times_array.push({time: this.attendance_information.work_zone_time_start + 0.1, num: times_array.length});
            times_array.push({time: this.attendance_information.work_zone_time_end - 0.1, num: times_array.length});

            //実績情報
            const work_achievement = this.getMasterData().work_achievement.find((elm) => elm.work_achievement_id == this.attendance_information.work_achievement_id);
            if(work_achievement?.is_not_register === 1 || (this.attendance_information.work_achievement_id == 0 && this.attendance_information.unemployed_id == 0))
            {
                //データ登録できない申請の時は、時間被りチェック不要
                is_need_check_dupulicate = false;
            }

            //勤務帯情報
            const work_zone = this.getMasterData().work_zone.find((elm) => elm.work_zone_id == this.attendance_information.work_zone_id);

            //時給申請の場合に、直接実績入力変更があったかどうかチェック
            let is_input_direct = false;
            if(work_zone && work_zone.work_zone_aggrigation_class === 2)
            {
                //勤務帯の時間と異なる場合
                if(this.attendance_information.work_zone_time_start !== this.attendance_information.work_time_start ||
                    this.attendance_information.work_zone_time_end !== this.attendance_information.work_time_end)
                {
                    is_input_direct = true;
                }
                //休憩時間合計が異なる場合
                else if(this.attendance_information.break_time !== work_zone.break_time ||
                    this.attendance_information.midnight_break_time !== work_zone.midnight_break_time)
                {
                    is_input_direct = true;
                }
            }

            //休日勤務の場合に、直接実績入力変更があったかどうかチェック
            if(this.attendance_information.work_achievement_id === 4 || this.attendance_information.work_achievement_id === 5 || this.attendance_information.work_achievement_id === 6)
            {
                //勤務帯の時間と異なる場合
                if(this.attendance_information.work_zone_time_start !== this.attendance_information.work_time_start ||
                    this.attendance_information.work_zone_time_end !== this.attendance_information.work_time_end)
                {
                    is_input_direct = true;
                }
                //休憩時間合計が異なる場合
                else if(this.attendance_information.break_time !== work_zone.break_time ||
                    this.attendance_information.midnight_break_time !== work_zone.midnight_break_time)
                {
                    is_input_direct = true;
                }
            }

            //実績変更・事由登録の入力検証
            if(this.attendance_information.work_achievement_id === 0 && this.attendance_information.work_holiday_id === 1)
            {
                //実績選択なし
                this.message = '実績を選択してください';
            }
            //休日の時は通常勤務は選択不可
            if(this.attendance_information.work_holiday_id !== 1 && this.attendance_information.work_achievement_id === 1)
            {
                this.message = '対象日が休日の場合は休日勤務を選択してください';
            }
            //通常勤務日は休日出勤は選択不可
            if(this.attendance_information.work_holiday_id === 1 && 
                (this.attendance_information.work_achievement_id === 4 || this.attendance_information.work_achievement_id === 5 || this.attendance_information.work_achievement_id === 6 || this.attendance_information.work_achievement_id === 7))
            {
                this.message = '対象日が出勤日の場合は休日勤務は選択できません';
            }
            //勤務帯が未選択でありデータ登録できる実績
            if(this.attendance_information.work_zone_id === 0 && (work_achievement?.is_not_register === 0))
            {
                //勤務帯選択なし
                this.message = '勤務帯を選択してください';
            }
            //申請事由（登録しないタイプの場合は事由不要
            //半角、全角ブランクを除去した内容で入力チェック
            if(((!this.attendance_information.information || this.attendance_information.information.replace(/\s+/g, "").length === 0) && work_achievement?.is_not_register === 0) || ((!this.attendance_information.information || this.attendance_information.information.replace(/\s+/g, "").length === 0) && this.attendance_information.violation_warning_id == 2))
            {
                //連絡事項
                this.message = '事由等を入力してください';
            }
            //振替休日選択不備
            if(this.attendance_information.work_achievement_id === 7)
            {
                //振替休日未選択
                if(!this.attendance_information.substitute_holiday_date || this.attendance_information.substitute_holiday_date === 0)
                {
                    this.message = '振替休日を選択してください';
                }
                if(this.attendance_information.substitute_holiday_date < this.getFirstDaySerialOfSpecifiedDateSerial(this.attendance_information.attendance_date))
                {
                    this.message = '振替休日は当月以降を選択してください';
                }
            }
            //休日の乖離申請
            if(this.attendance_information.violation_warning_id == 2 && (this.attendance_information.work_holiday_id === 2 || this.attendance_information.work_holiday_id === 3))
            {
                //休日の乖離申請の場合、実績と勤務帯の入力無しを許可する
                if(this.attendance_information.work_achievement_id === 0 && this.attendance_information.work_zone_id === 0){
                    if((this.message == '実績を選択してください') || (this.message == '勤務帯を選択してください')){
                        this.message = '';
                    }
                    //時間被りチェック不要
                    is_need_check_dupulicate = false;
                }
            }
            
            //不就業の入力検証
            //start_timeを基準として、昇順に並べ替える
            unemployed_array.sort(function(a, b){
                if (a.start_time > b.start_time) return 1;
                if (a.start_time < b.start_time) return -1;
                return 0;
            });
   
            for(let key in unemployed_array)
            {
                let is_empty = unemployed_array[key].is_empty(unemployed_array[key].panel_index);
                if(is_empty)
                {
                    //空は無視
                    continue;
                }
                //項目検証
                //届け出内容が未選択
                if(unemployed_array[key].unemployed_id === 0)
                {
                    this.message = '不就業時間の届け出内容が未選択の項目があります';
                    continue;
                }
                //開始時間か終了時間に未入力があるか、文字が入力されている
                if(unemployed_array[key].start_time === null || unemployed_array[key].end_time === null)
                {
                    this.message = '不就業時間に不正な文字が含まれているか未入力のものがあります';
                    continue;
                }
                //開始と終了時間の不整合
                if(unemployed_array[key].end_time < unemployed_array[key].start_time)
                {
                    this.message = '時間の指定に不正なものがあります';
                    continue;
                }
                //分の入力値が59より大きい
                if(unemployed_array[key].start_time_input_minutes > 59 || unemployed_array[key].end_time_input_minutes > 59)
                {
                    this.message = '時間の指定に不正なものがあります';
                    continue;
                }
                //所定時間以内
                if(is_input_direct)
                {
                    //1日単位の場合は所定時間と一致していることを比較
                    if(unemployed_array[key].unemployed_take_unit_class == 1)
                    {
                        if(unemployed_array[key].end_time != this.attendance_information.work_zone_time_end || this.attendance_information.work_zone_time_start != unemployed_array[key].start_time)
                        {
                            this.message = '勤務帯を変更した場合、不就業時間を修正してください';
                            continue;
                        }
                    }
                    //1日単位以外の場合は申請時間以内であることをチェック
                    else if(!(unemployed_array[key].end_time <= this.attendance_information.work_time_end && this.attendance_information.work_time_start <= unemployed_array[key].start_time))
                    {
                        this.message = '不就業時間は申請時間内を指定してください';
                        continue;
                    }
                }
                else
                {
                    //1日単位の場合は所定時間と一致していることを比較
                    if(unemployed_array[key].unemployed_take_unit_class == 1)
                    {
                        if(unemployed_array[key].end_time != this.attendance_information.work_zone_time_end || this.attendance_information.work_zone_time_start != unemployed_array[key].start_time)
                        {
                            this.message = '勤務帯を変更した場合、不就業時間を修正してください';
                            continue;
                        }
                    }
                    //直接入力以外は所定時間と比較
                    else if(!(unemployed_array[key].end_time <= this.attendance_information.work_zone_time_end && this.attendance_information.work_zone_time_start <= unemployed_array[key].start_time))
                    {
                        this.message = '不就業時間は所定時間内を指定してください';
                        continue;
                    }
                }
                //申請事由が空
                //半角、全角ブランクを除去した内容で入力チェック
                if(unemployed_array[key].request_reason.replace(/\s+/g, "").length == 0)
                {
                    this.message = '不就業時間の申請事由を入力してください';
                    continue;
                }
                //申請事由が50文字以上
                if(50 < unemployed_array[key].request_reason.length)
                {
                    this.message = '不就業時間の申請事由は50文字以内で入力してください';
                    continue;
                }
                //遅刻早退は1時間30分まで
                if(unemployed_array[key].unemployed_id === 3 || unemployed_array[key].unemployed_id === 5 || unemployed_array[key].unemployed_id === 8 || unemployed_array[key].unemployed_id === 9)
                {
                    if(90 < unemployed_array[key].unemployed_time)
                    {
                        this.message = '遅刻、早退は1時間30分以内で指定してください';
                        continue;
                    }
                }
                //半休区分の申請時間が1時間31分から5時間に収まっていない
                if(unemployed_array[key].unemployed_id === 2 || unemployed_array[key].unemployed_id === 7 || unemployed_array[key].unemployed_id === 11 || unemployed_array[key].unemployed_id === 13 || unemployed_array[key].unemployed_id === 18)
                {
                    if(unemployed_array[key].unemployed_time < 91 || 300 < unemployed_array[key].unemployed_time)
                    {
                        this.message = '申請時間は1時間31分から5時間の間で指定してください';
                        continue;
                    }
                }
                //時給申請の場合に、1日不就業で登録されているのに始業、終業、休憩時間が入力されている
                if(work_zone && work_zone.work_zone_aggrigation_class === 2 && unemployed_array[key].unemployed_take_unit_class === 1)
                {
                    if(this.attendance_information.work_time_start > 0 || this.attendance_information.work_time_end > 0 || this.attendance_information.break_time > 0 || this.attendance_information.midnight_break_time > 0)
                    {
                        this.message = '不就業を選択している場合は始業、終業、休憩時間は0にしてください';
                        continue;
                    }
                }
                //時間被り検証用に追加
                times_array_un.push({time: unemployed_array[key].start_time, num: times_array_un.length});
                times_array_un.push({time: unemployed_array[key].end_time, num: times_array_un.length});

                unemployed_array_valid.push(unemployed_array[key]);
            }
            //時間外・控除
            //over_start_timeを基準として、昇順に並べ替える
            over_time_class_array.sort(function(a, b){
                if (a.over_time_start > b.over_time_start) return 1;
                if (a.over_time_start < b.over_time_start) return -1;
                return 0;
            });
            
            for(let key in over_time_class_array)
            {
                let is_empty = over_time_class_array[key].is_empty(over_time_class_array[key].panel_index);
                if(is_empty)
                {
                    //空は無視
                    continue;
                }
                //届け出内容が未選択
                if(over_time_class_array[key].over_time_class_id == 0)
                {
                    this.message = '時間外時間の届け出内容が未選択の項目があります';
                    continue;
                }
                //開始時間か終了時間に未入力があるか、文字が入力されている
                if(over_time_class_array[key].over_time_start === null  || over_time_class_array[key].over_time_end === null)
                {
                    this.message = '時間外時間に不正な文字が含まれているか未入力のものがあります';
                    continue;
                }
                //開始と終了時間の不整合
                if(over_time_class_array[key].over_time_end < over_time_class_array[key].over_time_start)
                {
                    this.message = '時間の指定に不正なものがあります';
                    continue;
                }
                //時間外時間より休憩時間の合計の方が多い
                if(over_time_class_array[key].over_time_end - over_time_class_array[key].over_time_start < (over_time_class_array[key].over_time_rest_time + over_time_class_array[key].over_time_midnight_rest_time))
                {
                    this.message = '時間外時間よりも多い休憩時間が入力されています';
                    continue;
                }
                //控除時間が時間外時間よりも多い
                if(over_time_class_array[key].over_time_end - over_time_class_array[key].over_time_start < over_time_class_array[key].deduction_time + over_time_class_array[key].over_time_rest_time + over_time_class_array[key].over_time_midnight_rest_time)
                {
                    this.message = '休憩時間と控除時間の合計が時間外時間合計を超えています';
                    continue;
                }
                //申請時間と控除時間が同じ時間でない場合
                if((over_time_class_array[key].over_time_end - over_time_class_array[key].over_time_start) != over_time_class_array[key].deduction_time + over_time_class_array[key].over_time_rest_time)
                {
                    //申請事由が空
                    //半角、全角ブランクを除去した内容で入力チェック
                    if(over_time_class_array[key].over_time_reason == null || over_time_class_array[key].over_time_reason.replace(/\s+/g, "").length == 0)
                    {
                        this.message = '時間外時間の申請事由を入力してください';
                        continue;
                    }
                }
                //控除区分、事由、時間いずれかに入力がある
                if(!(over_time_class_array[key].deduction_reason_id == 0 && (over_time_class_array[key].deduction_time == null || over_time_class_array[key].deduction_time == 0) && over_time_class_array[key].deduction_reason.length == 0))
                {
                    //控除区分か時間どちらかが未入力の場合
                    if(over_time_class_array[key].deduction_reason_id == 0 || over_time_class_array[key].deduction_time <= 0)
                    {
                        this.message = '控除時間の入力に不備があります';
                        continue;
                    }
                    //申請事由のみの場合はNG
                    if(over_time_class_array[key].deduction_reason_id == 0 && (over_time_class_array[key].deduction_time == null || over_time_class_array[key].deduction_time <= 0) && over_time_class_array[key].deduction_reason.length != 0)
                    {
                        this.message = '控除時間の入力に不備があります';
                        continue;
                    }
                    //控除事由区分が「その他」の場合、申請事由が必須
                    //半角、全角ブランクを除去した内容で入力チェック
                    if(over_time_class_array[key].deduction_reason_id == 5 && over_time_class_array[key].deduction_time != null && over_time_class_array[key].deduction_time != 0 && over_time_class_array[key].deduction_reason.replace(/\s+/g, "").length == 0)
                    {
                        this.message = '控除事由区分が「その他」の時は事由を入力してください';
                        continue;
                    }
                   
                    //申請事由が50文字以上
                    if(50 < over_time_class_array[key].deduction_reason.length)
                    {
                        this.message = '控除時間の申請事由は50文字以内で入力してください';
                        continue;
                    }
                }
                //「外勤営業時間外」開始時刻は勤務終了時刻から規定した時間以上間隔
                if(over_time_class_array[key].over_time_class_id == 3 
                && over_time_class_array[key].over_time_start < this.attendance_information.work_zone_time_end + this.attendance_information.employee.deviation_time_after_end_time.allow_after_end_time)
                {
                    this.message = `「外勤営業時間外」開始時刻は所定就業時刻から${this.attendance_information.employee.deviation_time_after_end_time.allow_after_end_time}分以上間隔をあけてください`;
                    continue;
                }
                //時間被り検証用に追加
                times_array.push({time: over_time_class_array[key].over_time_start, num: times_array.length});
                times_array.push({time: over_time_class_array[key].over_time_end, num: times_array.length});

                over_time_class_array_valid.push(over_time_class_array[key]);
            }
            
            //不就業の登録がある場合、実績は空とする、事由の入力無しを許可する
            if(unemployed_array_valid.length != 0){
                if((this.message == '事由等を入力してください') || (this.message == '実績を選択してください')){
                    this.message = '';
                }
                //休日の場合は、実績必須
                if(this.attendance_information.work_holiday_id != 1)
                {
                    if(this.attendance_information.work_achievement_id == 0){
                        this.message = '休日出勤時には実績を選択してください';
                    }
                }
                else
                {
                    //休日以外の場合は実績登録不可
                    if(this.attendance_information.work_achievement_id != 0){
                        this.message = '不就業を登録するときは、実績を登録できません';
                    }
                }
            }

            //残業・控除の登録がある場合、事由の入力無しを許可する
            //当日以前で乖離なしの場合、事由の入力無しを許可する
            if( (over_time_class_array_valid.length != 0) ||
                (this.attendance_information.violation_warning_id == 1 && this.attendance_information.attendance_date < this.todaySerial()) && this.attendance_information.work_achievement_id == 1){
                if(this.message == '事由等を入力してください'){
                    this.message = '';
                }
            }

            //直接入力時の入力不備チェック（既にエラーがある場合はここの検証は不要）
            if(is_input_direct && this.message.length == 0)
            {
                //開始、終了チェック
                if(this.attendance_information.work_time_start == null || 
                    this.attendance_information.work_time_end == null)
                {
                    this.message = '始業時刻もしくは終業時刻が正しく入力されていません';
                }
                //不就業登録されていない、もしくは不就業が１日のものでない場合
                if(unemployed_array_valid.length == 0 || (unemployed_array_valid.length != 0 && unemployed_array_valid[0].unemployed_take_unit_class != 1))
                {
                    //申請時間は必ず入力必要
                    if(this.attendance_information.work_time_start == 0 && this.attendance_information.work_time_end == 0)
                    {
                        this.message = '申請時間を入力してください';
                    }
                    //通常・休日休憩が入力されている時に、通常・休日実働時間がゼロではダメ
                    if((this.attendance_information.actual_work_time + this.attendance_information.holiday_work_time - (this.attendance_information.exclude_actual_work_time | 0) + (this.attendance_information.additional_actual_work_time | 0)) == 0
                        && 0 < (this.attendance_information.break_time + this.attendance_information.holiday_work_break_time - (this.attendance_information.exclude_rest_time | 0) + (this.attendance_information.additional_break_time | 0)))
                    {
                        this.message = '休憩時間は実働時間合計より小さい値を入力してください';
                    }
                    //深夜・休日深夜休憩が入力されている時に、深夜・休日深夜実働時間がゼロではダメ
                    if((this.attendance_information.midnight_time + this.attendance_information.holiday_midnight_work_time - (this.attendance_information.exclude_midnight_actual_work_time | 0) + (this.attendance_information.additional_midnight_time | 0)) == 0
                        && 0 < (this.attendance_information.midnight_break_time + this.attendance_information.holiday_midnight_work_break_time - (this.attendance_information.exclude_midnight_rest_time | 0) + (this.attendance_information.additional_midnight_break_time | 0)))
                    {
                        this.message = '深夜休憩時間は深夜実働時間合計より小さい値を入力してください';
                    }
                }
            }
            
            //時給の場合は時間外は申請不可
            if(work_zone && work_zone.work_zone_aggrigation_class === 2 && over_time_class_array_valid.length !== 0)
            {
                this.message = '実績を直接入力した場合、時間外の登録はできません';
            }

            //勤務帯が選択されておりデータ登録できない実績
            if(this.attendance_information.work_zone_id !== 0 && (work_achievement?.is_not_register === 1))
            {
                this.message = 'データ登録できない実績の場合、勤務帯は登録できません';
            }

            //データ登録できない実績の場合は不就業と時間外は申請不可
            if((work_achievement?.is_not_register === 1) && (over_time_class_array_valid.length !== 0 || unemployed_array_valid.length !== 0))
            {
                this.message = 'データ登録できない実績の場合、不就業と時間外の登録はできません';
            }

            //休日の乖離申請の場合は不就業と残業は申請不可
            if(this.attendance_information.violation_warning_id == 2 && (this.attendance_information.work_holiday_id === 2 || this.attendance_information.work_holiday_id === 3))
            {
                if(over_time_class_array_valid.length !== 0 || unemployed_array_valid.length !== 0){
                    this.message = '休日の乖離申請の場合、不就業と時間外の登録はできません';
                }
            }

            //ここでメッセージ登録されていたらreturn
            if(this.message.length !== 0)
            {
                return false;
            }
            
            //時間被り検証フラグが立っている場合は、時間被りチェック実施
            let work_time_start = 0;
            let work_time_end = 0;
            if(is_need_check_dupulicate)
            {
                //時間被りが無いか検証
                times_array.sort(function(a, b){
                    if (a.time > b.time) return 1;
                    if (a.time < b.time) return -1;
                    return 0;
                });
                for(let i = 0; i < times_array.length; i = i + 2)
                {
                    if(Number(times_array[i].num) + 1 != Number(times_array[i + 1].num))
                    {
                        //並んでいない＝時間が被っている
                        this.message = '時間外時間の申請時間で重複している箇所があります';
                        continue;
                    }
                    //時間が連続であること
                    //外勤営業時間外の場合、開始時間は勤務終了時間と離れていても、登録できるようにする
                    if(i + 2 < times_array.length)
                    {
                        let over_time_class_array_index = (times_array.length-2) / ((times_array.length-2) / i) / 2;
                        if(over_time_class_array[over_time_class_array_index].over_time_class_id != 3) {
                            if(Math.round(Number(times_array[i + 1].time)) != Math.round(Number(times_array[i + 2].time)))
                            {
                                //隣り合った時間が一緒でない＝連続していない
                                this.message = '時間外時間の指定が不正な箇所があります';
                                continue;
                            }
                        }
                    }
                }
                times_array_un.sort(function(a, b){
                    if (a.time > b.time) return 1;
                    if (a.time < b.time) return -1;
                    return 0;
                });
                for(let i = 0; i < times_array_un.length; i = i + 2)
                {
                    //順番であること
                    if(Number(times_array_un[i].num) + 1 != Number(times_array_un[i + 1].num))
                    {
                        //並んでいない＝時間が被っている
                        this.message = '不就業時間の申請時間で重複している箇所があります';
                        continue;
                    }
                }

                work_time_start = Math.round(times_array[0].time);
                work_time_end = Math.round(times_array[times_array.length - 1].time);

                for(let i = 0; i < times_array_un.length - 1; i = i + 2)
                {
                    if(Math.round(times_array_un[i].time) == work_time_start){
                        work_time_start = Math.round(times_array_un[i + 1].time);
                    }
                    if(i == times_array_un.length - 2){
                        break;
                    }
                    if(Math.round(times_array_un[i + 1].time) != Math.round(times_array_un[i + 2].time)){
                        break;
                    }
                }
                for(let i = times_array_un.length - 1; i > 0; i = i - 2)
                {
                    if(Math.round(times_array_un[i].time) != work_time_end){
                        break;
                    }else{
                        work_time_end = Math.round(times_array_un[i - 1].time);
                    }
                    if(i == 1){
                        break;
                    }
                    if(Math.round(times_array_un[i - 1].time) != Math.round(times_array_un[i - 2].time)){
                        break;
                    }
                }

                if(times_array.length == 2 && work_time_end == Math.round(times_array[0].time) && work_time_start == Math.round(times_array[1].time)){
                    work_time_start = 0;
                    work_time_end = 0;
                }
            }

            //メッセージ登録されていなければOK
            if(this.message.length === 0)
            {
                //不就業と時間外の配列をセット
                this.attendance_information['over_time_class_array_valid'] = over_time_class_array_valid;
                this.attendance_information['unemployed_array_valid'] = unemployed_array_valid;
                //直接入力ではない場合、申請時間の始業就業を調整
                if(!is_input_direct)
                {
                    this.attendance_information['work_time_start'] = work_time_start;
                    this.attendance_information['work_time_end'] = work_time_end;
                }
                return true;
            }
            else
            {
                return false;
            }
        },
    },
    mounted(){
        axios.get('m105_input_attendance_detail', {
            params:{
                'attendanceInformationId': this.op1.attendance_information_id,
            }
        }).then(response => {
            if(response.data.result)
            {
                this.attendance_information = response.data.values.attendance_information;

                //状態によりボタン制御
                //事業所締めされたら登録、承認、差戻し、申請取り消しできない
                if(this.attendance_information.close_state_id > 3){
                    this.isEnableApply = false;
                    this.isEnableCancelApply = false;
                    this.isEnableApprove = false;
                    this.isEnableRemand = false;
                    this.isEnableApplyAndApprove = false;
                    this.op1.isRemand = false;
                }
                //管理者締めされたら登録、承認、申請取り消しできない
                else if(this.attendance_information.close_state_id == 3){
                    this.isEnableApply = false;
                    this.isEnableCancelApply = false;
                    this.isEnableApprove = false;
                    this.isEnableApplyAndApprove = false;
                    this.op1.isRemand = false;
                    this.isEnableApproveUnrecognized = true;
                }
                //申請状態が初期状態（未申請状態）は承認、差戻し、申請取り消しできない
                else if(this.attendance_information.approval_state_id == 1){
                    this.isEnableCancelApply = false;
                    this.isEnableApprove = false;
                    this.isEnableRemand = false;
                    this.op1.isRemand = false;
                }
                //申請状態が申請中は申請・承認、申請・承認解除できない
                else if(this.attendance_information.approval_state_id == 2){
                    this.isEnableApplyAndApprove = false;
                    this.op1.isRemand = false;
                }
                //申請状態が承認済みは承認、申請、申請・承認、申請取り消しできない
                else if(this.attendance_information.approval_state_id == 3)
                {
                    this.isEnableApprove = false;
                    this.isEnableApply = false;
                    this.isEnableCancelApply = false;
                    this.isEnableApproveUnrecognized = true;
                    this.isEnableApplyAndApprove = false;
                }
                //申請状態が差戻しは承認できない
                else if(this.attendance_information.approval_state_id == 4){
                    this.isEnableApprove = false;
                    this.isEnableApplyAndApprove = false;
                    this.op1.isRemand = false;
                //申請状態が仮申請は承認、差戻しできない
                }else if(this.attendance_information.approval_state_id == 5){
                    this.isEnableApprove = false;
                    this.isEnableRemand = false;
                }
            }
            else
            {
                //取得失敗
            }
        }).catch(error =>{
            console.log(error.response);
        });
    }
};
</script>