<template>
    <div id="C032-01-01">
        <div id="C032-01-01-02" class="container-fluid p-3 h-100 w-100 shadow-sm board" style="margin-top:20pt;">
            <div class="row">
                <div class="col-12">
                    <button id="C027-01-01-01" class="btn btn-primary" style="font-size:11pt;width:100pt" v-on:click="onClickSelectDate()" v-html="buttonCaptionSelectTerm" v-bind:disabled="isSelectedTarget"></button>
                    <div id="C027-01-01-02" class="d-inline-block" style="color:#000000;font-size:15pt; margin-left: 20px; vertical-align: middle;" v-html="targetDate"></div>
                </div>
            </div>
        </div>
        <div v-if="isSelectedTerm">
            <div id="C032-01-01-01" class="container-fluid p-3 h-100 w-100 shadow-sm board" style="margin-top:20pt;">
                <div class="row">
                    <div class="px-2">
                        <div id="C032-01-01-01-01" class="text-left">
                            対象者選択
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="px-3 col-2 text-left">
                        <button id="C032-01-01-01-02" style="font-size:11pt;width:100pt" class="btn btn-primary" v-bind:disabled="isSelectedTarget" v-on:click="selectTarget()">対象者検索</button>
                    </div>
                    <div class="px-3 col-6 text-left">
                        <div id="C032-01-01-01-03" style="font-size:15pt;color:black;"  v-html="TextSelectTarget"></div>
                    </div>
                    <div class="px-3 col-2 text-left">
                        <button v-if="!isMenuOffice" id="C032-01-01-01-04" style="font-size:11pt;width:100pt" class="btn btn-primary" v-bind:disabled="isSelectedTarget" v-on:click="newTarget()">新規登録</button>
                    </div>
                    <div class="px-3 col-2 text-center">
                        <button id="C032-01-01-01-05" style="font-size:11pt;width:100pt" class="btn btn-primary" v-bind:disabled="!isSelectedTarget" v-on:click="exitInput()">入力終了</button>
                    </div>
                </div>
            </div>
        </div>
        <div v-if="isSelectedTarget">
            <div id="C032-01-01-02" class="container-fluid p-3 h-100 w-100 shadow-sm board" style="margin-top:20pt;">
                <div class="row">
                    <div class="px-2">
                        <div id="C032-01-01-02-01" class="text-left">
                            マスタ管理メニュー
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="p-2 col-12 text-left">
                        <button v-if="!isMenuOffice" id="C032-01-01-02-02" style="font-size:11pt;width:100pt" class="btn btn-primary ml-3" >社員情報修正</button>
                        <button v-if="!isMenuOffice" id="C032-01-01-02-03" style="font-size:11pt;width:100pt" class="btn btn-primary ml-3" v-on:click="openSettingApprovalTargetModal()">承認対象者設定</button>
                        <button v-if="!isMenuOffice" id="C032-01-01-02-04" style="font-size:11pt;width:100pt" class="btn btn-primary ml-3" v-on:click="openSettingInputAgentTargetModal()">代理入力者設定</button>
                        <button v-if="!isMenuOffice" id="C032-01-01-02-05" style="font-size:11pt;width:100pt" class="btn btn-primary ml-3" v-on:click="openGiveModal()">有休付与</button>
                        <button v-if="!isMenuOffice" id="C032-01-01-02-06666" style="font-size:11pt;width:100pt" class="btn btn-primary ml-3" v-on:click="openRegisterRetirementModal()" v-bind:disabled="isRetiremented">退職</button>
                    </div>
                </div>

                <div class="row">
                    <div class="p-2 col-12 text-left">
                        <div id="C032-01-01-02-06" class="card shadow h-100" style="background-color:#BCD2EE;">
                            <div class="card-title text-left">個人情報</div>
                            <div class="card-body">
                                <div class="row" style="color:#000000;">
                                    <div class="col-3 text-left">
                                        社員コード※
                                        <input type="text" class="form-control" v-model="employee_code" v-bind:disabled="isMenuOffice">
                                    </div>
                                    <div class="col-3 text-left">
                                        氏名※
                                        <input type="text" class="form-control" v-model="employee_name" v-bind:disabled="isMenuOffice">
                                    </div>
                                    <div class="col-3 text-left">
                                        カナ氏名※
                                        <input type="text" class="form-control" v-model="employee_kana_name" v-bind:disabled="isMenuOffice">
                                    </div>
                                    <div class="col-3 text-left">
                                        性別
                                        <input type="text" class="form-control" v-model="gender" v-bind:disabled="isMenuOffice">
                                    </div>
                                </div>
                                <div class="row" style="color:#000000;">
                                    <div class="col-3 text-left">
                                        メールアドレス
                                        <input type="email" class="form-control" v-model="email_address" v-bind:disabled="isMenuOffice">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="p-2 col-12 text-left">
                        <div id="C032-01-01-02-07" class="card shadow h-100" style="background-color:#BCD2EE;">
                            <div class="card-title text-left">所属情報</div>
                            <div class="card-body">
                                <div class="row" style="color:#000000;">
                                    <div class="col-3 text-left">
                                        事業所※
                                        <select style="border:none;" class="form-control" v-model="office_id" value="" v-bind:disabled="isMenuOffice">
                                            <option></option>
                                            <option v-for="option in officeList" :key="option.office_id" v-bind:value="option.office_id">{{ option.office_name }}</option>
                                        </select>
                                    </div>
                                    <div class="col-3 text-left">
                                        勤怠締め所属事業所※
                                        <select style="border:none;" class="form-control" v-model="work_closing_belonging_office_id" v-bind:disabled="isMenuOffice">
                                            <option></option>
                                            <option v-for="option in officeList" :key="option.office_id" v-bind:value="option.office_id">{{ option.office_name }}</option>
                                        </select>
                                    </div>
                                    <div class="col-3 text-left">
                                        部署※
                                        <select style="border:none;" class="form-control" v-model="dept_id" v-bind:disabled="isMenuOffice">
                                            <option></option>
                                            <option v-for="option in deptList" :key="option.dept_id" v-bind:title="option.dept_name" v-bind:value="option.dept_id">{{ option.dept_name }}</option>
                                        </select>
                                    </div>
                                    <div class="col-3 text-left">
                                        役職※
                                        <select style="border:none;" class="form-control" v-model="post_id" v-bind:disabled="isMenuOffice">
                                            <option></option>
                                            <option v-for="option in postList" :key="option.post_id" v-bind:value="option.post_id">{{ option.post_name }}</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="p-2 col-12 text-left">
                        <div id="C032-01-01-02-08" class="card shadow h-100" style="background-color:#BCD2EE;">
                            <div class="card-title text-left">雇用・勤怠情報</div>
                            <div class="card-body">
                                <div class="row" style="color:#000000;">
                                    <div class="col-3 text-left">
                                        入社年月日※
                                        <input type="date" class="form-control" v-model="joined_company_date" min="1901-01-01" v-bind:disabled="isMenuOffice">
                                    </div>
                                    <div class="col-3 text-left">
                                        退職年月日
                                        <input type="date" class="form-control" v-model="retirement_company_date" min="1901-01-01" disabled=false>
                                    </div>
                                    <div class="col-3 text-left">
                                        有休付与起算入社日※
                                        <input type="date" class="form-control" v-model="grant_starting_date" min="1901-01-01" v-bind:disabled="isMenuOffice">
                                    </div>
                                    <div class="col-3 text-left">
                                        初年度有休付与年月日※
                                        <input type="date" class="form-control" v-model="first_paid_leave_date" min="1901-01-01" v-bind:disabled="isMenuOffice">
                                    </div>
                                </div>
                                <div class="row" style="color:#000000;">
                                    <div class="col-2 text-left">
                                        雇用形態※
                                        <select style="border:none;" class="form-control" v-model="employment_style_id" v-bind:disabled="isMenuOffice">
                                            <option></option>
                                            <option v-for="option in employmentStyleList" :key="option.employment_style_id" v-bind:value="option.employment_style_id">{{ option.employment_style_name }}</option>
                                        </select>
                                    </div>
                                    <div class="col-2 text-left">
                                        締日区分※
                                        <select style="border:none;" class="form-control" v-model="close_date_id" v-bind:disabled="isMenuOffice">
                                            <option></option>
                                            <option v-for="option in closeDateList" :key="option.close_date_id" v-bind:value="option.close_date_id">{{ option.close_date_name }}</option>
                                        </select>
                                    </div>
                                    <div class="col-2 text-left">
                                        週所定日数※
                                        <input type="text" class="form-control" v-model="week_scheduled_working_days" v-bind:disabled="isMenuOffice">
                                    </div>
                                    <div class="col-2 text-left">
                                        所定労働時間※
                                        <inputTypeTimeModel v-model="scheduled_working_hours" :isEnableInput="!isMenuOffice"></inputTypeTimeModel>
                                    </div>
                                    <div class="col-2 text-left">
                                        時間外基準時間※
                                        <inputTypeTimeModel v-model="overtime_base_time" :isEnableInput="!isMenuOffice"></inputTypeTimeModel>
                                    </div>
                                    <div class="col-2 text-left">
                                        有休付与パターン※
                                        <select style="border:none;" class="form-control" v-model="grant_paid_leave_type_id" v-bind:disabled="isMenuOffice">
                                            <option></option>
                                            <option v-for="option in grantPaidLeaveTypeList" :key="option.grant_paid_leave_type_id" v-bind:value="option.grant_paid_leave_type_id">{{ option.grant_paid_leave_type_name }}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row" style="color:#000000;">
                                    <div class="col-3 text-left">
                                        個人カレンダID※
                                        <select style="border:none;" class="form-control" v-model="personal_calendar_id" v-bind:disabled="isMenuOffice">
                                            <option></option>
                                            <option v-for="option in calendarList" :key="option.calendar_id" v-bind:value="option.calendar_id">{{ option.calendar_name }}</option>
                                        </select>
                                    </div>
                                    <div class="col-3 text-left">
                                        勤務帯※
                                        <select style="border:none;" class="form-control" v-model="work_zone_id" v-bind:disabled="isMenuOffice">
                                            <option></option>
                                            <option v-for="option in workZoneList" :key="option.work_zone_id" v-bind:value="option.work_zone_id">{{ option.work_zone_name }}</option>
                                        </select>
                                    </div>
                                     <div class="col-3 text-left">
                                        ３６協定※
                                        <select style="border:none;" class="form-control" v-model="thirtysix_agreement_apply_id" v-bind:disabled="isMenuOffice">
                                            <option></option>
                                            <option v-for="option in thirtysixAgreementApplyList" :key="option.thirtysix_agreement_apply_id" v-bind:value="option.thirtysix_agreement_apply_id">{{ option.thirtysix_agreement_apply_name }}</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="p-2 col-12 text-left">
                        <div id="C032-01-01-02-09" class="card shadow h-100" style="background-color:#BCD2EE;">
                            <div class="card-title text-left">権限</div>
                            <div class="card-body">
                                <div class="row" style="color:#000000;">
                                    <div class="col-3 text-left">
                                        権限パターン※
                                    </div>
                                </div>
                                <div class="row mt-2" style="color:#000000;">
                                    <div class="col-3 text-left">
                                        <input id="C032-01-01-02-09-01" type="radio" v-model="authority_pattern_id" value=1 v-bind:disabled="isMenuOffice">
                                        <label for="C032-01-01-02-09-01">本人</label>
                                    </div>
                                </div>
                                <div class="row mt-2" style="color:#000000;">
                                    <div class="col-3 text-left">
                                        <input id="C032-01-01-02-09-02" type="radio" v-model="authority_pattern_id" value=2 v-bind:disabled="isMenuOffice">
                                        <label for="C032-01-01-02-09-02">代理入力者</label>
                                    </div>
                                    <div class="col-3 text-left">
                                        <input id="C032-01-01-02-09-03" type="radio" v-model="authority_pattern_id" value=3 v-bind:disabled="isMenuOffice">
                                        <label for="C032-01-01-02-09-03">代理入力者(代理入力対象設定)</label>
                                    </div>
                                </div>
                                <div class="row mt-2" style="color:#000000;">
                                    <div class="col-3 text-left">
                                        <input id="C032-01-01-02-09-04" type="radio" v-model="authority_pattern_id" value=4 v-bind:disabled="isMenuOffice">
                                        <label for="C032-01-01-02-09-04">勤怠管理者</label>
                                    </div>
                                    <div class="col-3 text-left">
                                        <input id="C032-01-01-02-09-05" type="radio" v-model="authority_pattern_id" value=5 v-bind:disabled="isMenuOffice">
                                        <label for="C032-01-01-02-09-05">勤怠管理者(代理対象設定)</label>
                                    </div>
                                    <div class="col-3 text-left">
                                        <input id="C032-01-01-02-09-06" type="radio" v-model="authority_pattern_id" value=6 v-bind:disabled="isMenuOffice">
                                        <label for="C032-01-01-02-09-06">勤怠管理者(承認対象設定)</label>
                                    </div>
                                    <div class="col-3 text-left">
                                        <input id="C032-01-01-02-09-07" type="radio" v-model="authority_pattern_id" value=7 v-bind:disabled="isMenuOffice">
                                        <label for="C032-01-01-02-09-07">勤怠管理者(承認・代理対象設定)</label>
                                    </div>
                                </div>
                                <div class="row mt-2" style="color:#000000;">
                                    <div class="col-3 text-left">
                                        <input id="C032-01-01-02-09-08" type="radio" v-model="authority_pattern_id" value=8 v-bind:disabled="isMenuOffice">
                                        <label for="C032-01-01-02-09-08">事業所</label>
                                    </div>
                                    <div class="col-3 text-left">
                                        <input id="C032-01-01-02-09-09" type="radio" v-model="authority_pattern_id" value=9 v-bind:disabled="isMenuOffice">
                                        <label for="C032-01-01-02-09-09">事業所(代理対象設定)</label>
                                    </div>
                                    <div class="col-3 text-left">
                                        <input id="C032-01-01-02-09-10" type="radio" v-model="authority_pattern_id" value=10 v-bind:disabled="isMenuOffice">
                                        <label for="C032-01-01-02-09-10">事業所(承認対象設定)</label>
                                    </div>
                                    <div class="col-3 text-left">
                                        <input id="C032-01-01-02-09-11" type="radio" v-model="authority_pattern_id" value=11 v-bind:disabled="isMenuOffice">
                                        <label for="C032-01-01-02-09-11">事業所(承認・代理対象設定)</label>
                                    </div>
                                </div>
                                <div class="row mt-2" style="color:#000000;">
                                    <div class="col-3 text-left">
                                        <input id="C032-01-01-02-09-12" type="radio" v-model="authority_pattern_id" value=12 v-bind:disabled="isMenuOffice">
                                        <label for="C032-01-01-02-09-12">総務</label>
                                    </div>
                                    <div class="col-3 text-left">
                                        <input id="C032-01-01-02-09-13" type="radio" v-model="authority_pattern_id" value=13 v-bind:disabled="isMenuOffice">
                                        <label for="C032-01-01-02-09-13">総務(代理対象設定)</label>
                                    </div>
                                    <div class="col-3 text-left">
                                        <input id="C032-01-01-02-09-14" type="radio" v-model="authority_pattern_id" value=14 v-bind:disabled="isMenuOffice">
                                        <label for="C032-01-01-02-09-14">総務(承認対象設定)</label>
                                    </div>
                                    <div class="col-3 text-left">
                                        <input id="C032-01-01-02-09-15" type="radio" v-model="authority_pattern_id" value=15 v-bind:disabled="isMenuOffice">
                                        <label for="C032-01-01-02-09-15">総務(承認・代理対象設定)</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="p-2 col-12 text-left">
                        <div id="C032-01-01-02-10" class="card shadow h-100" style="background-color:#BCD2EE;">
                            <div class="card-title text-left">管理対象</div>
                            <div class="card-body">
                                <div class="row" style="color:#000000;">
                                    <div class="col-3 text-left">
                                        入力可否区分※
                                        <select style="border:none;" class="form-control" v-model="available_input_class" v-bind:disabled="isMenuOffice">
                                            <option v-bind:value="0">勤怠入力不可</option>
                                            <option v-bind:value="1">勤怠入力可</option>
                                        </select>
                                    </div>
                                    <div class="col-3 text-left">
                                        打刻対象区分※
                                        <select style="border:none;" class="form-control" v-model="stamping_target_class" v-bind:disabled="isMenuOffice">
                                            <option v-bind:value="0">打刻対象外</option>
                                            <option v-bind:value="1">打刻対象</option>
                                        </select>
                                    </div>
                                    <div class="col-3 text-left">
                                        勤務管理対象区分※
                                        <select style="border:none;" class="form-control" v-model="work_management_target_class" v-bind:disabled="isMenuOffice">
                                            <option v-bind:value="0">勤務管理対象外</option>
                                            <option v-bind:value="1">勤務管理対象</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row" style="color:#000000;">
                                    <div class="col-3 text-left">
                                        外勤営業区分※
                                        <select style="border:none;" class="form-control" v-model="field_work" v-bind:disabled="isMenuOffice">
                                            <option v-bind:value="0">外勤営業入力不可</option>
                                            <option v-bind:value="1">外勤営業入力可</option>
                                        </select>
                                    </div>
                                    <div class="col-3 text-left">
                                        始業前乖離許容時間※
                                        <select style="border:none;" class="form-control" v-model="deviation_time_before_start_time_id" v-bind:disabled="isMenuOffice">
                                            <option v-for="option in allowBeforeStartTimeArray" :key="option.web_punch_clock_deviation_time_id" v-bind:value="option.web_punch_clock_deviation_time_id">{{ option.allow_before_start_time }}</option>
                                        </select>
                                    </div>
                                    <div class="col-3 text-left">
                                        終業後乖離許容時間※
                                        <select style="border:none;" class="form-control" v-model="deviation_time_after_end_time_id" v-bind:disabled="isMenuOffice">
                                            <option v-for="option in allowAfterEndTimeArray" :key="option.web_punch_clock_deviation_time_id" v-bind:value="option.web_punch_clock_deviation_time_id">{{ option.allow_after_end_time }}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row" style="color:#000000;">
                                    <div class="col-3 text-left">
                                        有効期限開始※
                                        <input type="date" class="form-control" v-model="valid_date_start" min="1901-01-01" v-bind:disabled="isMenuOffice">
                                    </div>
                                    <div class="col-3 text-left">
                                        有効期限終了※
                                        <input type="date" class="form-control" v-model="valid_date_end" min="1901-01-01" disabled=false>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="p-2 col-4">
                    </div>
                    <div class="p-2 col-4 text-center">
                        <button v-if="!isMenuOffice" id="C032-01-01-02-11" style="font-size:11pt;width:100pt" class="btn btn-danger ml-3" v-html="TextCancelDelete" v-on:click="cancelDelete()"></button>
                        <button v-if="!isMenuOffice" id="C032-01-01-02-12" style="font-size:11pt;width:100pt" class="btn btn-success ml-3" v-html="TextRegistUpdate" v-on:click="registUpdate()"></button>
                    </div>
                </div>
            </div>
        </div> 
    </div>
</template>

<script>
export default {
    props: {
        employee_id: Number, //親からもらった社員番号 Numberで来る
        is_menu_office: Boolean,
        session_data: Object,
    },
    data() {
        return {
            employeeID: 0, //ここでの値保持
            yearMonth: Number(this.serialToDateStr(this.todaySerial(), "YYYYMM")), //ここでの値保持
            targetEmployeeID: 0,
            isSelectedTarget:false,
            TextSelectTarget: "",
            TextCancelDelete: "",
            TextRegistUpdate: "",
            modalOption: {
                select_period_type:  false, //true:複数選択、false:択一選択
                callback_select: (employee_id,employee_code,employee_name,post_name,dept_name)=>{this.callback_select(employee_id,employee_code,employee_name,post_name,dept_name);},
                callback_cancel: ()=>{this.callback_cancel();},
                isEnableSelectOffice: true,
                employeeID: 1, //ここでの値保持＆子へ渡す用
                closeDateId: 0,
                officeId: 0,
                referenceDate: new Date(),
            },
            modalOption_m112: {
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
            modalOption_m119: {
                select_period_type: 1,
                callback_select: (startdate)=>{this.callback_select_date(startdate);},
                callback_cancel: ()=>{this.callback_cancel();},
                selectedStartDateSerial: -1,
            },
            startDateSerial: -1,
            isSelectedTerm: false,
            backgroundType: 3,  //背景色
            officeList: [],
            deptList: [],
            postList: [],
            employmentStyleList: [],
            closeDateList: [],
            calendarList: [],
            workZoneList: [],
            grantPaidLeaveTypeList: [],
            thirtysixAgreementApplyList: [],
            employeeData: [],
            employee_code: "",
            employee_name: "",
            employee_kana_name: "",
            gender: "",
            email_address: "",
            office_id: 0,
            work_closing_belonging_office_id: 0,
            dept_id: 0,
            post_id: 0,
            joined_company_date: "",
            retirement_company_date: "",
            grant_starting_date: "",
            first_paid_leave_date: "",
            employment_style_id: 0,
            close_date_id: 0,
            week_scheduled_working_days: "",
            scheduled_working_hours: null,
            overtime_base_time: null,
            personal_calendar_id: 0,
            work_zone_id: 0,
            grant_paid_leave_type_id: 0,
            thirtysix_agreement_apply_id: 0,
            authority_pattern_id: 0,
            available_input_class: 0,
            stamping_target_class: 0,
            work_management_target_class: 0,
            valid_date_start: "",
            valid_date_end: "",
            isregist: true,
            oldTargetEmployeeCode: "",
            retirement_company_serial: 0,
            approvalmodalOption: {
                isEnableSelectOffice: true, //全事業所選択
                employeeID: 0, //ここでの値保持＆子へ渡す用
                closeDateId: 0, //締め区分指定なし
                setting_target_type: 1, //承認対象者：1、代理入力者：2
            },
            inputAgentmodalOption: {
                isEnableSelectOffice: true, //全事業所選択
                employeeID: 0, //ここでの値保持＆子へ渡す用
                closeDateId: 0, //締め区分指定なし
                setting_target_type: 2, //承認対象者：1、代理入力者：2
            },
            registerRetirementmodalOption: {
                callback_select: this.callback_select,
                targetEmployeeID: 0, //ここでの値保持＆子へ渡す用
                targetEmployeeCode: 0,
                targetEmployeeName: '',
                targetEmployeePost: '',
                targetEmployeeOffice: '',
                targetEmployeeDept: '',
                joinedCompanyDate: '',
            },
            isMenuOffice: true,
            field_work: 0,
            deviation_time_before_start_time_id: 1,
            deviation_time_after_end_time_id: 2
        };
    },
    methods: {
        //初期化メソッド（親から呼ばれる）
        initialize()
        {
            this.isSelectedTarget = false;
            this.TextSelectTarget = "";
            this.isSelectedTerm = false;
            this.Insertinitialize();
        },
        openSettingApprovalTargetModal() {
            this.openModal('m108_setting_target', 'modal-xl', this.approvalmodalOption);
        },
        openSettingInputAgentTargetModal() {
            this.openModal('m108_setting_target', 'modal-xl', this.inputAgentmodalOption);
        },
        openGiveModal()
        {
            const option = {
                //ToDo対象者情報渡す必要あり
                firstPaidFlg:true,
                giveDay:this.todaySerial(),
                limitDay:this.todaySerial(),
                isFixMode: false,
                callback_regist: (absentType, giveDay, limitDay, giveNums)=>{this.callback_regist(absentType, giveDay, limitDay, giveNums);},
                callback_cancel: ()=>{this.callback_cancel();},
            }
            this.openModal('m111_give_paid_absents', '', option);
        },
        openRegisterRetirementModal() {
            this.openModal('m120_register_retirement', '', this.registerRetirementmodalOption);
        },
        callback_regist(absentType, giveDay, limitDay, giveNums){
            axios.post('insert_holiday_management', {
                holiday_id: absentType,
                employee_id: this.targetEmployeeID,
                grant_date: this.checkDate(giveDay),
                grant_holiday_days: giveNums,
                valid_date_end: this.checkDate(limitDay),
            }).then(response => {
                if(response.data.result)
                {
                    this.modalOption_m112.message =  "休暇管理情報" + response.data.values.holiday_management_id +"を新規登録しました。";
                    this.openModal("m112_common_message", "", this.modalOption_m112);
                }
                else
                {
                    this.modalOption_m112.message = "新規登録失敗しました。";
                    this.openModal("m112_common_message", "", this.modalOption_m112);
                }
            }).catch(error => {
                console.log(error.response);
            });   
        },
        Insertinitialize()
        {
            this.employee_code = "";
            this.employee_name = "";
            this.employee_kana_name = "";
            this.gender = "";
            this.email_address = "";
            this.office_id = 0;
            this.work_closing_belonging_office_id = 0;
            this.dept_id = 0;
            this.post_id = 0;
            this.joined_company_date = "";
            this.retirement_company_date = "";
            this.grant_starting_date = "";
            this.first_paid_leave_date = "";
            this.employment_style_id = 0;
            this.close_date_id = 0;
            this.week_scheduled_working_days = "";
            this.scheduled_working_hours = null;
            this.overtime_base_time = null;
            this.personal_calendar_id = 0;
            this.work_zone_id = 0;
            this.grant_paid_leave_type_id = 0;
            this.thirtysix_agreement_apply_id = 0;
            this.authority_pattern_id = 0;
            this.available_input_class = 0;
            this.stamping_target_class = 0;
            this.work_management_target_class = 0;
            this.valid_date_start = "1901-01-01";
            this.valid_date_end = "9999-12-31";
            this.field_work = 0;
            this.deviation_time_before_start_time_id = 1;
            this.deviation_time_after_end_time_id = 2;
        },
        selectTarget()
        {
            if(this.isMenuOffice){
                this.modalOption.isEnableSelectOffice = false;
                this.modalOption.officeId = this.session_data.office_id;
            }
            this.modalOption.referenceDate = this.startDateSerial;
            this.openModal('m110_search_member','', this.modalOption);
        },
        //社員が選択されたときのコールバック
        callback_select(employee_list){
            //モーダルの戻り値を取得
            this.targetEmployeeID = Number(employee_list.employee_id);
            this.TextSelectTarget = "選択中：　" + employee_list.employee_code +"　　"+ employee_list.employee_name +"　　"+ employee_list.employee_post +"　　"+ employee_list.employee_office+'／'+employee_list.employee_dept;
            this.isSelectedTarget = true;
            this.TextCancelDelete = "社員情報削除";
            this.TextRegistUpdate = "社員情報更新";
            this.isregist = false;
            this.approvalmodalOption.employeeID = this.targetEmployeeID;
            this.inputAgentmodalOption.employeeID = this.targetEmployeeID;
            this.registerRetirementmodalOption.targetEmployeeID = this.targetEmployeeID;
            this.registerRetirementmodalOption.targetEmployeePost = employee_list.employee_post;
            this.registerRetirementmodalOption.targetEmployeeOffice = employee_list.employee_office;
            this.registerRetirementmodalOption.targetEmployeeDept = employee_list.employee_dept;

            axios.get('getEmployeeInfo', {
                //年月を6桁で送信
                params:{
                    'employeeID': this.targetEmployeeID,
                    'targetDate': this.startDateSerial,
                }
            }).then(response => {
                if(response.data.result)
                {
                    this.oldTargetEmployeeCode = response.data.values.employee_code;
                    this.employee_code = response.data.values.employee_code;
                    this.employee_name = response.data.values.employee_name;
                    this.employee_kana_name = response.data.values.employee_kana_name;
                    this.gender = response.data.values.gender;
                    this.email_address = response.data.values.email_address;
                    //this.stamping_password = "";
                    this.office_id = response.data.values.office_id;
                    this.work_closing_belonging_office_id = response.data.values.work_closing_belonging_office_id;
                    this.dept_id = response.data.values.dept_id;
                    this.post_id = response.data.values.post_id;
                    this.joined_company_date = this.serialToDateStr(response.data.values.joined_company_date, "YYYY-MM-DD");
                    this.retirement_company_date = this.serialToDateStr(response.data.values.retirement_company_date, "YYYY-MM-DD");
                    this.grant_starting_date = this.serialToDateStr(response.data.values.grant_starting_date, "YYYY-MM-DD");
                    this.first_paid_leave_date = this.serialToDateStr(response.data.values.first_paid_leave_date, "YYYY-MM-DD");
                    this.employment_style_id = response.data.values.employment_style_id;
                    this.close_date_id = response.data.values.close_date_id;
                    this.week_scheduled_working_days = response.data.values.week_scheduled_working_days;
                    this.scheduled_working_hours = response.data.values.scheduled_working_hours;
                    this.overtime_base_time = response.data.values.overtime_base_time;
                    this.personal_calendar_id = response.data.values.personal_calendar_id;
                    this.work_zone_id = response.data.values.work_zone_id;
                    this.grant_paid_leave_type_id = response.data.values.grant_paid_leave_type_id;
                    this.thirtysix_agreement_apply_id = response.data.values.thirtysix_agreement_apply_id;
                    this.authority_pattern_id = response.data.values.authority_pattern_id;
                    this.available_input_class = response.data.values.available_input_class;
                    this.stamping_target_class = response.data.values.stamping_target_class;
                    this.work_management_target_class = response.data.values.work_management_target_class;
                    this.valid_date_start = this.serialToDateStr(response.data.values.valid_date_start, "YYYY-MM-DD");
                    this.valid_date_end = this.serialToDateStr(response.data.values.valid_date_end, "YYYY-MM-DD");
                    this.registerRetirementmodalOption.targetEmployeeCode = response.data.values.employee_code;
                    this.registerRetirementmodalOption.targetEmployeeName = response.data.values.employee_name;
                    this.registerRetirementmodalOption.joinedCompanyDate = response.data.values.joined_company_date;
                    this.field_work = response.data.values.field_work;
                    this.deviation_time_after_end_time_id = response.data.values.deviation_time_after_end_time_id;
                    this.deviation_time_before_start_time_id = response.data.values.deviation_time_before_start_time_id;
                }
             }).catch(error =>{

             })
        },
        //モーダルにてキャンセルされたときのコールバック
        callback_cancel(){
            
        },
        exitInput()
        {
            this.initialize();
            this.isSelectedTerm = true;
        },
        newTarget()
        {
            this.isSelectedTarget = true;
            this.TextSelectTarget = "新規登録中";
            this.TextCancelDelete = "キャンセル";
            this.TextRegistUpdate = "社員情報登録";
            this.Insertinitialize();
            this.isregist = true;
        },
        async updateEmployeeData(){
            let ret = null;
            //入力チェック
            if(this.employee_code == "" || this.employee_name == "" || this.employee_kana_name == ""){
                this.modalOption_m112.message = "個人情報の必須項目を入力してください。";
                this.openModal("m112_common_message", "", this.modalOption_m112);
                return;
            }
            if(this.office_id == 0 || this.work_closing_belonging_office_id == 0 || this.dept_id == 0 || this.post_id == 0){
                this.modalOption_m112.message = "所属情報の必須項目を選択してください。";
                this.openModal("m112_common_message", "", this.modalOption_m112);
                return;
            }
            if(this.joined_company_date == "" || this.grant_starting_date == "" || this.first_paid_leave_date == "" || this.week_scheduled_working_days == "" || this.scheduled_working_hours == null || this.scheduled_working_hours == -1 || this.overtime_base_time == null || this.overtime_base_time == -1){
                this.modalOption_m112.message = "雇用・勤怠情報の必須項目を入力してください。";
                this.openModal("m112_common_message", "", this.modalOption_m112);
                return;
            }
            if(this.employment_style_id == 0 || this.close_date_id == 0 || this.personal_calendar_id == 0 || this.work_zone_id == 0 || this.thirtysix_agreement_apply_id == 0){
                this.modalOption_m112.message = "雇用・勤怠情報の必須項目を選択してください。";
                this.openModal("m112_common_message", "", this.modalOption_m112);
                return;
            }
            if(this.grant_paid_leave_type_id == 0){
                this.modalOption_m112.message = "有休付与パターンを選択してください。";
                this.openModal("m112_common_message", "", this.modalOption_m112);
                return;
            }
            if(this.authority_pattern_id == 0){
                this.modalOption_m112.message = "権限パターンを選択してください。";
                this.openModal("m112_common_message", "", this.modalOption_m112);
                return;
            }
            if(this.valid_date_start == "" || this.valid_date_end == ""){
                this.modalOption_m112.message = "管理対象の必須項目を入力してください。";
                this.openModal("m112_common_message", "", this.modalOption_m112);
                return;
            }
            if(!this.checkDateStr(this.joined_company_date) ||!this.checkDateStr(this.grant_starting_date) ||!this.checkDateStr(this.first_paid_leave_date)){
                this.modalOption_m112.message = "雇用・勤怠情報の日付項目を確認してください。";
                this.openModal("m112_common_message", "", this.modalOption_m112);
                return;
            }
            if(!this.checkDateStr(this.valid_date_start) || !this.checkDateStr(this.valid_date_end)){
                this.modalOption_m112.message = "管理対象の日付項目を確認してください。";
                this.openModal("m112_common_message", "", this.modalOption_m112);
                return;
            }
            if(this.scheduled_working_hours == -1){
                this.modalOption_m112.message = "雇用・勤怠情報の時間項目を確認してください。";
                this.openModal("m112_common_message", "", this.modalOption_m112);
                return;
            }
            if(!this.checkNumberStr(String(this.week_scheduled_working_days))){
                this.modalOption_m112.message = "週所定日数を確認してください。";
                this.openModal("m112_common_message", "", this.modalOption_m112);
                return;
            }
            if(!this.checkNumberStringStr(String(this.employee_code))){
                this.modalOption_m112.message = "社員コードに半角英数字を入力してください。";
                this.openModal("m112_common_message", "", this.modalOption_m112);
                return;
            }
            if(this.deviation_time_before_start_time_id == '' || this.deviation_time_after_end_time_id == '' || this.field_word === '') {
                this.modalOption_m112.message = "外勤営業の必須項目を確認してください";
                this.openModal("m112_common_message", "", this.modalOption_m112);
                return;
            }

            //初期化
            this.employeeData = [];
            if(this.checkDateStr(this.retirement_company_date)){
                this.retirement_company_serial = this.dateStrToSerial(this.getValidDateStr(this.retirement_company_date));
            }else{
                this.retirement_company_serial = 2958465;
            }
            //入力欄まとめ
            this.employeeData.push({
                'employee_code': this.employee_code,
                'employee_name': this.employee_name,
                'employee_kana_name': this.employee_kana_name,
                'gender': this.gender,
                'email_address': this.email_address,
                'office_id': this.office_id,
                'work_closing_belonging_office_id': this.work_closing_belonging_office_id,
                'dept_id': this.dept_id,
                'post_id': this.post_id,
                'joined_company_date': this.dateStrToSerial(this.getValidDateStr(this.joined_company_date)),
                'retirement_company_date': this.retirement_company_serial,
                'grant_starting_date': this.dateStrToSerial(this.getValidDateStr(this.grant_starting_date)),
                'employment_style_id': this.employment_style_id,
                'close_date_id': this.close_date_id,
                'week_scheduled_working_days': this.getValidNumberStr(String(this.week_scheduled_working_days)),
                'scheduled_working_hours': this.scheduled_working_hours,
                'overtime_base_time': this.overtime_base_time,
                'personal_calendar_id': this.personal_calendar_id,
                'work_zone_id': this.work_zone_id,
                'grant_paid_leave_type_id' : this.grant_paid_leave_type_id,
                'first_paid_leave_date': this.dateStrToSerial(this.getValidDateStr(this.first_paid_leave_date)),
                'thirtysix_agreement_apply_id': this.thirtysix_agreement_apply_id,
                'authority_pattern_id': this.authority_pattern_id,
                'available_input_class': this.available_input_class,
                'stamping_target_class': this.stamping_target_class,
                'work_management_target_class': this.work_management_target_class,
                'valid_date_start': this.dateStrToSerial(this.getValidDateStr(this.valid_date_start)),
                'valid_date_end': this.dateStrToSerial(this.getValidDateStr(this.valid_date_end)),
                'field_work': this.field_work,
                'deviation_time_after_end_time_id': this.deviation_time_after_end_time_id,
                'deviation_time_before_start_time_id': this.deviation_time_before_start_time_id
            });
            await axios.post('/insert_employee_information', {
                data: this.employeeData,
                employeeID: this.employeeID,
                oldTargetEmployeeCode: this.oldTargetEmployeeCode,
                targetEmployeeID: this.targetEmployeeID,
                isregist: this.isregist,
            }).then(response => {
                if(response.data.result)
                {
                    this.oldTargetEmployeeCode = this.employee_code;
                    this.targetEmployeeID = response.data.values;
                }
                else
                {
                    this.modalOption_m112.message = response.data.val;
                    this.openModal("m112_common_message", "", this.modalOption_m112);
                }
                ret = response.data.result;
            })
            .catch(function(error){
                //何らかのエラー
            }); 
            return ret;
        },
        updateHistory(){
            axios.post('/update_history_table', {
                data: this.employeeData,
                employeeID: this.employeeID,
                targetEmployeeID: this.targetEmployeeID,
            }).then(response => {
                if(response.data.result)
                {
                    this.modalOption_m112.message = "成功";
                    this.openModal("m112_common_message", "", this.modalOption_m112);
                }
                else
                {
                    this.modalOption_m112.message = "社員情報更新成功したが、履歴更新失敗";
                    this.openModal("m112_common_message", "", this.modalOption_m112);
                }
                return response.data.result;
            })
            .catch(function(error){
                //何らかのエラー
            });
        },
        //更新・新規ボタン押す
        async registUpdate()
        {
            //社員情報更新
            const isUpdatedEmployeeData = await this.updateEmployeeData();

            if(isUpdatedEmployeeData)
            {
                //履歴更新実施
                this.updateHistory();
            }else{
                
            }
        },
        cancelDelete(){
            if(this.isregist == true){

                this.exitInput();

            }else{
                axios.post('/delete_employee_information', {
                    data: this.employeeData,
                    employeeID: this.employeeID,
                    targetEmployeeID: this.targetEmployeeID,
                }).then(response => {
                    if(response.data.result)
                    {
                        this.modalOption_m112.message = "削除しました。";
                        this.openModal("m112_common_message", "", this.modalOption_m112);
                    }
                    else
                    {
                        this.modalOption_m112.message = "削除失敗";
                        this.openModal("m112_common_message", "", this.modalOption_m112);
                    }
                    return response.data.result;
                })
                .catch(function(error){
                    //何らかのエラー
                });
            }
        },
        //基準日設定
        onClickSelectDate()
        {
            //選択済みの日付がある場合、反映、ない場合、当日の日付
            this.modalOption_m119.selectedStartDateSerial = 0 < this.startDateSerial ? this.startDateSerial : this.todaySerial();

            //モーダルを開く
            this.openModal("m119_select_day", "", this.modalOption_m119);
        },
        //日付が選択されたときのコールバック
        callback_select_date(startdate){
            this.startDateSerial = startdate;
            this.exitInput();
        },
    },
    mounted(){

        //所属マスタ
        const officeData =  this.getMasterData().office;
        for(let i = 0; i < officeData.length; i++)
        {
            this.officeList.push({
                'office_id': officeData[i].office_id,
                'office_name': officeData[i].office_name,
            });
        }
        //部署マスタ
        const deptData = this.getMasterData().dept_tree;
        let deptShortName = "";
        for(let i = 0; i < deptData.length; i++)
        {
            if(deptData[i].dept_name.length > 20){
                deptShortName = deptData[i].dept_name.substring(0, 20) + "...";
            }else{
                deptShortName = deptData[i].dept_name;
            }
            this.deptList.push({
                'dept_id': deptData[i].dept_id,
                'dept_name': deptData[i].dept_name,
                'dept_short_name': deptShortName,
            });
        }
        const postData = this.getMasterData().post;
        for(let i = 0; i < postData.length; i++)
        {
            this.postList.push({
                'post_id': postData[i].post_id,
                'post_name': postData[i].post_name,
            });
        }
        const employmentStyle = this.getMasterData().employment_style;
        for(let i = 0; i < employmentStyle.length; i++)
        {
            this.employmentStyleList.push({
                'employment_style_id': employmentStyle[i].employment_style_id,
                'employment_style_name': employmentStyle[i].employment_style_name,
            });
        }
        const closeDate = this.getMasterData().close_date;
        for(let i = 0; i < closeDate.length; i++)
        {
            this.closeDateList.push({
                'close_date_id': closeDate[i].close_date_id,
                'close_date_name': closeDate[i].close_date_name,
            });
        }
        const calendar = this.getMasterData().calendar;
        for(let i = 0; i < calendar.length; i++)
        {
            this.calendarList.push({
                'calendar_id': calendar[i].calendar_id,
                'calendar_name': calendar[i].calendar_name,
            });
        }
        const workZone = this.getMasterData().work_zone;
        for(let i = 0; i < workZone.length; i++)
        {
            this.workZoneList.push({
                'work_zone_id': workZone[i].work_zone_id,
                'work_zone_name': workZone[i].work_zone_name,
            });
        }
        const thirtysixAgreementApply = this.getMasterData().thirtysix_agreement_apply;
        for(let i = 0; i < thirtysixAgreementApply.length; i++)
        {
            this.thirtysixAgreementApplyList.push({
                'thirtysix_agreement_apply_id': thirtysixAgreementApply[i].thirtysix_agreement_apply_id,
                'thirtysix_agreement_apply_name': thirtysixAgreementApply[i].thirtysix_agreement_apply_name,
            });
        }
        const grantPaidLeaveType = this.getMasterData().grant_paid_leave_type;
        for(let i = 0; i < grantPaidLeaveType.length; i++)
        {
            this.grantPaidLeaveTypeList.push({
                'grant_paid_leave_type_id': grantPaidLeaveType[i].grant_paid_leave_type_id,
                'grant_paid_leave_type_name': grantPaidLeaveType[i].grant_paid_leave_type_name,
            });
        }
    },
    computed:{
        allowBeforeStartTimeArray: function() {
            return this.getMasterData().web_punch_clock_deviation_time.filter(item => {
                return item.clocking_in_out_id == 1;
            })
        },
        allowAfterEndTimeArray: function() {
            return this.getMasterData().web_punch_clock_deviation_time.filter(item => {
                return item.clocking_in_out_id == 2;
            })
        },
        targetYearMonth: function(){
            return this.yearMonthNumberToText(this.yearMonth);       
        },
        buttonCaptionSelectTerm: function() {
            return this.isSelectedTerm ? "基準日変更" : "基準日指定"
        },
        isRetiremented: function() {
            return this.dateStrToSerial(this.getValidDateStr(this.retirement_company_date)) != 2958465;
        },
        targetDate: function(){
            if(this.startDateSerial < 0)
            {
                //デフォルト値を表示
                return "表示する基準日を選択してください";
            }
            else
            {
                //シリアル値を日付に変換して表示
                return "基準日：" + this.serialToDateStr(this.startDateSerial, "YYYY/MM/DD(A)");
            }
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
        is_menu_office: { // 外からプロパティの中身が変更になったら実行される
            immediate: true,
            handler(value) {
                this.isMenuOffice = value;
            }
        },
    }
}
</script>
