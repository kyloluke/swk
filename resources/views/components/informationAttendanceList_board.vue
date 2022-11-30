<template>
    <div>
        <loading :active.sync="isLoading"
            :can-cancel="true"
            :on-cancel="onCancel"
            :is-full-page="fullPage"></loading> 
        <div id="C014-02-02-10" class="card shadow w-100 h-100" :class="$style[backgroundClass]">
            <div class="card-body card-body-page" v-if="isExistData">
                <div class="card-button">
                <button style="font-size:11pt;width:60pt" class="btn btn-primary ml-3" v-on:click="onClickUpdate()">更新</button>
                <button style="font-size:11pt;width:100pt;margin-left:20px;" class="btn btn-primary ml-3" v-on:click="openWebPunchClockModal()">WEB打刻一覧</button>
                <span v-if="isManagerButton">
                    <button style="font-size:11pt;width:100pt;margin-left:20px;" class="btn ml-3" :class="changeWorkHolidaybuttonTypeClass" v-on:click="changeWorkHolidayMode()" v-text="changeWorkHolidayText" :disabled="this.information_attendance_mode!=1"></button>
                </span>
                <span v-if="isChangeWorkHolidayModeButton">
                    <button style="font-size:11pt;width:130pt;margin-left:20px;" class="btn btn-danger ml-3" v-on:click="cancelWorkHolidayMode()">出休変更キャンセル</button>
                </span>
                <span>
                    <button style="font-size:11pt;width:130pt;margin-left:20px;" class="btn ml-3" :class="changeSubstitutebuttonTypeClass" v-on:click="changeSubstituteMode()" v-text="changeSubstituteText"></button>
                </span>
                <span v-if="isChangeSubstituteModeButton">
                    <button style="font-size:11pt;width:150pt;margin-left:20px;" class="btn btn-danger ml-3" v-on:click="cancelSubstituteMode()">振替休日変更キャンセル</button>
                </span>
                <span v-if="isAllApplyModeButton && !isChangeSubstituteModeButton">
                    <button style="font-size:11pt;width:150pt;margin-left:20px;" class="btn btn-primary ml-3" v-on:click="OpenBunchApplicationModal()" v-tooltip="'チェックした日を　　<br>全て同じ内容で登録'">一括登録開始</button>
                    <button style="font-size:11pt;width:150pt;margin-left:20px;" class="btn btn-primary ml-3" v-on:click="application()" v-tooltip="'チェックした日を申請する<br>（申請のみ）　　　　　　'">申請</button>
                </span>
                <button v-if="isManagerButton" style="font-size:11pt;width:140pt;margin-left:20px;" class="btn btn-danger" v-on:click="applyCheckedDays(attendanceAggregate.close_state_id)" :disabled="is_nocheck">選択した日付を承認</button>
                </div>
                <div style="margin-right:12.5pt;">
                <!-- <table class="table-record-time-head" style="margin-top:20pt;font-size:9pt;font-weight: 100;"> -->
                <table class="table-record-time" style="margin-top:20pt;font-size:9pt;font-weight: 100;">
                <!-- <table class="table-record-time-head"> -->
                    <thead>
                        <tr class="text-white" :class="$style[listColorClass]">
                            <th v-if="isManager" style="width:3%;font-weight: 100;" rowspan="2" class="px-2">
                                <select v-if="isEnableCheckAtLeastOne" v-model="select_all_type" style="width: 50px">
                                    <option value="0"></option>
                                    <option value="1">全て</option>
                                    <option value="2">平日</option>
                                    <option value="3">休日</option>
                                </select>
                            </th>
                            <th v-if="isBunchApplication" style="width:3%;font-weight: 100;" rowspan="2" class="px-2">
                                <select v-if="isEnableBunchApplicationAtLeastOne" v-model="select_all_type" style="width: 50px">
                                    <option value="0"></option>
                                    <option value="1">全て</option>
                                    <option value="2">平日</option>
                                    <option value="3">休日</option>
                                </select>
                            </th>
                            <th class="do-apply" rowspan="2">申請</th>
                            <th class="title-date" rowspan="2" colspan="3">日付</th>
                            <th v-if="!isChangeWorkHolidayMode && !isChangeSubstituteMode" class="actiual-result-1" rowspan="2">実績</th>
                            <th v-if="isChangeWorkHolidayMode || isChangeSubstituteMode" class="actiual-result-2" rowspan="2">実績</th>
                            <th class="not-employed" rowspan="2">不就業</th>
                            <th class="app-time" colspan="3">申請時間</th>
                            <th class="punch-time" colspan="2">打刻時間</th>
                            <th class="actiual-work" rowspan="2">実働</th>
                            <th class="off-hours" rowspan="2">時間外</th>
                            <th class="deduction" rowspan="2">控除</th>
                            <th class="holiday-work" rowspan="2">休日勤務</th>
                            <th class="midnight" rowspan="2">深夜</th>
                            <th class="absence" rowspan="2">欠勤</th>
                            <th class="contact-reason-1" v-if="!isChangeWorkHolidayMode && !isChangeSubstituteMode" rowspan="2">連絡事項・事由等</th>
                            <th class="contact-reason-2" v-if="isChangeWorkHolidayMode || isChangeSubstituteMode" rowspan="2">連絡事項・事由等</th>
                            <th class="approver" rowspan="2">承認者</th>
                            <th class="author" rowspan="2">入力者</th>
                        </tr>
                        <tr class="text-white" :class="$style[listColorClass]">
                            <th class="work-zone">勤務帯</th>
                            <th class="start-of-work">始業</th>
                            <th class="end-of-work">終業</th>
                            <th class="start-of-work-2">始業</th>
                            <th class="end-of-work-2">終業</th>
                        </tr>
                    </thead>
                </table>
                </div>
                <div class="table-record-detail">
                <table class="table-record-time" style="font-size:9pt;font-weight: 100;">
                    <tbody>
                        <tr v-for="item in attendanceInformationInfoList" :key="item.attendance_date">
                            <td :class="$style[item.list_background_class]" v-if="isManager" class="px-2" style="width:3%;font-weight: 100;"><input style="width: 50px" type="checkbox" v-model="item.isChecked" v-if="item.isEnableCheck" v-on:change="chkClick()"/></td>
                            <td :class="$style[item.list_background_class]" v-if="isBunchApplication" class="px-2" style="width:3%;font-weight: 100;"><input style="width: 50px" type="checkbox" v-model="item.isChecked" v-if="item.isEnableBunchApplication" v-on:change="chkClick()"/></td>
                            <td :class="[$style[item.list_background_class],fontcolor(item.approval,item.close_state_id) ? $style.fontcolor_red : null]" style="width:4%;font-weight: 100;">{{item.approval}}</td>
                            <td :class="$style[item.list_background_class]" style="cursor: pointer; color: blue; text-decoration: underline; text-decoration-color: blue;width:2%;font-weight: 100;" v-on:click="openDailyReportModal(item.option,item.date)">{{item.date}}</td>
                            <td :class="$style[item.list_background_class]" style="width:2%;font-weight: 100;">{{item.week}}</td>
                            <td :class="$style[item.list_background_class]" style="width:2%;font-weight: 100;">{{item.work_holiday_short_name}}</td>
                            <td v-if="isChangeWorkHolidayMode && item.isEnableChangeWorkHoliday" :class="$style[item.list_background_class]" style="width:8%;font-weight: 100;">
                                <select style="border:none;" class="form-control" v-model="item.work_achievement_id" v-bind:disabled="!item.isEnableChangeWorkHoliday" v-on:change="changeWorkAchievement(item)">
                                    <option value = 1>通常</option>
                                    <option value = 0>休日</option>
                                </select>
                            </td>
                            <td v-if="isChangeSubstituteMode && item.isEnableChangeSubstitute" :class="$style[item.list_background_class]" style="width:8%;font-weight: 100;">
                                <select style="border:none;" class="form-control" v-model="item.work_achievement_id" v-bind:disabled="!item.isEnableChangeSubstitute">
                                    <option v-if="item.work_achievement_id == 0" value = 0>通常</option><!-- 不就業や残業あり場合は実績は0になっていても、ここのデフォルト値は「通常」で表示する、だけど編集できないようにする、0を維持すること -->
                                    <option v-else value = 1>通常</option>
                                    <option value = 9>振休</option>
                                </select>
                            </td>
                            <td v-if="!isChangeWorkHolidayMode && !isChangeSubstituteMode" :class="$style[item.list_background_class]" style="width:4%;font-weight: 100;">{{item.work_achievement_name}}</td>
                            <td v-if="(isChangeWorkHolidayMode && !item.isEnableChangeWorkHoliday) || (isChangeSubstituteMode && !item.isEnableChangeSubstitute)" :class="$style[item.list_background_class]" style="width:8%;font-weight: 100;">{{item.work_achievement_name}}</td>
                            <td :class="$style[item.list_background_class]" style="width:4%;font-weight: 100;">{{item.unemployed_name}}</td>
                            <td :class="$style[item.list_background_class]" style="width:10%;font-weight: 100;">{{item.work_zone_name}}</td>
                            <td :class="$style[item.list_background_class]" style="width:4%;font-weight: 100;">{{item.work_time_start}}</td>
                            <td :class="$style[item.list_background_class]" style="width:4%;font-weight: 100;">{{item.work_time_end}}</td>
                            <td :class="$style[item.web_punch_clock_start_time_background_class]" style="width:5%;font-weight: 100;">{{item.web_punch_clock_time_start}}</td>
                            <td :class="$style[item.web_punch_clock_end_time_background_class]" style="width:5%;font-weight: 100;">{{item.web_punch_clock_time_end}}</td>
                            <td :class="$style[item.list_background_class]" style="width:4%;font-weight: 100;">{{item.actual_work_time}}</td>
                            <td :class="$style[item.list_background_class]" style="width:4%;font-weight: 100;">{{item.working_time}}</td>
                            <td :class="$style[item.list_background_class]" style="width:4%;font-weight: 100;">{{item.deduction_time}}</td>
                            <td :class="$style[item.list_background_class]" style="width:4%;font-weight: 100;">{{item.holiday_work_time}}</td>
                            <td :class="$style[item.list_background_class]" style="width:4%;font-weight: 100;">{{item.midnight_time}}</td>
                            <td :class="$style[item.list_background_class]" style="width:4%;font-weight: 100;">{{item.absent_time}}</td>
                            <td v-if="!(isChangeWorkHolidayMode || isChangeSubstituteMode)" :class="$style[item.list_background_class]" style="width:16%;font-weight: 100;">{{item.information}}</td>
                            <td v-if="isChangeWorkHolidayMode || isChangeSubstituteMode" :class="$style[item.list_background_class]" style="width:12%;font-weight: 100;">{{item.information}}</td>
                            <td :class="$style[item.list_background_class]" style="width:6%;font-weight: 100;">{{item.approval_employee_name}}</td>
                            <td :class="$style[item.list_background_class]" style="width:6%;font-weight: 100;">{{item.input_employee_name}}</td>
                        </tr>
                    </tbody>
                </table>
                </div>
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
import Loading from 'vue-loading-overlay';
import 'vue-loading-overlay/dist/vue-loading.css';
import VTooltip from 'v-tooltip'

export default {
    name: "informationAttendanceList_board",
    components: {
        "loading":Loading
    },
    props:{
        employee_id: Number,
        year_month: Number,
        is_manager: Boolean,
        background_type: {type: Number, default: 1},
        information_attendance_mode: {type: Number, default: 0},
        session_data: Object,
        input_attendance: Object,
        updateInputAttendanceBoard: Function,
        is_selected_target: Boolean,
    },
    data() {
        return {
            isLoading: false,  //ローディング画面用プロパティ
            fullPage: true,    //ローディング画面用プロパティ
            yearMonth: 0,
            employeeID: 0,
            attendanceInformationInfoList: [],
            employeeInfo: [],
            isManager: false,
            isCheckedCheckAll: false, // 全てチェックされてるか判定用
            isBunchApplication: false,
            backgroundType: 1,
            isChangeWorkHolidayMode: false,
            isChangeSubstituteMode: false,
            attendanceAggregate: [],
            remainingHolidayDays: 0,
            remainingHolidayHalfDays: 0,
            unusedAccumulatedPaidLeaveDays: 0,
            unusedAccumulatedPaidLeaveHalfDays: 0,
            isRemand: false,
            informationAttendanceMode: 0,
            modalOptionThemselves: {
                message: '本人締めエラー。すべての日付が申請されていることを確認してください。',
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
            modalOptioncheckedHoliday: {
                message: '有休数エラー。有休申請数が有休残数を超えないことを確認してください。',
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
            modalOptionManager: {
                message: '管理者締めエラー。すべての日付が承認されていることを確認してください。',
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
            modalOptionchecked: {
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
            applicationChecked: {
                message: '申請しました。',
                buttons:[{
                        exec : ()=>{
                            this.onClickUpdate();
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
            workHolidayChanged: {
                message: '出休変更登録しました。',
                buttons:[{
                        exec : ()=>{
                            this.btn="OK"; //ボタンが押された時の処理をここに記載
                            //モーダルを閉じる
                            $('body').removeClass('modal-open');
                            this.cleanModal();
                            $('.modal-backdrop').remove();
                            //勤怠一覧表取得
                            this.updateInputAttendanceBoard(true);
                        },
                        caption : "OK",  //'OK','はい','いいえ','キャンセル','再試行','閉じる'
                        btnclass : "btn-success"
                    }],
            },
            substituteChanged: {
                message: '振替休日変更登録しました。',
                buttons:[{
                        exec : ()=>{
                            this.btn="OK"; //ボタンが押された時の処理をここに記載
                            //モーダルを閉じる
                            $('body').removeClass('modal-open');
                            this.cleanModal();
                            $('.modal-backdrop').remove();
                            //勤怠一覧表取得
                            this.updateInputAttendanceBoard(true);
                        },
                        caption : "OK",  //'OK','はい','いいえ','キャンセル','再試行','閉じる'
                        btnclass : "btn-success"
                    }],
            },
            modalOptionApply: {
                message: '承認しました',
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
            is_nocheck: true,
            select_all_type: 0,
            isEnableCheckNum: 0, // isManager = trueの場合、チェックボックス表示させる行数トータル
            isEnableBunchApplicationNum: 0, // isBunchApplication = true の場合、チェックボックス表示させる行数トータル
        };
    },
    mounted() {
        Vue.use(VTooltip);
    },
    methods:{
        //本人締め
        closeThemselves(){
            let remainingHolidayDayFlg = this.remainingHolidayDays + (this.remainingHolidayHalfDays / 2) >= this.holidayDaysCount + (this.holidayHalfDaysCount / 2);
            let unusedAccumulatedPaidLeaveDayFlg = this.unusedAccumulatedPaidLeaveDays + (this.unusedAccumulatedPaidLeaveHalfDays / 2) >= this.accumulatedHolidayDaysCount + (this.accumulatedHolidayHalfDaysCount / 2);
            if(remainingHolidayDayFlg && unusedAccumulatedPaidLeaveDayFlg){
                axios.get('closeThemselves', {
                    //年月を6桁で送信
                    params:{
                        'yearMonth' : this.yearMonth,
                        'employeeID': this.employeeID,
                    }
                }).then(response => {
                    if(response.data.result)
                    {
                        this.updateInputAttendanceBoard(true);

                    }else{
                        if(typeof(response.data.values.message) != "undefined"){
                            //取得可能な振替休日よりも多くの振替休日を取得しようとしている場合
                            this.modalOptionThemselves.message = response.data.values.message;
                            this.openModal("m112_common_message", "", this.modalOptionThemselves);
                        }
                    }
                });
            }
            else{
                //有休の数が有休残数を超えた場合
                this.openModal("m112_common_message", "", this.modalOptioncheckedHoliday);
            }
        },
        //管理者締め・管理者締め解除
        closeManager(close_state_id){
            if(close_state_id >= 2){
                axios.get('closeManager', {
                    //年月を6桁で送信
                    params:{
                        'yearMonth' : this.yearMonth,
                        'employeeID': this.employeeID,
                        'close_state_id': close_state_id,
                    }
                }).then(response => {
                    if(response.data.result)
                    {
                        if(response.data.close_flg){
                            //勤怠入力の他のボード含め表示更新
                            this.updateInputAttendanceBoard(true);
                        }
                        else{
                            //DB検証し本人締めできる状態でなかった場合
                            this.openModal("m112_common_message", "", this.modalOptionManager);
                        }
                    }
                });
            }
        },

        //更新ボタン押す
        async onClickUpdate()
        {
            //勤怠入力の他のボード含め表示更新
            await this.updateInputAttendanceBoard(true);
            //休暇情報取得
            await this.getAbsentList();
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
                if(response.data.result)
                {
                }
                ret = response.data.result
            });
            return ret;
        },
        // onClickCheckAll(){
        //     //チェック状態をあわせる
        //     this.attendanceInformationInfoList.forEach((e)=>{
        //         this.$set(e, 'isChecked', !this.isCheckedCheckAll);//なぜか反転させなければいけない・・・？
        //     });
        //     this.chkClick();
        // },
        openWebPunchClockModal(){
            const option = {
                employee_id: this.employeeID,
                year_month: this.yearMonth,
                reflectChange: this.onClickUpdate,
                isManager: this.isManager,
            }
            //cleanしてから開く
            this.cleanModal();
            this.openModal('m104_record_time_list', 'modal-xl', option);
        },
        changeWorkHolidayMode(){
            if(this.isChangeWorkHolidayMode){
                this.isLoading = true;
                axios.post('change_work_holiday', {
                    params: {
                        attendanceInformationInfoList: this.attendanceInformationInfoList,
                        employee_id: this.employeeID
                    }
                }).then(response => {
                    if(response.data.result)
                    {
                        //更新処理
                        this.updateViolationWarningId();
                        
                        this.isChangeWorkHolidayMode = false;
                        this.openModal("m112_common_message", "", this.workHolidayChanged);
                    }
                    else
                    {
                        //選択がない状態でリクエストなど行われた場合
                        this.modalOptionchecked.message = response.data.values.message;
                        this.openModal("m112_common_message", "", this.modalOptionchecked);
                    }
                    this.isLoading = false;
                }).catch(error => {
                    console.log(error.response);
                    this.isLoading = false;
                });
            }else{
                this.isChangeWorkHolidayMode = true;
            }
        },
        cancelWorkHolidayMode(){
            this.isChangeWorkHolidayMode = false;
            this.attendanceInformationInfoList.forEach((data)=>{
                data.work_achievement_id = data.backup_work_achievement_id;
                data.work_holiday_id = data.backup_work_holiday_id;
            });
        },
        cancelSubstituteMode(){
            this.isChangeSubstituteMode = false;
            this.attendanceInformationInfoList.forEach((data)=>{
                data.work_achievement_id = data.backup_work_achievement_id;
                data.work_holiday_id = data.backup_work_holiday_id;
            });
        },
        changeSubstituteMode(){
            if(this.isChangeSubstituteMode){
                this.isLoading = true;
                axios.post('change_substitute', {
                    params: {
                        attendanceInformationInfoList: this.attendanceInformationInfoList,
                        employee_id: this.employeeID,
                        year_month:this.yearMonth,
                    }
                }).then(response => {
                    if(response.data.result)
                    {
                        //更新処理
                        this.updateViolationWarningId();
                        
                        this.isChangeSubstituteMode = false;
                        this.openModal("m112_common_message", "", this.substituteChanged);
                    }
                    else
                    {
                        //選択がない状態でリクエストなど行われた場合
                        this.modalOptionchecked.message = response.data.values.message;
                        this.openModal("m112_common_message", "", this.modalOptionchecked);
                    }
                    this.isLoading = false;
                }).catch(error => {
                    console.log(error.response);
                    this.isLoading = false;
                });
            }else{
                this.isChangeSubstituteMode = true;
            }
        },
        webPunchClockTimeColorClass:function(dayInfo, violationInfo){
            if(dayInfo.close_state_id > 2)
            {
                //管理者締め以降
                return "list_background_gray";
            }
            if(violationInfo){
                //乖離あり
                return "list_background_red";
            }
            if(dayInfo.violation_warning_id !== 0 && dayInfo.violation_warning_id !== 1)
            {
                //違反警告状態あり
                return "list_background_yellow";
            }
            //
            switch(dayInfo.approval_state_id)
            {
                case 2: //申請中
                    return "list_background_blue";
                case 3: //承認済み
                    return "list_background_gray";
                case 4: //差戻し
                    return "list_background_yellow";
            }
            if(dayInfo.work_holiday_id !== 1)
            {
                //通常以外
                return "list_background_green";
            }
            //初期状態
            return "list_background_white";
        },
        listRowColorClass:function(dayInfo){
            if(dayInfo.close_state_id > 2)
            {
                //管理者締め以降
                return "list_background_gray";
            }
            if(dayInfo.violation_warning_id !== 0 && dayInfo.violation_warning_id !== 1)
            {
                //違反警告状態あり
                return "list_background_yellow";
            }
            //
            switch(dayInfo.approval_state_id)
            {
                case 2: //申請中
                    return "list_background_blue";
                case 3: //承認済み
                    return "list_background_gray";
                case 4: //差戻し
                    return "list_background_yellow";
            }
            if(dayInfo.work_holiday_id !== 1)
            {
                //通常以外
                return "list_background_green";
            }
            //初期状態
            return "list_background_white";
        },
        openDailyReportModal(itemOption,itemDate)
        {

            //申請・承認差し戻し判定
            if(this.isManager)
            {
                // 必要なユーザIDを取得する
                // ログインID
                let loginid = itemOption.session_data.employee_id;
                // 入力者ID
                let inputterid = this.input_attendance.attendance_information[itemDate-1].input_employee_id;
                // 承認者ID
                let approvalid = this.input_attendance.attendance_information[itemDate-1].approval_employee_id;
                // ログインID、入力者ID、承認者IDが一致する場合のみ、「申請・承認取り消し」ボタンをクリック可とする
                if(loginid == inputterid && loginid == approvalid && inputterid == approvalid)
                {
                    // 申請・承認取り消しクリック可
                    this.isRemand = true;
                }
                else
                {
                    this.isRemand = false;
                }

            }

            itemOption.isManager = this.isManager;
            itemOption.isRemand = this.isRemand;
            itemOption.informationAttendanceMode = this.informationAttendanceMode;

            this.cleanModal();
            this.openModal('m105_input_attendance_details', 'modal-lg', itemOption);
        },
        OpenBunchApplicationModal()
        {
            let first_attendance_information_id = 0;
            let option = [];
            let checked_list = [];
            let isFirstChecked = true;
            let work_holiday_flg = true;

            for(let key in this.attendanceInformationInfoList)
            {
                
                if(this.attendanceInformationInfoList[key].isChecked && this.attendanceInformationInfoList[key].isEnableBunchApplication)
                {
                    if(isFirstChecked){
                        first_attendance_information_id = this.attendanceInformationInfoList[key].attendance_information_id;
                        isFirstChecked = false;
                        work_holiday_flg = this.attendanceInformationInfoList[key].work_holiday_id == 1 ? true : false;
                    }else{
                        if(work_holiday_flg != (this.attendanceInformationInfoList[key].work_holiday_id == 1 ? true : false)){
                            this.modalOptionchecked.message = '出勤日と休日は別々に申請してください';
                            this.openModal("m112_common_message", "", this.modalOptionchecked);
                            return;
                        }
                    }
                    checked_list.push(this.attendanceInformationInfoList[key].attendance_information_id); 
                }
            }

            option = 
            {
                'isManager':this.isManager,
                'session_data': this.session_data,
                'attendance_information_id': first_attendance_information_id,
                'reflectChange': this.onClickUpdate,
                'updateViolation': this.updateViolationWarningId,
                'checked_list':checked_list,
                'isBunch':true,
                'isRemand':this.isRemand,
                'informationAttendanceMode':this.informationAttendanceMode,
                'close_state_id': this.attendanceAggregate.closeStateId
            };

            if(!isFirstChecked){
                option.isManager = this.isManager;
                option.isRemand = this.isRemand;
                option.informationAttendanceMode = this.informationAttendanceMode;

                this.cleanModal();
                this.openModal('m105_input_attendance_details', 'modal-lg', option);
                return;
            }else{
                this.modalOptionchecked.message = '日付を選択してください';
                this.openModal("m112_common_message", "", this.modalOptionchecked);
                return;
            }
        },
        application ()
        {
            let checked_list = [];
            for(let key in this.attendanceInformationInfoList)
            {
                if(this.attendanceInformationInfoList[key].isChecked && this.attendanceInformationInfoList[key].isEnableBunchApplication)
                {
                    if(this.attendanceInformationInfoList[key].approval_state_id != 5 
                    && !(this.attendanceInformationInfoList[key].approval_state_id == 1 && this.attendanceInformationInfoList[key].violation_warning_id == 1 && this.attendanceInformationInfoList[key].attendance_date < this.todaySerial())){
                        this.modalOptionchecked.message = '「申請せずに保存」状態、「自動申請」状態の日のみ選択してください';
                        this.openModal("m112_common_message", "", this.modalOptionchecked);
                        return;
                    }
                    checked_list.push(this.attendanceInformationInfoList[key].attendance_information_id); 
                }
            }
            if(checked_list == null || checked_list.length == 0){
                this.modalOptionchecked.message = '日付を選択してください';
                this.openModal("m112_common_message", "", this.modalOptionchecked);
                return;
            }

            axios.post('informationAttendanceList_application', {
                params: {
                    checked_list: checked_list,
                    employee_id: this.employeeID,
                    yearMonth: this.yearMonth,      //対象年月も渡す
                }
            }).then(response => {
                if(response.data.result)
                {
                    this.openModal("m112_common_message", "", this.applicationChecked);
                }
                else
                {
                    this.modalOptionchecked.message = response.data.values.message;
                    this.openModal("m112_common_message", "", this.modalOptionchecked);
                }
            }).catch(error => {
                console.log(error.response);
            });

        },
        changeWorkAchievement(item){
            //チェック状態をあわせる
            this.attendanceInformationInfoList.forEach((data)=>{
                if(data.attendance_information_id == item.attendance_information_id){
                    data.work_achievement_id = item.work_achievement_id;//実績ID　休日：０　通常：１
                    if(data.work_achievement_id == 1){
                        //通常
                        data.work_holiday_id = 1;//出休ID　通常
                    }else{
                        //休日
                        if(item.week == "日"){//出休ID　所定：２　法定：３
                            data.work_holiday_id = 3;
                        }else{
                            data.work_holiday_id = 2;
                        }
                    }
                }
            });
        },
        //一括承認
        //引数追加
        applyCheckedDays(close_state_id)
        {
            //チェック済みかつ表示されているattendanceInformation取得
            let apply_array = [];
            for(let key in this.attendanceInformationInfoList)
            {
                if(this.attendanceInformationInfoList[key].isChecked && this.attendanceInformationInfoList[key].isEnableCheck)
                {
                    apply_array.push(this.attendanceInformationInfoList[key]);
                }
            }
            axios.post('checked_attendance_details_approve', {
                params: {
                    info_array: apply_array,
                    employee_id: this.employeeID,
                    yearMonth: this.yearMonth,       //パラメタ追加
                    close_state_id: close_state_id,  //パラメタ追加
                }
            }).then(response => {
                if(response.data.result)
                {
                    //勤怠入力の他のボード含め表示更新
                    this.updateInputAttendanceBoard(true);
                    this.isCheckedCheckAll = false;
                    this.is_nocheck = true;
                    this.openModal("m112_common_message", "", this.modalOptionApply);
                }
                else
                {
                    //選択がない状態でリクエストなど行われた場合
                    this.modalOptionchecked.message = response.data.values.message;
                    this.openModal("m112_common_message", "", this.modalOptionchecked);
                }
            }).catch(error => {
                console.log(error.response);
            });
        },
        //チェックボックスクリック
        chkClick(){
            var checked = false;
            for(let key in this.attendanceInformationInfoList)
            {
                if(this.attendanceInformationInfoList[key].isChecked && this.attendanceInformationInfoList[key].isEnableCheck)
                {
                    checked = true;
                    break;
                }
            }
            this.is_nocheck = !checked;
            //「入力・承認」は選択した日付を承認は押せないようにしておく
            if(this.information_attendance_mode==2){
                this.is_nocheck = true;
            }
        },
        getAbsentList()
        {
            axios.get('getAbsentInfo', {
                //年月を6桁で送信
                params:{
                    'employeeID': this.employeeID,
                    'targetdate': this.attendanceInformationInfoList[this.attendanceInformationInfoList.length-1].attendance_date, //締め日
                }
            }).then(response => {
                if(response.data.result){
                    if(response.data.values.target_acquired_holidays != null)
                    {
                        this.remainingHolidayDays = 0;
                        this.remainingHolidayHalfDays = 0;
                        this.unusedAccumulatedPaidLeaveDays = 0;
                        this.unusedAccumulatedPaidLeaveHalfDays = 0;
                        for(let i = 0; i < response.data.values.target_acquired_holidays.length; i++)
                        {
                            this.remainingHolidayDays = this.remainingHolidayDays + response.data.values.target_acquired_holidays[i].remaining_holiday_days;
                            this.remainingHolidayHalfDays = this.remainingHolidayHalfDays + response.data.values.target_acquired_holidays[i].remaining_holiday_half_days;
                        }
                    }
                    if(response.data.values.target_accumulated_holidays != null)
                    {
                        for(let i = 0; i < response.data.values.target_accumulated_holidays.length; i++)
                        {
                            this.unusedAccumulatedPaidLeaveDays = this.unusedAccumulatedPaidLeaveDays + response.data.values.target_accumulated_holidays[i].remaining_holiday_days;
                            this.unusedAccumulatedPaidLeaveHalfDays = this.unusedAccumulatedPaidLeaveHalfDays + response.data.values.target_accumulated_holidays[i].remaining_holiday_half_days;
                        }
                    }
                }
            })
        },
        reflectAttendance(value){
            this.select_all_type = 0;
            this.isEnableCheckNum = 0;
            this.isEnableBunchApplicationNum = 0;
            this.is_nocheck = true; //再描画時にis_nocheckのフラグもクリアしておく
            //更新処理が行われたら、必ず出休モードを終了する
            if(this.isChangeWorkHolidayMode)
            {
                this.isChangeWorkHolidayMode = false;
                this.attendanceInformationInfoList.forEach((data)=>{
                    data.work_achievement_id = data.backup_work_achievement_id;
                    data.work_holiday_id = data.backup_work_holiday_id;
                });
            }
            if(this.isChangeSubstituteMode)
            {
                this.isChangeSubstituteMode = false;
                this.attendanceInformationInfoList.forEach((data)=>{
                    data.work_achievement_id = data.backup_work_achievement_id;
                    data.work_holiday_id = data.backup_work_holiday_id;
                });
            }
            //リストの初期化
            this.attendanceInformationInfoList = [];
            this.attendanceAggregate = value.attendance_aggregate;

            if(this.attendanceAggregate){
                if((this.attendanceAggregate.close_state_id == 1 || this.attendanceAggregate.close_state_id == 2) && !this.isManager){
                    this.isBunchApplication = true;
                }else{
                    this.isBunchApplication = false;
                }
            }
            //対象期間の日付分のListを作る
            for(let i = 0; i < value.attendance_information.length; i++)
            {
                const attendanceInfo = value.attendance_information[i];
                //・乖離がある日の申請欄に「※」を赤字で追加
                let approvalStateName = this.session_data.master_data.approval_state.find(elm => elm.approval_state_id == attendanceInfo.approval_state_id)?.approval_state_short_name;
                if(attendanceInfo.violation_warning_id == 2 && !this.isManager){
                    approvalStateName += "※";
                }
                //手入力打刻に「※」を追加
                let web_punch_clock_time_start = '';
                if(attendanceInfo.web_punch_clock_time_start !== null){
                    web_punch_clock_time_start = this.serialToTimeStr(attendanceInfo.web_punch_clock_time_start, false);
                    if(value.web_punch_clock_info.find(elm => elm.punch_clock_date == attendanceInfo.attendance_date && elm.clocking_in_out_id == 1 && elm.input_class == 1)){
                        web_punch_clock_time_start += "※";
                    }
                }
                let web_punch_clock_time_end = '';
                if(attendanceInfo.web_punch_clock_time_end !== null){
                    web_punch_clock_time_end = this.serialToTimeStr(attendanceInfo.web_punch_clock_time_end, false);
                    if(value.web_punch_clock_info.find(elm => elm.punch_clock_date == attendanceInfo.attendance_date && elm.clocking_in_out_id == 2 && elm.input_class == 1)){
                        web_punch_clock_time_end += "※";
                    }
                }

                //チェックボックスの表示有無は、「申請状態」もしくは「自動申請状態」＝「初期状態」かつ「警告なし」かつ「前日以前」
                let isEnableCheck = attendanceInfo.approval_state_id == 2 || (attendanceInfo.approval_state_id == 1 && attendanceInfo.violation_warning_id == 1 && attendanceInfo.attendance_date < this.todaySerial());
                if(isEnableCheck) {
                    this.isEnableCheckNum ++; 
                }
                let work_achievement_name = this.session_data.master_data.work_achievement.find(elm => elm.work_achievement_id == attendanceInfo.work_achievement_id)?.work_achievement_short_name;
                let is_not_register = this.session_data.master_data.work_achievement.find(elm => elm.work_achievement_id == attendanceInfo.work_achievement_id)?.is_not_register;
                let work_time_start = "";
                let work_time_end = "";
                if(attendanceInfo.work_achievement_id == 0 && (attendanceInfo.work_holiday_id == 2 || attendanceInfo.work_holiday_id == 3)){
                    work_achievement_name = '会社休日';
                    work_time_start = this.serialToTimeStr(attendanceInfo.work_time_start, false) != "0:00"? this.serialToTimeStr(attendanceInfo.work_time_start, false) : "";
                    work_time_end = this.serialToTimeStr(attendanceInfo.work_time_end, false) != "0:00"? this.serialToTimeStr(attendanceInfo.work_time_end, false) : "";
                }
                else if(is_not_register == 1)
                {
                    work_time_start = "";
                    work_time_end = "";
                }
                else{
                    work_time_start = this.serialToTimeStr(attendanceInfo.work_time_start, false);
                    work_time_end = this.serialToTimeStr(attendanceInfo.work_time_end, false);
                }
                let isEnableChangeWorkHoliday = attendanceInfo.approval_state_id == 1 && (attendanceInfo.work_achievement_id == 0 || attendanceInfo.work_achievement_id == 1);
                // (申請なし||申請せずに保存) && (実績は通常||実績は振休) || (申請状態ではない&&不就業や残業入力あり(実績IDは0)&&休日(2,3)ではない)「振休」⇔「通常」変更可
                let isEnableChangeApprovalStateIdsArr = [1, 5];
                let isEnableChangeWorkAchievementIdsArr = [1, 9];
                let isEnableChangeSubstitute = isEnableChangeApprovalStateIdsArr.includes(attendanceInfo.approval_state_id) 
                && isEnableChangeWorkAchievementIdsArr.includes(attendanceInfo.work_achievement_id)
                || (attendanceInfo.approval_state_id != 2 && attendanceInfo.work_holiday_id != 2 && attendanceInfo.work_holiday_id != 3 && attendanceInfo.work_achievement_id == 0)
                
                let actual_work_time = attendanceInfo.actual_work_time;
                let holiday_work_time = attendanceInfo.holiday_work_time;
                let midnight_time = attendanceInfo.midnight_time;
                //休日時の実働時間、休日時間、深夜時間
                if(attendanceInfo.work_achievement_id == 4 || attendanceInfo.work_achievement_id == 5 || attendanceInfo.work_achievement_id == 6 || attendanceInfo.work_achievement_id == 7)
                {
                    //actual_work_time = attendanceInfo.actual_work_time + attendanceInfo.midnight_time + attendanceInfo.holiday_work_time + attendanceInfo.holiday_midnight_work_time;
                    holiday_work_time = attendanceInfo.holiday_work_time + attendanceInfo.holiday_midnight_work_time;
                    midnight_time = attendanceInfo.midnight_time + attendanceInfo.holiday_midnight_work_time;
                }
                let isEnableBunchApplication = false;
                if(attendanceInfo.close_state_id == 1 || attendanceInfo.close_state_id == 2){
                    isEnableBunchApplication = true;
                    this. isEnableBunchApplicationNum ++;
                }
                this.attendanceInformationInfoList.push({
                    'list_background_class': this.listRowColorClass(attendanceInfo),
                    'web_punch_clock_start_time_background_class': this.webPunchClockTimeColorClass(attendanceInfo, value.violation_warning_array[i]['start_violation_flg']),
                    'web_punch_clock_end_time_background_class': this.webPunchClockTimeColorClass(attendanceInfo, value.violation_warning_array[i]['end_violation_flg']),
                    'isChecked': false,
                    'isEnableCheck': isEnableCheck,
                    'approval' : approvalStateName,
                    'option': {
                        'isManager':this.isManager,
                        'session_data': this.session_data,
                        'attendance_information_id': attendanceInfo.attendance_information_id,
                        'reflectChange': this.onClickUpdate,
                        'updateViolation': this.updateViolationWarningId,
                        'isBunch':false,
                        'isRemand':this.isRemand,
                        'informationAttendanceMode':this.informationAttendanceMode,
                        'close_state_id':this.attendanceAggregate.close_state_id,
                    },
                    'date': this.serialToDateStr(attendanceInfo.attendance_date,'D'),
                    'week': this.serialToDateStr(attendanceInfo.attendance_date,'A'),
                    'work_time_start': work_time_start,
                    'work_time_end': work_time_end,
                    'web_punch_clock_time_start': web_punch_clock_time_start,
                    'web_punch_clock_time_end': web_punch_clock_time_end,
                    'actual_work_time': this.serialToHoursStr(actual_work_time) != "0:00"? this.serialToHoursStr(actual_work_time) : "",
                    'working_time': this.serialToHoursStr(attendanceInfo.statutory_working_time + attendanceInfo.non_statutory_working_time) != "0:00"? this.serialToHoursStr(attendanceInfo.statutory_working_time + attendanceInfo.non_statutory_working_time) : "",
                    'deduction_time': this.serialToHoursStr(attendanceInfo.deduction_time) != "0:00"? this.serialToHoursStr(attendanceInfo.deduction_time) : "",
                    'holiday_work_time': this.serialToHoursStr(holiday_work_time) != "0:00"? this.serialToHoursStr(holiday_work_time) : "",
                    'midnight_time': this.serialToHoursStr(midnight_time) != "0:00"? this.serialToHoursStr(midnight_time) : "",
                    'absent_time': this.serialToHoursStr(attendanceInfo.absent_time) != "0:00"? this.serialToHoursStr(attendanceInfo.absent_time) : "",
                    'information': attendanceInfo.substitute_holiday_reason + (((attendanceInfo.substitute_holiday_reason != "") && (attendanceInfo.information != ""))? " ／ " : "") + attendanceInfo.information,
                    'approval_employee_name': value.employee_list.find(elm => elm.employee_id == attendanceInfo.approval_employee_id)?.employee_name,
                    'input_employee_name': value.employee_list.find(elm => elm.employee_id == attendanceInfo.input_employee_id)?.employee_name,
                    'work_achievement_name': work_achievement_name,
                    'unemployed_id': attendanceInfo.unemployed_id,
                    'unemployed_name': this.session_data.master_data.unemployed.find(elm => elm.unemployed_id == attendanceInfo.unemployed_id)?.unemployed_short_name,
                    'work_zone_name': this.session_data.master_data.work_zone.find(elm => elm.work_zone_id == attendanceInfo.work_zone_id)?.work_zone_name,
                    'work_achievement_id': attendanceInfo.work_achievement_id,
                    'work_holiday_id': attendanceInfo.work_holiday_id,
                    'backup_work_achievement_id': attendanceInfo.work_achievement_id,
                    'backup_work_holiday_id': attendanceInfo.work_holiday_id,
                    'work_zone_id': attendanceInfo.work_zone_id,
                    'isEnableChangeWorkHoliday': isEnableChangeWorkHoliday,
                    'isEnableChangeSubstitute': isEnableChangeSubstitute,
                    'attendance_information_id': attendanceInfo.attendance_information_id,
                    'attendance_date': attendanceInfo.attendance_date,
                    'close_state_id': attendanceInfo.close_state_id,
                    'isEnableBunchApplication':isEnableBunchApplication,
                    'work_holiday_short_name': value.work_holiday_short_name_array[i],
                    'approval_state_id': attendanceInfo.approval_state_id,
                    'violation_warning_id': attendanceInfo.violation_warning_id,
                    'work_holiday_class': attendanceInfo.work_holiday_class,
                });
            }
        },
        onCancel() {
            //Loading画面のキャンセル
        },
    },
    computed:{
        isEnableCheckAtLeastOne: function() {
            return this.isEnableCheckNum > 0;
        },
        isEnableBunchApplicationAtLeastOne: function() {
            return this.isEnableBunchApplicationNum > 0;
        },
        //ボタン表示の条件をプロパティ化しておく
        // 管理者画面「照会・承認」/それ以外
        isManagerButton : function(){
            return (this.information_attendance_mode == 1 || this.information_attendance_mode == 2);
        },
        // 休出
        isChangeWorkHolidayModeButton : function(){
            return (this.isChangeWorkHolidayMode);
        },
        // 振替休日変更
        isChangeSubstituteModeButton : function(){
            return (this.isChangeSubstituteMode);

        },
        // 一括登録開始
        isAllApplyModeButton : function(){
            return (this.information_attendance_mode ==0);
        },
        isGeneralAttendance:function(){
            return !!Number(this.session_data.authority_pattern.general_affairs_attendance_input);
        },
        isOfficeMenu:function(){
            return !!Number(this.session_data.authority_pattern.office_menu);
        },
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
            return "card_fontcolor_blue";
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
        closeThemselvesButtonTypeClass:function(){
            return this.isCloseThemselves ? "btn-secondary" : "btn-primary";
        },
        changeWorkHolidaybuttonTypeClass:function(){
            return this.isChangeWorkHolidayMode ? "btn-success" : "btn-primary";
        },
        changeSubstitutebuttonTypeClass:function(){
            return this.isChangeSubstituteMode ? "btn-success" : "btn-primary";
        },
        closeManagerButtonTypeClass:function(){
            //締め状態が「管理者締め」以降
            if(this.attendanceAggregate.close_state_id >= 3)
            {
                return "btn-danger";
            }
            //締め状態が「本人締め」
            else if(this.attendanceAggregate.close_state_id == 2)
            {
                return "btn-primary";
            }
            //締め状態が「初期状態」
            else
            {
                return "btn-secondary";
            }
        },
        fontcolor:function(){
            return function(approval,close_state_id) {
                if(close_state_id > 2)
                {
                    //管理者締め以降
                    return false;
                }
                if(!this.isManager)
                {
                    return approval == "差戻" || approval == "※";
                }else{
                    return approval == "申請";
                }
            }
        },
        isExistData: function(){
            return !!this.attendanceInformationInfoList && this.attendanceInformationInfoList.length != 0;
        },
        holidayDaysCount: function(){
            return this.attendanceInformationInfoList.filter(item => item.unemployed_id == 1).length;
        },
        holidayHalfDaysCount: function(){
            return this.attendanceInformationInfoList.filter(item => item.unemployed_id == 2).length + 
                    this.attendanceInformationInfoList.filter(item => item.unemployed_id == 3).length + 
                    this.attendanceInformationInfoList.filter(item => item.unemployed_id == 5).length;
        },
        accumulatedHolidayDaysCount: function(){
            return this.attendanceInformationInfoList.filter(item => item.unemployed_id == 10).length;
        },
        accumulatedHolidayHalfDaysCount: function(){
            return this.attendanceInformationInfoList.filter(item => item.unemployed_id == 11).length;
        },
        isCloseThemselves: function(){
            return !!(2 <= this.attendanceAggregate?.close_state_id);
        },
        isCloseManager: function(){
            return this.attendanceAggregate?.close_state_id == 1 || 3 < this.attendanceAggregate?.close_state_id;
        },
        changeWorkHolidayText: function(){
            return this.isChangeWorkHolidayMode ? '出休変更登録': '出休変更';
        },
        changeSubstituteText: function(){
            return this.isChangeSubstituteMode ? '振替休日変更登録': '振替休日変更';
        },
        isInputApproval: function(){
            // 「入力・承認」が選択された
            return (this.information_attendance_mode ==2);
        },
    },
    watch: {
        year_month: { // 外からプロパティの中身が変更になったら実行される
            immediate: true,
            handler(value) {
                if(!value || value.length <= 0)
                {
                    // do nothing
                }
                else
                {
                    this.yearMonth = value;
                    this.isChangeSubstituteMode = false;
                    //employeeIDが初期値の時は処理しない（初回2度読み防止）
                    if(this.employeeID){
                        this.isCheckedCheckAll = false;
                        this.is_nocheck = true;
                        this.updateInputAttendanceBoard(false);
                    }
                }
            }
        },
        is_manager: { // 外からプロパティの中身が変更になったら実行される
            immediate: true,
            handler(value) {
                this.isManager = value;
            }
        },
        background_type: { // 外からプロパティの中身が変更になったら実行される
            immediate: true,
            handler(value) {
                this.backgroundType = value;
            }
        },
        information_attendance_mode: { // 外からプロパティの中身が変更になったら実行される
            immediate: true,
            handler(value) {
                this.informationAttendanceMode = value;
                //画面の更新を行う
                if(this.employeeID && this.yearMonth){
                    this.updateInputAttendanceBoard(false);
                }
            }
        },
        employee_id: { // 外からプロパティの中身が変更になったら実行される
            immediate: true,
            handler(value) {
                if(!value || value.length <= 0)
                {
                    // do nothing
                }
                else
                {
                    this.isChangeSubstituteMode = false;
                    this.employeeID = Number(value);
                    //yearMonthが初期値の時は処理しない（初回2度読み防止）
                    if(this.yearMonth){
                        this.updateInputAttendanceBoard(true);
                    }
                }
            }
        },
        input_attendance: {
            handler(value) {
                if(value)
                {
                    this.reflectAttendance(value);
                    if(this.attendanceInformationInfoList.length > 0){
                        this.getAbsentList();
                    }
                }
            }
        },
        is_selected_target:{
            handler(value)
            {
                if(!value)
                {
                    //非表示状態になったら行う処理
                    this.isChangeWorkHolidayMode = false;
                    this.isChangeSubstituteMode = false;
                }
            }
        },
        select_all_type: {
            handler(value)
            {
                switch(value) {
                    case '0':
                        // チェックなし
                        this.isCheckedCheckAll = false;
                        this.is_nocheck = true;
                        this.attendanceInformationInfoList.forEach((e)=>{
                            this.$set(e, 'isChecked', this.isCheckedCheckAll);
                        });
                        break;
                    case '1':
                        // 全てのチェック
                        this.isCheckedCheckAll = true;
                        this.is_nocheck = false;
                        this.attendanceInformationInfoList.forEach((e)=>{
                            this.$set(e, 'isChecked', this.isCheckedCheckAll);
                        });
                        break;
                    case '2':
                        // チェックは平日のみ
                        this.is_nocheck = false;
                        this.attendanceInformationInfoList.forEach((e)=>{
                            if(e.work_holiday_class == 0) {
                                this.$set(e, 'isChecked', true);
                            } else {
                                this.$set(e, 'isChecked', false);
                            }
                        });
                        break;
                    case '3':
                        // チェックは休日のみ
                        this.is_nocheck = false;
                        this.attendanceInformationInfoList.forEach((e)=>{
                            if(e.work_holiday_class == 1 || e.work_holiday_class == 2) {
                                this.$set(e, 'isChecked', true);
                            } else {
                                this.$set(e, 'isChecked', false);
                            }
                        });
                        break;
                    default:
                        // do nothing
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
.list_background_gray{
    background-color: #a2a2a2 !important;
}
.list_background_blue{
    background-color: #a7cdff !important;
}
.list_background_yellow{
    background-color: #fffaad !important;
}
.list_background_green{
    background-color: #d1ffde !important;
}
.list_background_white{
    background-color: #ffffff !important;
}
.list_background_red{
    background-color: #ff8888 !important;
}
.fontcolor_red{
    color: #ff8888 !important;
}

</style>