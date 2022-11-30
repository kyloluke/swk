<template>
    <div class="modal-content" id="C113-01">
        <div class="modal-body">
    　      <div id="C113-01-01" class="mb-3 d-flex justify-content-center">対象期間を指定してください</div>
            <div class="row d-flex justify-content-center">
                <label id="C113-01-02" class="col-form-label mr-2">開始</label>
                <div class="form-group" style="width: 160px;">
                    <input id="C113-01-03" type="date" class="form-control" v-model="selectedStartDate" min="1901-01-01">
                </div>
                <label id="C113-01-04" class="col-form-label ml-3 mr-2">終了</label>
                <div class="form-group" style="width: 160px;">
                    <input id="C113-01-05" type="date" class="form-control" v-model="selectedEndDate" min="1901-01-01">
                </div>
            </div>
            <div class="modal-footer d-flex justify-content-center">
                <button id="C113-01-06" type="button" class="btn btn-primary w-35" data-dismiss="modal" style="margin-right: 80px" v-on:click="selectClick" v-bind:disabled="!isEnableSelect">選択</button>
                <button id="C113-01-07" type="button" class="btn btn-danger w-35" data-dismiss="modal" v-on:click="cancelClick">キャンセル</button>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: ['op1'],
    data() {
        return {
            selectedStartDate: "",
            selectedEndDate: "",
        }
    },
    methods: {
        //P113-04 選択処理
        selectClick() {
            if(this.checkDate(this.selectedStartDate) != -1 && this.checkDate(this.selectedEndDate) != -1){
                this.op1.callback_select(this.checkDate(this.selectedStartDate), this.checkDate(this.selectedEndDate));
            }   
        },
        //キャンセル処理
        cancelClick() {
            this.op1.callback_cancel();
        },
        //バリデーション
        validateDate(){

            if(this.checkDate(this.selectedStartDate) == -1 || this.checkDate(this.selectedEndDate) == -1){
                return false;
            }

            //変な入力チェック
            if(this.checkDate(this.selectedEndDate) == null || this.checkDate(this.selectedStartDate) == null)
            {
                return false;
            }

            //大小関係
            if(this.checkDate(this.selectedEndDate) < this.checkDate(this.selectedStartDate))
            {
                return false;
            }

            //問題なし
            return true;
        }
    },
    mounted(){
        //P113-05 年月日指定処理
        if(this.op1.select_period_type === 1){
            $(function() {
                //vuejs-datepickerへ変更のため、暫定的にコメントアウト
                //$("#C113-01-03").datepicker().datepicker('setDate', 'today');
                //$("#C113-01-05").datepicker().datepicker('setDate', 'today');
            });
        }
        //P113-06 年月指定処理
        else{
            $(function() {
                //vuejs-datepickerへ変更のため、コメントアウト
                //$("#C113-01-03").datepicker({dateFormat: 'yy/mm'}).datepicker('setDate', 'today');
                //$("#C113-01-05").datepicker({dateFormat: 'yy/mm'}).datepicker('setDate', 'today');
            });
        }
        //選択初期値指定
        if(0 < this.op1.selectedStartDateSerial)
        {
            this.selectedStartDate = this.serialToDateStr(this.op1.selectedStartDateSerial, "YYYY-MM-DD");
        }
        if(0 < this.op1.selectedEndDateSerial)
        {
            this.selectedEndDate = this.serialToDateStr(this.op1.selectedEndDateSerial, "YYYY-MM-DD");
        }
        
    },
    created() {
        //vuejs-datepickerへ変更のため、コメントアウト
        //$("#C113-01-03").remove();
        //$("#C113-01-05").remove();
    },
    computed:{
        //バリデーションと選択可否を紐づけ
        isEnableSelect: function(){
            return this.validateDate();
        }
    }
};
</script>
