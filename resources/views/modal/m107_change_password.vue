<template>
    <div class="modal-content" id="C107-01">
        <div class="modal-body">
    　      <div id="C107-01-01" class="mb-3 d-flex justify-content-center">パスワード変更を行います</div>
            <div class="form-group row">
                <div class="col-sm-1 mb-2"></div>
                <div class="col-sm-10 mb-2">
                    <label id="C107-01-02" class="modal-form-label" for="C107-01-03">現在のパスワード</label>
                    <input id="C107-01-03" type="password" v-model="currentPasswordInput" class="form-control" placeholder="現在のパスワード" value="">
                </div>
                <div class="col-sm-1 mb-2"></div>
            </div>
            <div class="form-group row">
                <div class="col-sm-1"></div>
                <div class="col-sm-10">
                    <label id="C107-01-04" class="modal-form-label" for="C107-01-05">新パスワード</label>
                    <input id="C107-01-05" type="password" v-model="newPasswordInput" class="form-control" placeholder="新パスワード" value="">
                </div>
                <div class="col-sm-1"></div>
            </div>
            <div class="form-group row">
                <div class="col-sm-1"></div>
                <div class="col-sm-10">
                    <label id="C107-01-06" class="modal-form-label" for="C107-01-07">新パスワード（確認用）</label>
                    <input id="C107-01-07" type="password" v-model="comfilmPasswordInput" class="form-control" placeholder="新パスワード（確認用）" value="">
                </div>
                <div class="col-sm-1"></div>
            </div>
      　    <div id="C107-01-10" class="message-group row" v-if="message !== null">
                <div class="col-sm-1"></div>
                <div class="error-message text-center col-sm-10">
                    <div>{{message}}</div>
                </div>
                <div class="col-sm-1"></div>
            </div>

            <div class="modal-footer d-flex justify-content-center">
                <button id="C107-01-08" type="button" class="btn btn-primary w-35" style="margin-right: 80px" v-on:click="changeClick">変更</button>
                <button id="C107-01-09" type="button" class="btn btn-danger w-35" data-dismiss="modal">キャンセル</button>
            </div>
        </div>
    </div>

</template>

<script>
export default {
    data() {
        return {
            message: null,
            currentPasswordInput: '',
            newPasswordInput: '',
            comfilmPasswordInput: '',
            modalOption: {
                message: '',
                buttons:[
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
            modalOption_failed: {
                message: '',
                buttons:[
                    {
                        exec : ()=>{
                            this.btn="OK";
                        },
                        caption : "OK",  //'OK','はい','いいえ','キャンセル','再試行','閉じる'
                        btnclass : "btn-danger"
                    }],
            },
        }
    },
    methods: {
        changeClick() {
            var saveflg = true;
            this.message = null;

            //E107-01-001
            if(!this.currentPasswordInput || !this.newPasswordInput || !this.comfilmPasswordInput){
                this.message = '現在のパスワード、新パスワード、確認用パスワードを入力してください';
            }
            //E107-01-003
            else if(this.currentPasswordInput === this.newPasswordInput){
                this.message = '旧パスワードと異なる新しいパスワードを再度入力してください';
            }
            //E107-01-004
            else if(this.newPasswordInput !== this.comfilmPasswordInput){
                this.message = '新パスワードと確認用パスワードが一致しません';
            }
            //E107-01-005
            else if((this.newPasswordInput.length > 12) || (this.newPasswordInput.length < 4)){
                this.message = 'パスワードは4～12文字で入力してください';
            }
            //E107-01-006
            else if(!this.newPasswordInput.match(/^[A-Za-z0-9]*$/)){
                this.message = 'パスワードは半角英数のみ使用可能です';
            }

            //送信ボタン本来の動作をキャンセル     
            if(this.message !== null){
                saveflg = false;
            }

            if(saveflg){
                //パスワード更新
                this.updatePassword(this.currentPasswordInput, this.newPasswordInput, this.comfilmPasswordInput);
            }
        },
        //社員番号とパスワードを照合
        updatePassword(currentpass_str,newpass_str,comformpass_str){
            axios.post('/update_password', {
                old_input_password: currentpass_str,
                input_password: newpass_str,
                comform_password: comformpass_str,
            })
            .then(response => {
                if(response.data.result)
                {
                    //モーダルを開く
                    this.modalOption.message = response.data.values.message;
                    this.openModal("m112_common_message", "", this.modalOption);
                }
                else
                {
                    //モーダルを開く
                    this.modalOption_failed.message = response.data.values.message;
                    this.openModal("m112_common_message", "", this.modalOption_failed);
                }
                return response.data.result;
            })
            .catch(function(error){
                //何らかのエラー
                this.modalOption_failed.message = "不明なエラーが発生しました。ログアウトの後、再度お試しください。";
                this.openModal("m112_common_message", "", this.modalOption_failed);
            });
        },
    },
    mounted(){  //P107-01 初期化処理
        this.message = null,
        this.currentPasswordInput = '',
        this.newPasswordInput = '',
        this.comfilmPasswordInput = ''
    }
};
</script>
