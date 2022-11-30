<template>
    <div class="modal-content" id="C114-01">
        <div class="modal-body">
            <div id="C114-01-01" class="mb-3 d-flex justify-content-center">勤務帯登録</div>
            <div class="row mb-3">
                <div class="col-sm-1"></div>
                <label id="C114-01-02" class="col-sm-3 col-form-label">対象</label>
                <select id="C114-01-03" class="col-sm-7 form-control" v-model="targetOfficeID">
                    <option value="0">全社共通</option>
                    <option v-for="office in officeList" :key="office.office_id" v-bind:value="office.office_id">{{ office.office_name }}</option>
                </select>
                <div class="col-sm-1"></div>
            </div>
            <div class="row mb-3">
                <div class="col-sm-1"></div>
                <label id="C114-01-04" class="col-sm-3 col-form-label">名称</label>
                <input id="C114-01-05" class="col-sm-7 form-control" type="text" v-model="workZoneName" />
                <div class="col-sm-1"></div>
            </div>
            <div class="row mb-3">
                <div class="col-sm-1"></div>
                <label id="C114-01-27" class="col-sm-3 col-form-label">コード</label>
                <input id="C114-01-28" class="col-sm-7 form-control" type="text" v-model="workZoneCode" />
                <div class="col-sm-1"></div>
            </div>
            <div class="row mb-3">
                <div class="col-sm-1"></div>
                <label id="C114-01-29" class="col-sm-3 col-form-label">時給者用</label>
                <div class="form-check-label col-sm-7">
                    <input id="C114-01-30" class="c114_01_30" type="checkbox" v-model="workZoneAggrigationClass" true-value="2" false-value="1" />
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-sm-1"></div>
                <label id="C114-01-06" class="col-sm-3 col-form-label">所定時間</label>
                <div class="col-sm-3">
                    <inputTypeTime :timeSerial="prescribeWorkTimeStart" :isEnableInput="true" @onInput="inputChangedPrescribedWorkTimeStart"></inputTypeTime>
                </div>
                <div id="C111-01-08" class="col-sm-1 form-text">～</div>
                <div class="col-sm-3">
                    <inputTypeTime :timeSerial="prescribeWorkTimeEnd" :isEnableInput="true" @onInput="inputChangedPrescribedWorkTimeEnd"></inputTypeTime>
                </div>
                <div class="col-sm-1"></div>
            </div>
            <div class="row mb-3">
                <div class="col-sm-1"></div>
                <label id="C114-01-10" class="col-sm-3 col-form-label">所定休憩1</label>
                <div class="col-sm-3">
                    <inputTypeTime :timeSerial="prescribedRestTime[0].start.calc" :isEnableInput="true" @onInput="inputChangedPrescribedRestTime1Start"></inputTypeTime>
                </div>
                <div id="C111-01-12" class="col-sm-1 form-text">～</div>
                <div class="col-sm-3">
                    <inputTypeTime :timeSerial="prescribedRestTime[0].end.calc" :isEnableInput="true" @onInput="inputChangedPrescribedRestTime1End"></inputTypeTime>
                </div>
                <div class="col-sm-1"></div>
            </div>
            <div class="row mb-3">
                <div class="col-sm-1"></div>
                <label id="C114-01-14" class="col-sm-3 col-form-label">所定休憩2</label>
                <div class="col-sm-3">
                    <inputTypeTime :timeSerial="prescribedRestTime[1].start.calc" :isEnableInput="true" @onInput="inputChangedPrescribedRestTime2Start"></inputTypeTime>
                </div>
                <div id="C111-01-16" class="col-sm-1 form-text">～</div>
                <div class="col-sm-3">
                    <inputTypeTime :timeSerial="prescribedRestTime[1].end.calc" :isEnableInput="true" @onInput="inputChangedPrescribedRestTime2End"></inputTypeTime>
                </div>
                <div class="col-sm-1"></div>
            </div>
            <div class="row mb-3">
                <div class="col-sm-1"></div>
                <label id="C114-01-18" class="col-sm-3 col-form-label">所定休憩3</label>
                <div class="col-sm-3">
                    <inputTypeTime :timeSerial="prescribedRestTime[2].start.calc" :isEnableInput="true" @onInput="inputChangedPrescribedRestTime3Start"></inputTypeTime>
                </div>
                <div id="C111-01-20" class="col-sm-1 form-text">～</div>
                <div class="col-sm-3">
                    <inputTypeTime :timeSerial="prescribedRestTime[2].end.calc" :isEnableInput="true" @onInput="inputChangedPrescribedRestTime3End"></inputTypeTime>
                </div>
                <div class="col-sm-1"></div>
            </div>
            <div class="row mb-3">
                <div class="col-sm-1"></div>
                <label id="C114-01-22" class="col-sm-3 col-form-label">所定実働</label>
                <div class="col-sm-4"></div>
                <input id="C114-01-23" type="text" class="col-sm-3 form-control" v-model="actualWorkTimeSum" disabled />
                <div class="col-sm-1"></div>
            </div>
            <div class="row mb-3">
                <div class="col-sm-1"></div>
                <label class="col-sm-3 col-form-label">所定休憩</label>
                <div class="col-sm-4"></div>
                <input type="text" class="col-sm-3 form-control" v-model="restTimeSum" disabled />
                <div class="col-sm-1"></div>
            </div>
      　    <div class="message-group row ml-1 mr-1 pt-3">
                <div id="C114-01-26" class="error-message text-center col-sm-12">
                    <div v-for="(item, i) in errorMessageArray" :key="i">{{item}}</div>
                </div>
            </div>
            <div class="modal-footer d-flex justify-content-center">
                <button id="C114-01-24" type="button" class="btn btn-primary w-35" style="margin-right: 80px" v-on:click="registClick" v-bind:disabled="!isEnableRegist">登録</button>
                <button id="C114-01-25" type="button" class="btn btn-danger w-35" data-dismiss="modal" v-on:click="cancelClick">キャンセル</button>
            </div>
        </div>
    </div>

</template>

<script>
export default {
    props: ['op1', 'id', 'itemOption'],
    data() {
        return {
            prescribeWorkTimeStart: 0,
            prescribeWorkTimeEnd: 0,
            prescribedRestTime:[
                {start: {calc: 0, input: null}, end: {calc:0, input: null}},
                {start: {calc: 0, input: null}, end: {calc:0, input: null}},
                {start: {calc: 0, input: null}, end: {calc:0, input: null}},
            ],
            prescribeWorkTimeStartInput: 0,
            prescribeWorkTimeEndInput: 0,
            workZoneID: 0,
            workZoneName: "",
            workZoneCode: "",
            workZoneAggrigationClass: 1,
            targetOfficeID: 0,
            errorMessage: null,
            errorMessageArray: [],
            modalOption: {
                message: '',
                buttons:[　//【暫定】どのボタン指定するかは呼び出し元などから決定し指定する
                    {
                        exec : ()=>{
                            this.btn = "OK"; //ボタンが押された時の処理をここに記載
                            //M112モーダルを閉じる
                            this.cleanModal();
                            $('.modal-backdrop').remove();
                        },
                        caption : "OK",  //'OK','はい','いいえ','キャンセル','再試行','閉じる'
                        btnclass : "btn-success"
                    }],
            },
            isError: false,
        }
    },
    methods: {
        inputChangedPrescribedWorkTimeStart: function(value){
            this.prescribeWorkTimeStartInput = value;
        },
        inputChangedPrescribedWorkTimeEnd: function(value){
            this.prescribeWorkTimeEndInput = value;
        },
        inputChangedPrescribedRestTime1Start: function(value){
            this.prescribedRestTime[0].start.input = value;
        },
        inputChangedPrescribedRestTime1End: function(value){
            this.prescribedRestTime[0].end.input = value;
        },
        inputChangedPrescribedRestTime2Start: function(value){
            this.prescribedRestTime[1].start.input = value;
        },
        inputChangedPrescribedRestTime2End: function(value){
            this.prescribedRestTime[1].end.input = value;
        },
        inputChangedPrescribedRestTime3Start: function(value){
            this.prescribedRestTime[2].start.input = value;
        },
        inputChangedPrescribedRestTime3End: function(value){
            this.prescribedRestTime[2].end.input = value;
        },
        //P114-05 登録処理
        registClick() {
            if(this.validate()){
                axios.get('m023_edit', {
                    params:{
                        'office_id': this.targetOfficeID,
                        'work_zone_id': this.workZoneID,
                        'work_zone_code': this.workZoneCode,
                        'work_zone_name': this.workZoneName,
                        'actual_work_time': this.actualWorkTimeSumSerial,
                        'break_time': this.restTimeSumSerial,
                        'actual_start_time': this.prescribeWorkTimeStartInput,
                        'actual_end_time': this.prescribeWorkTimeEndInput,
                        'work_zone_aggrigation_class': this.workZoneAggrigationClass,
                        'break1_start_time': this.prescribedRestTime[0].start.input,
                        'break1_end_time': this.prescribedRestTime[0].end.input,
                        'break2_start_time': this.prescribedRestTime[1].start.input,
                        'break2_end_time': this.prescribedRestTime[1].end.input,
                        'break3_start_time': this.prescribedRestTime[2].start.input,
                        'break3_end_time': this.prescribedRestTime[2].end.input,
                    }
                }).then(response => {
                    if(response.data.result)
                    {
                        this.op1.callback_regist();
                        //モーダルを閉じる
                        $('.modal-backdrop').remove();
                        $('#' + this.id).modal('hide');
                    }
                    else
                    {
                        this.modalOption.message = response.data.values.message;
                        this.openModal("m112_common_message", "", this.modalOption);
                    }
                });
            }
        },
        //入力チェック
        validate(){
            this.isError = false;
            this.errorMessageArray = [];
            //コード
            if(this.workZoneCode === null || this.workZoneCode.length === 0)
            {
                this.errorMessageArray.push("コードを入力してください");
                this.isError = true;
            }
            else if(20 < this.workZoneCode.length)
            {
                this.errorMessageArray.push("コードは20文字以下としてください");
                this.isError = true;
            }
            //名称
            if(this.workZoneName.length < 2 || this.workZoneName.length > 30)
            {
                this.errorMessageArray.push("名称は2文字以上30文字以下としてください");
                this.isError = true;
            }
            //実働時間
            if(this.actualWorkTimeSumSerial === 0)
            {
                this.errorMessageArray.push("所定実働が0時間の登録はできません");
                this.isError = true;
            }
            //所定休憩時間
            else if(60 * 6 < this.actualWorkTimeSumSerial && this.actualWorkTimeSumSerial < 60 * 8 && this.restTimeSumSerial < 45)
            {
                this.errorMessageArray.push("所定実働が6時間より多いため、所定時間内の所定休憩の合計を45分以上としてください");
                this.isError = true;
            }
            else if(60 * 8 <= this.actualWorkTimeSumSerial && this.restTimeSumSerial < 60){
                this.errorMessageArray.push("所定実働が8時間より多いため、所定時間内の所定休憩の合計を60分以上としてください");
                this.isError = true;
            }
            for(let i = 0; i < this.prescribedRestTime.length; i++)
            {
                //休憩時間未入力はスルー
                if(this.prescribedRestTime[i].start.input === null && this.prescribedRestTime[i].end.input === null)
                {
                    continue;
                }
                //休憩時間の入力不備
                else if(this.prescribedRestTime[i].start.input === null || this.prescribedRestTime[i].end.input === null)
                {
                    this.errorMessageArray.push("所定休憩" + (i + 1) + "の開始時刻、終了時刻はセットで入力してください");
                    this.isError = true;
                    continue;
                }
                //開始と終了が同じ
                else if(this.prescribedRestTime[i].start.input === this.prescribedRestTime[i].end.input)
                {
                    this.errorMessageArray.push("所定休憩" + (i + 1) + "の開始時刻と終了時刻は異なる時間を入力してください");
                    this.isError = true;
                    continue;
                }
                //休憩時間は実働時間の範囲内か
                if(this.prescribedRestTime[i].start.input <= this.prescribeWorkTimeStartInput || this.prescribeWorkTimeEndInput <= this.prescribedRestTime[i].end.input)
                {
                    this.errorMessageArray.push("所定休憩" + (i + 1) + "は所定時間外に設定されています");
                    this.isError = true;
                    continue;
                }
                //休憩時間内に被りは無いか
                let isDupulicate = false;
                for(let j = 0; j < i; j++)
                {
                    //j.start < i.startの時
                    if(this.prescribedRestTime[j].start.input < this.prescribedRestTime[i].start.input)
                    {
                        //j.start < i.start < j.endはダメ
                        if(this.prescribedRestTime[i].start.input < this.prescribedRestTime[j].end.input)
                        {
                            isDupulicate = true;
                            break;
                        }
                    }
                    //i.start < j.startの時
                    else
                    {
                        //i.start < j.start < i.endはダメ
                        if(this.prescribedRestTime[j].start.input < this.prescribedRestTime[i].end.input)
                        {
                            isDupulicate = true;
                            break;
                        }
                    }
                    //j.end < i.endの時
                    if(this.prescribedRestTime[j].end.input < this.prescribedRestTime[i].end.input)
                    {
                        //i.start < j.end < i.endはダメ
                        if(this.prescribedRestTime[i].start.input < this.prescribedRestTime[j].end.input)
                        {
                            isDupulicate = true;
                            break;
                        }
                    }
                    //i.end < j.endの時
                    else
                    {
                        //j.start < i.end < j.endはダメ
                        if(this.prescribedRestTime[j].start.input < this.prescribedRestTime[i].end.input)
                        {
                            isDupulicate = true;
                            break;
                        }
                    }
                }
                if(isDupulicate)
                {
                    this.errorMessageArray.push("所定休憩の内、重複している時間帯があります");
                    this.isError = true;
                    continue;
                }
            }

            return !this.isError;
        },
        //P114-02 キャンセル処理
        cancelClick() {
            this.op1.callback_cancel();
        },
    },
    mounted(){
        if(this.op1.workZoneID !== null)
        {
            //変更
            this.workZoneID = this.op1.workZoneID;
            const workZone = this.getMasterData().work_zone.find(elm => elm.work_zone_id == this.op1.workZoneID);
            const workZoneTime_work = this.getMasterData().work_zone_time.find(elm => elm.work_zone_id == this.op1.workZoneID && elm.time_type_class == 1);
            const workZoneTime_rest = this.getMasterData().work_zone_time.filter(elm => elm.work_zone_id == this.op1.workZoneID && elm.time_type_class == 2);
            
            this.targetOfficeID = workZone.office_id;
            this.workZoneName = workZone.work_zone_name;
            this.workZoneCode = workZone.work_zone_code;
            this.prescribeWorkTimeStart = workZoneTime_work.start_time;
            this.prescribeWorkTimeStartInput = workZoneTime_work.start_time;
            this.prescribeWorkTimeEnd = workZoneTime_work.end_time;
            this.prescribeWorkTimeEndInput = workZoneTime_work.end_time;
            this.workZoneAggrigationClass = workZone.work_zone_aggrigation_class;
            for(let i = 0; i < workZoneTime_rest.length; i++)
            {
                this.prescribedRestTime[i].start.calc = workZoneTime_rest[i].start_time;
                this.prescribedRestTime[i].start.input = workZoneTime_rest[i].start_time;
                this.prescribedRestTime[i].end.calc = workZoneTime_rest[i].end_time;
                this.prescribedRestTime[i].end.input = workZoneTime_rest[i].end_time;
            }
        }
        else
        {
            //新規
            //nothing to do 
        }
    },
    computed:{
        officeList: function(){
            return this.getMasterData().office;
        },
        //ボタン押下可否チェック
        isEnableRegist: function(){
            return true; //バリデーション働くのでチェックなし
        },
        //実働時間シリアル値
        actualWorkTimeSumSerial: function(){
            let actualWorkTime = 0;
            //所定実働の計算
            if(!!this.prescribeWorkTimeEndInput && !!this.prescribeWorkTimeStartInput)
            {
                actualWorkTime = this.prescribeWorkTimeEndInput - this.prescribeWorkTimeStartInput;
                if(actualWorkTime < 0 && this.prescribeWorkTimeEndInput < 2 * 24 * 60)
                {
                    //日またぎ発生
                    this.prescribeWorkTimeEnd = this.prescribeWorkTimeEndInput + 24 * 60;
                    actualWorkTime += 24 * 60;
                }
                if(24 * 60 <= actualWorkTime)
                {
                    //日またぎ解消
                    this.prescribeWorkTimeEnd = this.prescribeWorkTimeEndInput - 24 * 60;
                    actualWorkTime -= 24 * 60;
                }
            }
            //所定休憩を引く
            return this.restTimeSumSerial <= actualWorkTime ? actualWorkTime - this.restTimeSumSerial : 0;
        },
        //所定休憩シリアル値
        restTimeSumSerial: function(){
            let restTimeSum = 0;
            //休憩時間１
            for(let i = 0; i < this.prescribedRestTime.length; i++)
            {
                let restTime = 0;
                if(!!this.prescribedRestTime[i].start.input && !!this.prescribedRestTime[i].end.input)
                {
                    restTime = this.prescribedRestTime[i].end.input - this.prescribedRestTime[i].start.input;
                    if(restTime < 0 && this.prescribedRestTime[i].end.input < 2 * 24 * 60)
                    {
                        //日またぎ発生
                        this.prescribedRestTime[i].end.calc = this.prescribedRestTime[i].end.input + 24 * 60;
                        restTime += 24 * 60;
                    }
                    if(24 * 60 <= restTime)
                    {
                        //日またぎ解消
                        this.prescribedRestTime[i].end.calc = this.prescribedRestTime[i].end.input - 24 * 60;
                        restTime -= 24 * 60;
                    }
                }
                restTimeSum += restTime;
            }
            return restTimeSum;
        },
        actualWorkTimeSum: function(){
            return this.serialToHoursStr(this.actualWorkTimeSumSerial);
        },
        restTimeSum: function(){
            return this.serialToHoursStr(this.restTimeSumSerial);
        }
    }
};
</script>

<style>
    .c114_01_30 {
        margin-top: 12px;
    }
</style>
