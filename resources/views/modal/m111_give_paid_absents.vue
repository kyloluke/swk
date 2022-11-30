<template>
    <div class="modal-content" id="C111-01">
        <div class="modal-body">
            <div id="C111-01-01" class="d-flex justify-content-center">{{headMessage}}</div>
            <div id="C111-01-02" class="mb-3 d-flex justify-content-center">※グレーの項目は修正できません</div>
            <div class="row mb-3">
                <div class="col-sm-1"></div>
                    <label id="C111-01-03" class="col-sm-3 col-form-label">種別</label>
                    <select id="C111-01-04" class="col-sm-7 form-control" v-model="absentType" v-bind:disabled="op1.isFixMode">
                        <option v-for="item in holidayList" :key="item.holiday_id" v-bind:value = "item.holiday_id" v-html = "item.holiday_name"></option>
                    </select>
                <div class="col-sm-1"></div>
            </div>
            <div class="row mb-3">
                <div class="col-sm-1"></div>
                    <label id="C111-01-05" class="col-sm-3 col-form-label">付与日</label>
                    <input id="C111-01-06" class="col-sm-4 form-control" type="date" v-model="giveDay" min="1901-01-01" v-bind:disabled="op1.isFixMode" />
                <div class="col-sm-4"></div>
            </div>
            <div class="row mb-3">
                <div class="col-sm-1"></div>
                    <label id="C111-01-07" class="col-sm-3 col-form-label">期限</label>
                    <input id="C111-01-08" class="col-sm-4 form-control" type="date" v-model="limitDay" min="1901-01-01" v-bind:disabled="op1.isFixMode" />
                <div class="col-sm-4"></div>
            </div>
            <div class="row mb-3">
                <div class="col-sm-1"></div>
                    <label id="C111-01-09" class="col-sm-3 col-form-label">付与日数</label>
                    <input id="C111-01-10" type="number"  min="0" class="col-sm-2 form-control" v-model="giveNums" />
                    <div id="C111-01-11" class="col-sm-1 form-text d-flex align-items-end">日</div>
                <div class="col-sm-5"></div>
            </div>
            <div class="row mb-3">
                <div class="col-sm-1"></div>
                    <label id="C111-01-12" class="col-sm-3 col-form-label">使用済み日数</label>
                    <input id="C111-01-13" type="number"  min="0" class="col-sm-2 form-control" v-model="usedNums" v-bind:disabled="!op1.isFixMode" />
                    <div id="C111-01-14" class="col-sm-1 form-text d-flex align-items-end">日</div>
                <div class="col-sm-5"></div>
            </div>
      　    <div class="message-group row ml-1 mr-1 pt-3">
                <div id="C111-01-17" class="error-message text-center col-sm-12">
                    <div>{{errorMessage}}</div>
                </div>
            </div>
            <div class="modal-footer d-flex justify-content-center">
                <button id="C111-01-15" type="button" class="btn btn-primary w-35" style="margin-right: 80px" v-if="!op1.isFixMode" v-on:click="registClick" v-bind:disabled="!isEnableRegist">登録</button>
                <button id="C111-01-18" type="button" class="btn btn-primary w-35" style="margin-right: 80px" v-if="op1.isFixMode" v-on:click="editClick" v-bind:disabled="!isEnableEdit">修正</button>
                <button id="C111-01-16" type="button" class="btn btn-danger w-35" data-dismiss="modal" v-on:click="cancelClick">キャンセル</button>
            </div>
        </div>
    </div>

</template>

<script>
export default {
    props: ['op1', 'id'],
    data() {
        return {
            absentType: null,
            giveDay: null,
            limitDay: null,
            giveNums: null,
            usedNums: null,
            headMessage: null,
            errorMessage: null,
            today: null,
            holidayList: [],
        }
    },
    methods: {
        //P111-05 登録処理
        registClick() {

            if(typeof(this.absentType) == "undefined"){
                this.errorMessage = "休暇の種別を指定してください";
            }
            else if(this.giveNums <= 0){
                this.errorMessage = "付与日数は1以上を指定してください";
            }
            else if(this.checkDate(this.giveDay) == -1){
                this.errorMessage = "正確な付与日を入力してください";
            }
            else if(this.checkDate(this.limitDay) == -1){
                this.errorMessage = "正確な期限を入力してください";
            }
            else if(this.giveDay > this.limitDay){
                this.errorMessage = "期限は付与日以降を指定してください";
            }
            else if(Math.floor(this.todaySerial()) > Math.floor(this.checkDate(this.limitDay))){
                this.errorMessage = "期限は当日以降を指定してください";
            }
            else{
                //【要対応】データベース登録処理
//              if(){
//                  this.errorMessage = "データベースエラーが発生しました。";
//              }
//              else{

                //画面にデータ戻す
                this.op1.callback_regist(this.absentType, this.giveDay, this.limitDay, this.giveNums);
                //モーダルを閉じる
                $('.modal-backdrop').remove();
                $('#' + this.id).modal('hide');
//              }
            }
        },
        //P111-06 修正処理
        editClick() {
            if(this.giveNums <= 0){
                this.errorMessage = "付与日数は1以上を指定してください";
            }
            else if(this.usedNums > this.giveNums){
                this.errorMessage = "使用済み日数は付与日数以下を指定してください";
            }
            else{
                //【要対応】データベース登録処理
//              if(){
//                  this.errorMessage = "データベースエラーが発生しました。";
//              }
//              else{

                //画面にデータ戻す
                this.op1.callback_edit(this.giveNums, this.usedNums);
                //モーダルを閉じる
                $('.modal-backdrop').remove();
                $('#' + this.id).modal('hide');
//              }
            }
        },
        //P111-02 キャンセル処理
        cancelClick() {
            this.op1.callback_cancel();
        },
        //バリデーション
        validateRegist(){
            //未入力チェック
            if(this.absentType === null || this.giveDay === null || this.limitDay === null || this.giveNums === null
            || this.absentType === ""   || this.giveDay === ""   || this.limitDay === ""   || this.giveNums === "")
            {
                return false;
            }
            //問題なし
            return true;
        },
        validateEdit(){
            //未入力チェック
            if(this.giveNums === null || this.usedNums === null
            || this.giveNums === ""   || this.usedNums === "")
            {
                return false;
            }
            //問題なし
            return true;
        }
    },
    mounted(){

        axios.get('m043_holiday_list', {
        }).then(response => {
            if(response.data.result)
            {
                this.holidayList = [];
                for(let i = 0; i < response.data.holidayList.length; i++)
                {
                    if(response.data.holidayList[i].grant_enable_class == 1){
                        if(this.op1.firstPaidFlg && (response.data.holidayList[i].holiday_management_class == 1 || response.data.holidayList[i].holiday_management_class == 2)){
                            continue;
                        }
                        this.holidayList.push({
                            holiday_id: response.data.holidayList[i].holiday_id,
                            holiday_name: response.data.holidayList[i].holiday_name,
                        });
                    }
                }  
            }
            else
            {
                //取得失敗
            }
        });

        if(this.op1.isFixMode){
            this.headMessage = "修正情報を入力してください"
            this.absentType = this.op1.absentType;
            this.giveDay = this.serialToDateStr(this.op1.giveDay, "YYYY-MM-DD");
            this.limitDay = this.serialToDateStr(this.op1.limitDay, "YYYY-MM-DD");
            this.giveNums = this.op1.giveNums;
            this.usedNums = this.op1.usedNums;
        }
        else{
            this.headMessage = "登録する有給休暇を入力してください"
            this.absentType = this.op1.absentType;
            this.giveDay = this.serialToDateStr(this.op1.giveDay, "YYYY-MM-DD");
            this.limitDay = this.serialToDateStr(this.op1.limitDay, "YYYY-MM-DD");
            this.giveNums = 1;
            this.usedNums = 0;
        }

    },
    computed:{
        //バリデーションとボタン押下可否を紐づけ
        isEnableRegist: function(){
            return this.validateRegist();
        },
        isEnableEdit: function(){
            return this.validateEdit();
        },
    }
};
</script>
