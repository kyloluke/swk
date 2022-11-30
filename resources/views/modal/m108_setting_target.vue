<template>
    <div class="modal-content" id="C108-01" style="width: 1300px">
        <div class="modal-body">
            <loading :active.sync="isLoading"
            :can-cancel="true"
            :on-cancel="onCancel"
            :is-full-page="fullPage"></loading> 
            <div id="C108-01-01" class="row mb-2 ml-2">設定する基準日を選択して下さい</div>
            <div class="modal-footer row d-flex justify-content-center">
                <label id="C108-01-02" class="col-form-label">基準日</label>
                <div class="form-group ml-3 mr-5" style="width: 200px;">
                    <vuejsDatepicker id="C108-01-03" :format="datePickerFormat" :language="datePickerLanguage" minimum-view="day" class="dp-form-control" v-model="referenceDate" :disabled="selectStdflg"></vuejsDatepicker>
                </div>
                <button id="C108-01-04" type="button" class="btn w-15 ml-5" style="margin-right: 80px;" :class="selectButtonTypeClass(selectStdflg)" v-on:click="selectStdClick" :disabled="selectStdflg">基準日を選択</button>
                <button id="C108-01-05" type="button" class="btn w-15" style="margin-right: 80px" :class="cancelButtonTypeClass(selectStdflg)" v-on:click="cancelClick" :disabled="selectStdflg">キャンセル</button>
                <button id="C108-01-06" type="button" class="btn w-15" :class="changeButtonTypeClass(selectStdflg)" v-on:click="changeStdClick" :disabled="!selectStdflg">基準日を変更</button>
            </div>
            <div id="C108-01-07" class="row ml-2">注意！！　設定を反映すると、基準日以降に設定されている対象者情報は全てリセットされます。</div>

            <div v-if="selectStdflg">
                <div id="C108-01-08" class="mt-5 mb-4 ml-2">{{message}}</div>
                <div class="row mb-3">
                    <div class="col-sm-5">
                        <div class="form-group row">
                            <div class="col-sm-1"></div>
                            <div class="col-sm-10">
                                <label id="C108-01-09" class="modal-form-label" for="C108-01-10">社員番号</label>
                                <input id="C108-01-10" type="text" v-model="employeeNumberInput" class="form-control" placeholder="社員番号" value="">
                            </div>
                            <div class="col-sm-1"></div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-1"></div>
                            <div class="col-sm-10">
                                <label id="C108-01-11" class="modal-form-label" for="C108-01-12">名前</label>
                                <input id="C108-01-12" type="text" v-model="nameInput" class="form-control" placeholder="名前" value="">
                            </div>
                            <div class="col-sm-1"></div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-1"></div>
                            <div class="col-sm-10">
                                <label id="C108-01-13" class="modal-form-label" for="C108-01-14">事業所</label>
                                <select id="C108-01-14" v-model="adjectiveOfficeInput" class="form-control" value="">
                                    <option></option>
                                    <option v-for="option in office_options_list" :key="option.office_id" v-bind:value="option">{{ option.office_name }}</option>
                                </select>
                            </div>
                            <div class="col-sm-1"></div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-1"></div>
                            <div class="col-sm-10">
                                <label id="C108-01-15" class="modal-form-label" for="C108-01-14">部署</label>
                                <select id="C108-01-16" v-model="adjectiveDeptInput" class="form-control" value="">
                                    <option></option>
                                    <option v-for="option in dept_options_list" :key="option.dept_id" v-bind:title="option.dept_name" v-bind:value="option">{{ option.dept_name }}</option>
                                </select>
                            </div>
                            <div class="col-sm-1"></div>
                        </div>
                        <div class="d-flex justify-content-center mb-3">
                            <button id="C108-01-17" type="button" class="btn btn-primary w-35" v-on:click="searchClick">検索</button>
                        </div>
                        <div class="form-group" style="padding-left: 37px;">
                            <input id="C108-01-29" class="form-check-input" type="checkbox" value="" v-model="employeechk" v-on:change="employeeAllchkClick" style="transform: scale(2)">
                            <label id="C108-01-30" class="form-check-label ml-2">全て選択／選択を外す</label>
                        </div>
                        <div id="C108-01-18" style="height: 250px; overflow: scroll; white-space:nowrap; border: 1px solid rgba(0, 0, 0, 0.125);">
                            <!-- ここから該当データ分繰り返し -->
                            <div v-if="employee_list.length">
                                <div class="form-check ml-3 mt-3 mb-2" v-for="item in employee_list" :key="item.employee_code">
                                    <input id="C108-01-19" class="form-check-input" type="checkbox" v-model="item.isChecked" style="transform: scale(2)">
                                    <label id="C108-01-20-01" class="form-check-label abbreviation ml-2" style="width: 60px" v-bind:title="item.employee_code" v-html="item.employee_code"></label>
                                    <label id="C108-01-20-02" class="form-check-label abbreviation" style="width: 140px" v-bind:title="item.employee_name" v-html="item.employee_name"></label>
                                    <label id="C108-01-20-03" class="form-check-label abbreviation" style="width: 80px" v-bind:title="item.employee_post" v-html="item.employee_post"></label>
                                    <label id="C108-01-20-04" class="form-check-label abbreviation" style="width: 140px" v-bind:title="item.employee_office" v-html="item.employee_office"></label>
                                    <label id="C108-01-20-05" class="form-check-label abbreviation" v-html="item.employee_dept"></label>
                                </div>
                            </div>
                            <div v-if="!employee_list.length">　対象者がいません</div>
                            <!-- ここまで該当データ分繰り返し -->
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <button id="C108-01-21" type="button" class="btn btn-primary w-75" style="position: relative; top: 380px; left: 25px" v-on:click="addClick">追加＞</button>
                        <button id="C108-01-22" type="button" class="btn btn-danger w-75" style="position: relative; top: 430px; left: 25px" v-on:click="deleteClick">＜削除</button>
                    </div>
                    <div class="col-sm-5">
                        <div id="C108-01-23" class="mb-4 ml-2">選択済み対象者</div>
                        <div class="form-group" style="padding-left: 38px;">
                            <input id="C108-01-31" class="form-check-input" type="checkbox" value="" v-model="selected_employeechk" v-on:change="selectedEmployeeAllchkClick" style="transform: scale(2)">
                            <label id="C108-01-32" class="form-check-label ml-2">全て選択／選択を外す</label>
                        </div>
                        <div id="C108-01-24" style="height: 525px; overflow: scroll; white-space:nowrap; border: 1px solid rgba(0, 0, 0, 0.125);">
                            <!-- ここから該当データ分繰り返し -->
                            <div class="form-check ml-3 mt-3 mb-2" v-for="item in selected_employee_list" :key="item.employee_code">
                                <input id="C108-01-25" class="form-check-input" type="checkbox" v-model="item.isChecked" style="transform: scale(2)">
                                <label id="C108-01-26-01" class="form-check-label abbreviation ml-2" style="width: 60px" v-bind:title="item.employee_code" v-html="item.employee_code"></label>
                                <label id="C108-01-26-02" class="form-check-label abbreviation" style="width: 140px" v-bind:title="item.employee_name" v-html="item.employee_name"></label>
                                <label id="C108-01-26-03" class="form-check-label abbreviation" style="width: 80px" v-bind:title="item.employee_post" v-html="item.employee_post"></label>
                                <label id="C108-01-26-04" class="form-check-label abbreviation" style="width: 140px" v-bind:title="item.employee_office" v-html="item.employee_office"></label>
                                <label id="C108-01-26-05" class="form-check-label abbreviation" v-html="item.employee_dept"></label>
                            </div>
                            <!-- ここまで該当データ分繰り返し -->
                        </div>
                    </div>
                </div>
                <div class="modal-footer d-flex justify-content-center">
                    <button id="C108-01-27" type="button" class="btn btn-primary w-15" style="margin-right: 80px" v-on:click="registClick">登録</button>
                    <button id="C108-01-28" type="button" class="btn btn-danger w-15" v-on:click="cancelClick">キャンセル</button>
                </div>
            </div> <!--<div v-if="selectStdflg"> -->

        </div> <!--<div class="modal-body"> -->
    </div>
</template>

<script>
import vuejsDatepicker from 'vuejs-datepicker'
import Loading from 'vue-loading-overlay';
import 'vue-loading-overlay/dist/vue-loading.css';
export default {
    props: ['op1', 'id'],
    components: {
        vuejsDatepicker,
        "loading":Loading
    },
    data() {
        return {
            message: null,
            searchflg: false,
            selectStdflg: false,
            employee_list: [],
            selected_employee_list: [],
            op1_1: {
                buttons: [　//【暫定】メッセージ、ボタンの文言などは暫定
                    {
                        exec: ()=>{
                            //M108モーダルを閉じる
                            $('.modal-backdrop').remove();
                            $('#' + this.id).modal('hide');
                            $("#C108-01").remove();
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
            optionPreRegist: {
                buttons: [
                    {
                        exec: ()=>{
                            this.regist(); //登録処理
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
                message: '登録してよろしいですか？'
            },
            optionRegist: {
                buttons: [
                    {
                        exec: ()=>{
                            //M108モーダルを閉じる
                            $('.modal-backdrop').remove();
                            $('#' + this.id).modal('hide');
                            $("#C108-01").remove();
                        },
                        caption: "OK",
                        btnclass: "btn-success"
                    }
                ],
                message: '正常に登録されました'
            },
            optionRegistfail: {
                buttons: [
                    {
                        exec: ()=>{
                        },
                        caption: "OK",
                        btnclass: "btn-success"
                    }
                ],
                message: '登録失敗しました'
            },
            datePickerFormat: 'yyyy/MM/dd(D)',
            datePickerLanguage:{
                language: 'Japanese', 
                months: ['1月', '2月', '3月', '4月', '5月', '6月', '7月', '8月', '9月', '10月', '11月', '12月'], 
                monthsAbbr: ['1月', '2月', '3月', '4月', '5月', '6月', '7月', '8月', '9月', '10月', '11月', '12月'], 
                days: ['日', '月', '火', '水', '木', '金', '土'],
                rtl: false,
                ymd: false,
                yearSuffix: '年'
            },
            referenceDate: new Date(),
            employeeNumberInput: '',
            nameInput: '',
            adjectiveOfficeInput: [],
            adjectiveDeptInput: [],
            office_options_list: [],
            dept_options_list: [],
            officeId: 0,
            deptId: 0,
            isLoading: false,  //ローディング画面用プロパティ
            fullPage: true,    //ローディング画面用プロパティ
            employeechk: false,
            selected_employeechk: false,
        }
    },
    methods: {
        ascendingOrder(a, b) { //社員ID昇順並べ替え
            if (a.employee_id < b.employee_id) return -1;
            if (a.employee_id > b.employee_id) return 1;
            return 0;
        },
        cancelClick() { //P108-02 画面終了処理
            this.openModal('m112_common_message', '', this.op1_1); //【要対応】保存されていない入力情報がある場合に共通メッセージモーダルを開く
        },
        selectStdClick() { //P108-05 基準日を選択処理
            this.selectStdflg = true;
            axios.get('getSelectedTargetList', {
                //年月を6桁で送信
                params:{
                    'employeeId' : this.op1.employeeID,
                    'setting_target_type' : this.op1.setting_target_type,
                    'referenceDate' : Math.floor(this.dateStrToSerial(this.referenceDate)),
                }
            }).then(response => {
                if(response.data.result)
                {
                    this.selected_employee_list = [];
                    for(let i = 0; i < response.data.values.length; i++)
                    {
                        this.selected_employee_list.push({
                            'employee_id': response.data.values[i].employee_id,
                            'employee_code': response.data.values[i].employee_code,
                            'employee_name': response.data.values[i].employee_name,
                            'employee_post': response.data.values[i].post_name,
                            'employee_office': response.data.values[i].office_name,
                            'employee_dept': response.data.values[i].dept_tree,
                            'isChecked': false,
                        });
                    }
                    this.selected_employee_list.sort(this.ascendingOrder);
                }
                else
                {
                    //取得失敗
                }
            })
        },
        changeStdClick() { //P108-06 基準日を変更処理
            this.selectStdflg = false;
            this.searchflg = false;
            this.employeeNumberInput = '';
            this.nameInput = '';
            this.adjectiveOfficeInput = '';
            this.adjectiveDeptInput = '';
        },
        searchClick() { //P108-07 検索処理
            //ローディング画面表示
            this.isLoading = true;
            this.searchflg = true;
            this.deptId = this.adjectiveDeptInput.dept_id;
            this.officeId = this.adjectiveOfficeInput.office_id;
            axios.get('officeTargetList', {
                //年月を6桁で送信
                params:{
                    'deptId' : this.deptId,
                    'closeDateId' : this.op1.closeDateId,
                    'officeId' : this.officeId,
                    'nameInput' : this.nameInput,
                    'employeeNumberInput' : this.employeeNumberInput.replace(/^0+/, ''),
                    'referenceDate' : Math.floor(this.dateStrToSerial(this.referenceDate)),
                }
            }).then(response => {
                if(response.data.result)
                {
                    this.employee_list = [];
                    for(let i = 0; i < response.data.values.length; i++)
                    {
                        //代理入力者設定の場合は本人を検索リストに出さない
                        var is_add = true;
                        if(this.op1.setting_target_type == 2){
                            if(response.data.values[i].employee_id == this.op1.employeeID){
                                is_add = false;
                            }
                        }
                        
                        //選択済み対象者に既にいる社員は検索リストに出さない
                        if(is_add){
                            for(let j = 0; j < this.selected_employee_list.length; j++){
                                if(response.data.values[i].employee_id == this.selected_employee_list[j].employee_id){
                                    is_add = false;
                                    break;
                                }
                            }
                        }

                        if(is_add){
                            this.employee_list.push({
                                'employee_id': response.data.values[i].employee_id,
                                'employee_code': response.data.values[i].employee_code,
                                'employee_name': response.data.values[i].employee_name,
                                'employee_post': response.data.values[i].post_name,
                                'employee_office': response.data.values[i].office_name,
                                'employee_dept': response.data.values[i].dept_tree,
                                'isChecked': false,
                            });
                        }
                    }
                }
                else
                {
                    //取得失敗
                }
                //ローディング画面隠す
                this.isLoading = false;
            })
        },
        employeeAllchkClick() {
            for(let i = 0;i < this.employee_list.length; i++){
                   this.employee_list[i].isChecked = this.employeechk;
            }
        },
        selectedEmployeeAllchkClick() {
            for(let i = 0;i < this.selected_employee_list.length; i++){
                   this.selected_employee_list[i].isChecked = this.selected_employeechk;
            }
        },
        onCancel() {
            //Loading画面のキャンセル
        },
        addClick() { //P108-08 追加処理
            var wk_length = this.employee_list.length;
            var wk_checked = new Array();
            var wk_list = new Array();
            var j = 0;

            //チェックされた社員を検索リストから削除
            for(let i = wk_length - 1; i >= 0; i--){
                if(this.employee_list[i].isChecked){
                    this.employee_list[i].isChecked = false;
                    wk_list[j] = this.employee_list[i];
                    j++;
                    this.employee_list.splice( i, 1 );
                    wk_checked[i] = true;
                }
            }
            //検索リストから削除された社員を選択済み対象者リストに追加
            for(let i = 0; i < wk_list.length; i++){
                this.selected_employee_list.push(wk_list[wk_list.length - 1 - i]);
            }
            //employee_idで昇順並べ替え
            this.selected_employee_list.sort(this.ascendingOrder);
        },
        deleteClick() { //P108-09 削除処理
            var wk_length = this.selected_employee_list.length;
            var wk_checked = new Array();
            var wk_list = new Array();
            var j = 0;

            //チェックされた社員を選択済み対象者リストから削除
            for(let i = wk_length - 1; i >= 0; i--){
                if(this.selected_employee_list[i].isChecked){
                    this.selected_employee_list[i].isChecked = false;
                    wk_list[j] = this.selected_employee_list[i];
                    j++;
                    this.selected_employee_list.splice( i, 1 );
                    wk_checked[i] = true;
                }
            }
            //選択済み対象者リストから削除された社員を検索リストに追加。ただし、部署名が一致する場合のみ。
            for(let i = 0; i < wk_list.length; i++){
                var wk_office_names = wk_list[wk_list.length - 1 - i].employee_office;
                var wk_dept_names = wk_list[wk_list.length - 1 - i].employee_dept;
                if(((this.adjectiveOfficeInput.office_name == wk_office_names)||(this.adjectiveOfficeInput == '')) && ((this.adjectiveDeptInput.dept_name == wk_dept_names)||(this.adjectiveDeptInput == ''))){
                    if(this.employee_list.length == 0){
                        this.employee_list.push(wk_list[wk_list.length - 1 - i]);
                    }
                    else{
                        for(let j = 0; j < this.employee_list.length; j++){
                            if(this.employee_list[j].employee_id != wk_list[wk_list.length - 1 - i].employee_id){
                                this.employee_list.push(wk_list[wk_list.length - 1 - i]);
                                break;
                            }
                        }
                    }
                }
            }
            //employee_idで昇順並べ替え
            this.employee_list.sort(this.ascendingOrder);
        },
        registClick() { //P108-10 登録処理
            //登録確認
            this.openModal('m112_common_message', '', this.optionPreRegist);
        },
        regist() {
            axios.post('updateSelectedTargetList', {
                params: {
                    employeeId : this.op1.employeeID,
                    info: this.selected_employee_list, 
                    setting_target_type: this.op1.setting_target_type,
                    referenceDate : Math.floor(this.dateStrToSerial(this.referenceDate)),
                }
            }).then(response => {
                if(response.data.result)
                {
                    //正常登録お知らせ
                    this.openModal('m112_common_message', '', this.optionRegist);
                    //リスト更新
                    this.op1.callback();
                }
                else
                {
                    //登録失敗お知らせ
                    this.openModal('m112_common_message', '', this.optionRegistfail);
                }
            }).catch(error => {
                console.log(error.response);
            });
        },
    },
    mounted(){  //P108-01 初期化処理
        this.message = null;
        if(this.op1.setting_target_type == 1){
            this.message = '勤怠管理の対象者を選択し、追加及び削除を行ってください';
        }
        else{
            this.message = '代理入力の対象者を選択し、追加及び削除を行ってください';
        }
        axios.get('m110_common_search', {
            // 事業所選択フラグ送信
            params:{
                'selectOffice' : this.op1.isEnableSelectOffice,
            }
        }).then(response => {
            if(response.data.result) {
                for(let i = 0; i < response.data.office.length; i++)
                {
                    this.office_options_list.push({
                        'office_id': response.data.office[i].office_id,
                        'office_name': response.data.office[i].office_name,
                    });
                }
                var deptShortName = "";
                for(let i = 0; i < response.data.dept.length; i++)
                {
                    if(response.data.dept[i].dept_name.length > 20){
                        deptShortName = response.data.dept[i].dept_name.substring(0,20) + "...";
                    }else{
                        deptShortName = response.data.dept[i].dept_name;
                    }
                    this.dept_options_list.push({
                        'dept_id': response.data.dept[i].dept_id,
                        'dept_name': response.data.dept[i].dept_name,
                        'dept_short_name': deptShortName,
                    });
                }
            }
            else{
                // 取得失敗
            }
        });
    },
    computed:{
        selectButtonTypeClass:function(){
            return function(selectStdflg) {
                if(selectStdflg)
                {
                    return "btn-secondary";
                }
                return "btn-primary";
            }
        },
        cancelButtonTypeClass:function(){
            return function(selectStdflg) {
                if(selectStdflg)
                {
                    return "btn-secondary";
                }
                return "btn-danger";
            }
        },
        changeButtonTypeClass:function(){
            return function(selectStdflg) {
                if(selectStdflg)
                {
                    return "btn-success";
                }
                return "btn-secondary";
            }
        },
    },
};
</script>
