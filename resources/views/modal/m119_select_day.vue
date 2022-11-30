<template>
    <div class="modal-content" id="C119-01" style="width: 350px">
        <div class="modal-body">
    　      <div id="C119-01-01" class="mb-3 d-flex justify-content-center">基準日を指定してください</div>
            <div class="row d-flex justify-content-center">
                <div class="form-group" style="width: 160px;">
                    <input id="C119-01-03" type="date" class="form-control" v-model="selectedStartDate" min="1901-01-01">
                </div>
            </div>
            <div class="modal-footer d-flex justify-content-center">
                <button id="C119-01-06" type="button" class="btn btn-primary w-35" data-dismiss="modal" style="margin-right: 40px" v-on:click="selectClick" v-bind:disabled="!isEnableSelect">選択</button>
                <button id="C119-01-07" type="button" class="btn btn-danger w-35" data-dismiss="modal" v-on:click="cancelClick">キャンセル</button>
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
        }
    },
    methods: {
        //P119-04 選択処理
        selectClick() {
            if(this.checkDate(this.selectedStartDate) != -1){
                this.op1.callback_select(this.checkDate(this.selectedStartDate));
            }   
        },
        //キャンセル処理
        cancelClick() {
            this.op1.callback_cancel();
        },
        //バリデーション
        validateDate(){

            //入力チェック
            if(this.checkDate(this.selectedStartDate) == -1 || this.checkDate(this.selectedStartDate) == null){
                return false;
            }
            return true;
        }
    },
    mounted(){
        //選択初期値指定
        if(0 < this.op1.selectedStartDateSerial)
        {
            this.selectedStartDate = this.serialToDateStr(this.op1.selectedStartDateSerial, "YYYY-MM-DD");
        }        
    },
    computed:{
        //バリデーションと選択可否を紐づけ
        isEnableSelect: function(){
            return this.validateDate();
        }
    }
};
</script>
