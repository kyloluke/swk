<template>
    <div>
        <loading :active.sync="isLoading"
            :can-cancel="true"
            :on-cancel="onCancel"
            :is-full-page="fullPage"></loading> 
        <div class="container text-center">
            <div v-if="!is_multitennant" class="row">
                <div class="col-4 text-center">
                    <div id="C003-01-03" class="text-center " style="font-size:20pt">社員番号</div>
                </div>
                <div class="col-8 text-left">
                    <input id="C003-01-04" name="C003-01-04" style="font-size:16pt;width:100%" type="text" v-model='employeeCodeInput' @keyup.enter="inputToPassword" ref="webPunchCode">
                </div>
            </div>
            <div v-if="!is_multitennant" class="row" style="margin-top:20pt;">
                <div class="col-4 text-center">
                    <div id="C003-01-05" class="text-center " style="font-size:20pt">パスワード</div>
                </div>
                <div class="col-8 text-left">
                    <input id="C003-01-06" name="C003-01-06" style="font-size:16pt;width:100%" type="password" v-model='employeePasswordInput' ref="webPunchPassword">
                </div>
            </div>
            <div class="w-100 text-danger" style="background-color:#f0f8ff;font-size:11pt;margin-top:20pt;" v-if="error_message !== null">
                <div id="C003-01-10" class="d-inline-block" > エラー：  </div>
                <div id="C003-01-11" class="d-inline-block" > {{error_message}} </div>
            </div>
            <div class="row" style="margin-top:20pt;">
                <div class="col-6 text-left">
                    <button id="C003-01-07" name="C003-01-07" value ="1" class="btn w-100 btn-primary" v-bind:class="isAttendanceWork ? $style.c003_01_07_btnstyle_reverse : ''" style="font-size:40pt;border: 5px solid;" v-on:click="webPunch(1)" @keydown.enter="onPressEnterWebPunch(1)" v-on:mouseover="buttonReverse(1)" v-on:mouseleave="buttonNormal(1)" v-on:focus="buttonReverse(1)" v-on:blur="buttonNormal(1)">出勤</button>
                </div>
                <div class="col-6 text-right">
                    <button id="C003-01-08" name="C003-01-08" value ="2" class="btn w-100 btn-danger" v-bind:class="isLeavingWork ? $style.c003_01_08_btnstyle_reverse : ''" style="font-size:40pt;border:5px solid;margin-left:20%;" v-on:click="webPunch(2)" v-on:mouseover="buttonReverse(2)" v-on:mouseleave="buttonNormal(2)" v-on:focus="buttonReverse(2)" v-on:blur="buttonNormal(2)">退勤</button>
                </div>
            </div>
            <div style="text-align:center;margin-top:20pt;">
                <button class="btn btn-block text-white" style="background-color:#ff6600;font-size:14pt;width:109.5%;" type="button" data-toggle="collapse" data-target=".multi-collapse" aria-expanded="false" aria-controls="C003-01-09">外出 ／ 戻り ▽</button>
                <div class="collapse multi-collapse" style="background-color:#FFCC99;width:109.5%;"  id="C003-01-09">
                    <button id="C003-01-12" class="btn m-3 text-white" v-bind:class="isGoOut ? $style.go_out_and_return_reverse : ''" style="background-color:#ff6600;width:40%;font-size:24pt;border:solid;border-color:#ff6600;" v-on:click="webPunch(3)" v-on:mouseover="buttonReverse(3)" v-on:mouseleave="buttonNormal(3)" v-on:focus="buttonReverse(3)" v-on:blur="buttonNormal(3)">外出</button>
                    <button id="C003-01-13" class="btn m-3 text-white" v-bind:class="isReturn ? $style.go_out_and_return_reverse : ''" style="margin-left: 10% !important;background-color:#ff6600;width:40%;font-size:24pt;border:solid;border-color:#ff6600;" v-on:click="webPunch(4)" v-on:mouseover="buttonReverse(4)" v-on:mouseleave="buttonNormal(4)" v-on:focus="buttonReverse(4)" v-on:blur="buttonNormal(4)">戻り</button>  
                </div> 
            </div>
        </div>

    </div>
</template>
<script>
import Loading from 'vue-loading-overlay';
import 'vue-loading-overlay/dist/vue-loading.css';

export default {
    components: {
        "loading":Loading
    },
    props:{
        employee_id: Number,
        session_data: Object,
    },
    name: 'C003_01_web_puch',
    data () {
        return {
            is_authorized: false,
            is_punch_success: false,
            error_message: null,
            employeeCodeInput: '',
            employeePasswordInput: '',
            clocking_in_out_id: 0,
            employeeID: 0,
            stamped_info: {},
            isLoading: false,  //ローディング画面用プロパティ
            fullPage: true,    //ローディング画面用プロパティ
            isAttendanceWork: false,
            isLeavingWork: false,
            isGoOut: false,
            isReturn: false,
            is_multitennant: false,
        }
    },
    methods: {
        //Web打刻実行
        async webPunch(in_out_id){
            //ローディング画面表示
            this.isLoading = true;
            //押されたときのボタン保持
            this.clocking_in_out_id = in_out_id;
            //初期化
            this.initParams();

            //パスワード照合
            await this.checkPassword();
            if(this.is_authorized)
            {
                //打刻実施
                await this.execWebPunch();
                //モーダルオープン
                if(this.is_punch_success)
                {
                    //cleanしてからOpen
                    this.cleanModal();
                    this.openModal('m103_record_time_result', '', {stamp_info :this.stamped_info, callback: this.activaWebPunchCodeInput});
                    //画面初期化
                    this.initParams();
                    //入力値クリア
                    this.employeeCodeInput = '';
                    this.employeePasswordInput = '';
                }
                else
                {
                    //打刻エラー
                    this.error_message = '打刻時にエラーが発生しました。再度実行してください'
                }
            }
            this.isLoading = false;

        },
        //社員番号とパスワードを照合
        async checkPassword(){
            if(this.is_multitennant){
                this.is_authorized = true;
                return;
            }
            let ret = axios.post('checkWebPunchPassword', {
                company_id: 1, //リリース時1で固定
                employee_code: this.employeeCodeInput,
                input_password: this.employeePasswordInput,
            }).then(response => {
                if(response.data.result)
                {
                    //認証OK valueに社員ID返ってくる
                    this.employeeID = response.data.values.employee_id;
                    this.is_authorized = true;
                }
                else
                {
                    //認証NG
                    //エラーメッセージ表示
                    this.error_message = '社員番号とパスワードを確認してください';
                    this.is_authorized = false;
                    //社員コードへフォーカス移動
                    this.activaWebPunchCodeInput();
                }
            }).catch((error)=>{
                alert("通信エラーが発生しました。ページを再度読み込みなおしてください。");
            });
            return ret;
        },
        //Web打刻実行（DBへ登録）
        async execWebPunch(){
            this.is_punch_success = false;
            let ret = axios.post('execWebPunch',{
                employee_id: this.employeeID,
                clocking_in_out_id: this.clocking_in_out_id,
            }).then(response => {

                if(response.data.result)
                {
                    //打刻結果をセット
                    this.stamped_info = response.data.values;
                    this.is_punch_success = true;
                }
                else
                {
                    this.is_punch_success = false;
                }
            }).catch((error)=>{
                alert("通信エラーが発生しました。ページを再度読み込みなおしてください。");
            });
            return ret;
        },
        initParams(){
            if(!this.is_multitennant){
                this.employeeID = 0;
            }
            this.error_message = null;
            this.is_authorized = false;
            this.is_punch_success = false;
            this.stamped_info = {};
        },
        //Loading画面のキャンセル
        onCancel() {
        },
        //パスワード入力部へフォーカス移動
        inputToPassword (event) {
            if(event.keyCode === 13)
            {
                this.$refs.webPunchPassword.focus();
            }
        },
        onPressEnterWebPunch(in_out_id){
            //keyDownイベントでフォーカス外して打刻関数直接呼出し（2度押し防止）
            document.activeElement.blur();
            this.webPunch(in_out_id);
        },
        activaWebPunchCodeInput(){
            if(!this.is_multitennant){
                //連打防止のため少し遅らせる
                setTimeout(()=>{
                    this.$refs.webPunchCode.focus();
                }, 500)
            }
        },
        buttonReverse(in_out_id){
            //ボタンの反転を行う
            switch(in_out_id){
                case 1:
                    this.isAttendanceWork = true;
                    break;
                case 2:
                    this.isLeavingWork = true;
                    break;
                case 3:
                    this.isGoOut = true;
                    break;
                case 4:
                    this.isReturn = true;
                    break;
            }
        },
        buttonNormal(in_out_id){
            //ボタンの反転を解除
            switch(in_out_id){
                case 1:
                    this.isAttendanceWork = false;
                    break;
                case 2:
                    this.isLeavingWork = false;
                    break;
                case 3:
                    this.isGoOut = false;
                    break;
                case 4:
                    this.isReturn = false;
                    break;
            }
        },
    },
    mounted(){
        if(typeof(this.session_data) != "undefined"){
            this.is_multitennant = this.session_data.is_multitennant;
        }
    },
    watch: {
        employee_id: {
            immediate: true,
            handler(value) {
                if(!value || value.length <= 0)
                {
                    // do nothing
                }
                else
                {
                    this.employeeID = Number(value);
                }
            }
        },
    },
}
</script>
<style module>
.c003_01_07_btnstyle_reverse{
  color: #2176bd !important;
  background-color: #fff !important;
  border: 5px solid !important;
  border-color: #2176bd !important;
  box-shadow: 0 0 0 0.2rem rgba(38, 143, 255, 0.5) !important;
}
.c003_01_08_btnstyle_reverse{
    color: #c51f1a !important;
    background-color: #fff !important;
    border: 5px solid !important;
    border-color: #c51f1a !important;
    box-shadow: 0 0 0 0.2rem rgba(225, 83, 97, 0.5) !important;
}
.go_out_and_return_reverse{
    color: #ff6600 !important;
    background-color: #fff !important;
    border: solid;
    border-color: #ff6600 !important;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25) !important;
}
</style>