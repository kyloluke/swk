<template>
    <div class="d-flex justify-content-center" style="position: relative; top: 200px;">
        <div style="width: 440px; background: #efefef; border-radius: 8px; padding: 20px;">
            <loading :active.sync="isLoading"
                :can-cancel="true"
                :on-cancel="onCancel"
                :is-full-page="fullPage"></loading> 
            <div>
                <div class="form-group row mt-3">
                    <div class="col-sm-1"></div>
                    <div class="col-sm-10">
                        <label for="tennantID" id="tennantID">テナントID</label>
                        <input type="text" v-model="companyCodeInput" class="form-control" id="tennantID" placeholder="テナントID" @keyup.enter="inputToPassword" ref="loginTennant">
                    </div>
                    <div class="col-sm-1"></div>
                </div>
                <div class="form-group row mt-3">
                    <div class="col-sm-1"></div>
                    <div class="col-sm-10">
                        <label for="userID">ユーザーID</label>
                        <input type="text" v-model="employeeNumbertextInput" class="form-control" id="userID" placeholder="ユーザーID" @keyup.enter="inputToPassword" ref="loginCode">
                    </div>
                    <div class="col-sm-1"></div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-1"></div>
                    <div class="col-sm-10">
                        <label for="password">パスワード</label>
                        <input type="password" v-model="passwordInput" class="form-control" id="password" placeholder="パスワード" @keyup.enter="inputToLogin" ref="loginPassword">
                    </div>
                    <div class="col-sm-1"></div>
                </div>
                <div class="message-group row" v-if="message !== null">
                    <div class="col-sm-1"></div>
                    <div class="error-message text-center col-sm-10">
                        <div>{{message}}</div>
                    </div>
                    <div class="col-sm-1"></div>
                </div>

                <div class="d-flex justify-content-center">
                    <button type="button" class="btn btn-primary w-35" v-on:click="loginClick" ref="loginButton">ログイン</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Loading from 'vue-loading-overlay';
import 'vue-loading-overlay/dist/vue-loading.css';

export default {
    name: "multi_login",
    components: {
        "loading":Loading
    },
    data() {
        return {
            message: null,
            employeeNumbertextInput: '',
            passwordInput: '',
            employee_id: '',
            companyCodeInput: '',
            exec_status: 0,
            isLoading: false,  //ローディング画面用プロパティ
            fullPage: true,    //ローディング画面用プロパティ
        }
    },
    methods: {
        async loginClick() {
            //ローディング画面表示
            this.isLoading = true;
            this.message = '';
            //Code取得
            const res_id = await this.getEmployeeID();
            if(res_id.data.employee_id !== null){
                this.employee_id = res_id.data.employee_id
                if(0 < this.employee_id)
                {
                    //ログイン実行
                    await this.execLogin();
                    if(this.exec_status == 200 || this.exec_status == 204)
                    {
                        //認証成功
                        window.location.href = '/app';
                    }
                    else
                    {
                        //失敗
                        this.message = '社員IDとパスワードを確認してください';
                        this.focusToInputCodeButton();
                    }
                }
                else
                {
                    //存在しないcodeの場合
                    this.message = '社員IDとパスワードを確認してください';
                    this.focusToInputCodeButton();
                }
            }
            else
            {
                this.message = '会社IDと社員IDを確認してください';
            }
            this.isLoading = false;
        },
        //Loading画面のキャンセル
        onCancel() {
        },
        cancelClick() {
        },
        async getEmployeeID(){
            let ret = null;
            await axios.post('/get_id_by_code', {
                company_code: this.companyCodeInput, 
                employee_code: this.employeeNumbertextInput,
            })
            .then(response => (ret = response))
            .catch(function(error){
                //何らかのエラー
                alert("通信エラーが発生しました。ページを再度読み込みなおしてください。");
            });
            return ret;
        },
        async execLogin(){
            let ret = axios.post('/login', {
                employee_id: "" + this.employee_id,
                password: this.passwordInput
            })
            .then(response => {
                //ログイン認証成功
                this.exec_status = response.status;
            })
            .catch(error => {
                //422エラーの場合、検証エラー
                this.exec_status = error.response.status;
                console.log(error.response);
            });
            return ret;
        },
        //パスワード入力部へフォーカス移動
        inputToPassword (event) {
            if(event.keyCode === 13)
            {
                this.$refs.loginPassword.focus();
            }
        },
        //パスワード入力部へフォーカス移動
        inputToLogin (event) {
            if(event.keyCode === 13)
            {
                this.$refs.loginButton.focus();
            }
        },
        //社員コード入力部へフォーカス
        focusToInputCodeButton(){
            //誤操作防止のために遅延させる
            setTimeout(()=>{
                this.$refs.loginCode.focus();
            }, 500);
        }
    },
    mounted(){
        this.message = null;
        this.employeeNumbertextInput = '';
        this.passwordInput = '';
        //社員コード入力へフォーカス
        this.$nextTick(function(){
            this.focusToInputCodeButton();
        });
        let uri = window.location.href.split('?');
        if (uri.length == 2)
        {
            let vars = uri[1].split('&');
            let tmp = '';
            let companyCodeInput = '';
            vars.forEach(function(v){
                tmp = v.split('=');
                if(tmp.length == 2){
                    if(tmp[0] == 'ccode'){
                        companyCodeInput = tmp[1];
                    }
                }
            });
            this.companyCodeInput = companyCodeInput;
        }
    }
};
</script>
