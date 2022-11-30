<template>
    <div>
        <div v-if="isLoading" style="position: fixed; z-index: 1000000; left:0; right: 0; top: 55%; width: 100%; text-align: center;">
            <div v-show="!isClickedCancel" style="font-size: 1.1rem;">{{progressValue}}</div>
            <div v-show="!isClickedCancel" class="btn btn-danger" style="width: 200px; height:30px; margin-top:20px; line-height:16px; font-size: 0.8rem;" @click="cancelGeneralSearch">キャンセル</div>
        </div>
        <loading :active.sync="isLoading" :can-cancel="false" :on-cancel="onCancel" :is-full-page="fullPage"></loading>
        <div class="container-fluid p-3 h-100 w-100 shadow-sm board" style="margin-top:20pt;" v-bind:disabled="session_data.is_production">
            <div style="margin-left: 20px;">
                <button class="btn btn-primary" style="font-size: 11pt; width: 100pt;" v-on:click="openM118Modal">保存条件検索</button>
                <button class="btn btn-primary" style="font-size: 11pt; width: 100pt; margin-left: 20px;" v-on:click="onClickNewSearch">新規検索</button>
            </div>
        </div>
        <div v-show="isVisibleSearchConditions && !isNewSearch" class="container-fluid p-3 h-100 w-100 shadow-sm board" style="margin-top:20pt;" v-bind:disabled="session_data.is_production">
            <div class="row mb-3">
                <div class="px-2">
                    <div class="text-left">
                        保存条件
                    </div>
                </div>
            </div>
            <div class="card shadow h-100" style="background-color:#BCD2EE;">
                <div class="card-body">
                    <div class="row" style="color:#000000;">
                        <div class="col-sm-6">
                            <span>保存条件名</span>
                            <input type="text" v-model="saveName" style="color:#000000;font-size:15pt;" disabled/>
                        </div>
                        <div class="col-sm-6" style="line-height: 38px;">
                            <span style="margin-right: 20px;">管理区分</span>
                            <input type="radio" value=0 v-model="shareClass" disabled/>共通
                            <input type="radio" value=1 v-model="shareClass" style="margin-left: 20px;" disabled/>個人
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div v-show="isVisibleSearchConditions" class="container-fluid p-3 h-100 w-100 shadow-sm board" style="margin-top:20pt;">
            <div class="row mb-3">
                <div class="px-2">
                    <div class="text-left">
                        検索単位指定
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-12">
                    <div class="card shadow h-100" style="background-color:#BCD2EE;">
                        <div class="card-body">
                            <div class="row" style="color:#000000;">
                                <div class="col-sm-1">
                                    <div>検索単位</div>
                                </div>
                                <div class="col-sm-1">
                                    <input type="radio" value=1 v-bind:disabled="is_searched_flg" v-model="unitInput">日次
                                </div>
                                <div class="col-sm-1">
                                    <input type="radio" value=2 v-bind:disabled="is_searched_flg" v-model="unitInput">月次
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div v-show="isVisibleSearchConditions" class="container-fluid p-3 h-100 w-100 shadow-sm board" style="margin-top:20pt;" v-bind:disabled="session_data.is_production">
            <div class="row mb-3">
                <div class="px-2">
                    <div class="text-left">
                        表示項目設定
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-sm-5">
                    <div class="card shadow h-100" style="background-color:#BCD2EE;">
                        <div class="card-body" style="height:250pt">
                            <div class="card-title text-left">表示項目選択リスト</div>
                            <div class="row">
                                <div class="col-sm-9">
                                    <select class="form-select" v-bind:disabled="is_searched_flg" multiple v-model="selectedDisplayListSelectable" style="width: 100%; height: 250px;">
                                        <option v-for="item in displayListSelectable" :key="item.list_index" v-show="!item.is_selected" v-bind:value="item.list_index">{{displayListName(item)}}</option>
                                    </select>
                                </div>
                                <div class="col-sm-3"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-2">
                    <div class="form-group row">
                        <div class="col-sm-1"></div>
                        <div class="col-sm-10">
                            <button v-bind:disabled="is_searched_flg" type="button" class="btn btn-primary w-75" style="" @click="addList">追加＞</button>
                            <button v-bind:disabled="is_searched_flg" type="button" class="btn btn-danger w-75" style="margin-top:20pt;" @click="removeList">＜削除</button>
                            <button v-bind:disabled="is_searched_flg" type="button" class="btn btn-primary w-75" style="margin-top:20pt;" @click="addListAll">全追加＞</button>
                            <button v-bind:disabled="is_searched_flg" type="button" class="btn btn-danger w-75" style="margin-top:20pt;" @click="removeListAll">＜全削除</button>
                        </div>
                        <div class="col-sm-1"></div>
                    </div>
                </div>
                <div class="col-sm-5">
                    <div class="card shadow h-100" style="background-color:#BCD2EE;">
                        <div class="card-body">
                            <div class="card-title text-left">選択済みリスト</div>
                            <div class="row">     
                                <div class="col-sm-9">
                                    <select class="form-select" v-bind:disabled="is_searched_flg" multiple v-model="selectedDisplayListSelected" style="width: 100%; height: 250px;">
                                        <option v-for="item in displayListSelected" :key="item.list_index" v-bind:value="item.list_index">{{displayListName(item.list_item)}}</option>
                                    </select>
                                </div>
                                <div class="col-sm-3">
                                    <div class="text-left">並び替え</div>
                                    <button type="button"  @click="itemMoveTop" class="btn btn-primary w-75" style="margin-top:10pt;">先頭</button>
                                    <button type="button" @click="itemMoveUp" class="btn btn-primary w-75" style="margin-top:10pt;">上</button>
                                    <button type="button"  @click="itemMoveDown" class="btn btn-primary w-75" style="margin-top:10pt;">下</button>
                                    <button type="button" @click="itemMoveBottom" class="btn btn-primary w-75" style="margin-top:10pt;">最後</button>
                                </div>
                               
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div v-show="isVisibleSearchConditions" class="container-fluid p-3 h-100 w-100 shadow-sm board" style="margin-top:20pt;">
            <div class="row mb-3">
                <div class="px-2">
                    <div class="text-left">
                        検索条件設定
                    </div>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-12">
                    <div class="card shadow h-100" style="background-color:#BCD2EE;">
                        <div class="card-body">
                            <div class="card-title text-left">検索対象</div>
                            <div class="row" style="color:#000000;">
                                <div class="col-sm-1">
                                    <div>対象期間</div>
                                </div>
                                <div class="col-sm-3">
                                    <input v-if="unitInput == 1" type="date" class="form-control" v-bind:disabled="is_searched_flg" v-model="selectedStartDate" min="1901-01-01"/>
                                    <vuejsDatepicker v-else :format="datePickerFormat" :language="datePickerLanguage" minimum-view="month" :disabled-dates="datePickerDisabledDates" class="dp-form-control" v-model="selectedStartMonth"></vuejsDatepicker>
                                </div>
                                <div class="col-sm-1">
                                    <div>～</div>
                                </div>
                                <div class="col-sm-3">
                                    <input v-if="unitInput == 1" type="date" class="form-control" v-bind:disabled="is_searched_flg" v-model="selectedEndDate" min="1901-01-01"/>
                                    <vuejsDatepicker v-else :format="datePickerFormat" :language="datePickerLanguage" minimum-view="month" :disabled-dates="datePickerDisabledDates" class="dp-form-control" v-model="selectedEndMonth"></vuejsDatepicker>
                                </div>
                            </div>
                            <div class="row " style="color:#000000;margin-top:20pt;"> 
                                <div class="col-sm-1">
                                    <div>社員番号</div>
                                </div>
                                <div class="col-sm-2">
                                    <input type="text" class="form-control" v-bind:disabled="is_searched_flg" v-model="selectedEmployeeCode" placeholder="未指定">
                                </div>
                                <div class="col-sm-1">
                                    <div>事業所</div>
                                </div>
                                <div class="col-sm-2">
                                    <select v-model="selectedOfficeID" v-bind:disabled="is_office_page || is_searched_flg" class="form-control">
                                        <option value=0>全社</option>
                                        <option v-for="option in office_options_list" :key="option.office_id" v-bind:value="option.office_id">{{ option.office_name }}</option>
                                    </select>
                                </div>
                                <!-- 限定実装のため非表示
                                <div class="col-sm-1">
                                    <div>状態区分</div>
                                </div>
                                <div class="col-sm-2">
                                    <select v-if="unitInput == 1" v-model="approvalStateInput" v-bind:disabled="is_searched_flg" class="form-control">
                                        <option></option>
                                        <option v-for="option in approval_state_options_list" :key="option.approval_state_id" v-bind:value="option.approval_state_id">{{ option.approval_state_name }}</option>
                                    </select>
                                    <select v-else v-model="closeStateInput" v-bind:disabled="is_searched_flg" class="form-control">
                                        <option value = 0></option>
                                        <option v-for="option in close_state_options_list" :key="option.close_state_id" v-bind:value="option.close_state_id">{{ option.close_state_name }}</option>
                                    </select>
                                </div>
                                <div class="col-sm-2">
                                    <select v-if="(unitInput == 1 && approvalStateInput != 0) || (unitInput == 0 && closeStateInput != 0)" v-model="stateInput" v-bind:disabled="is_searched_flg" class="form-control">
                                        <option value = 1>等値</option>
                                        <option value = 2>以前</option>
                                        <option value = 3>以降</option>    
                                    </select>
                                </div>
                                -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- 用途不定のため、暫定非表示
            <div class="row mb-3">
                <div class="col-12">
                    <div class="card shadow h-100" style="background-color:#BCD2EE;">
                        <div class="card-body">
                            <div class="card-title text-left">出力設定</div>
                            <div class="row" style="color:#000000;">  
                                <div class="col-sm-2">
                                    <div>集計(時間/回数)</div>
                                </div>
                                <div class="col-sm-1">
                                    <input v-bind:disabled="is_searched_flg" type="radio"  value=0 v-model="aggregateFlag">する
                                </div>
                                <div class="col-sm-1">
                                    <input v-bind:disabled="is_searched_flg" type="radio"  value=1 v-model="aggregateFlag">しない
                                </div>
                                <div class="col-sm-2">
                                    <div>時間出力方法</div>
                                </div>
                                <div class="col-sm-1">
                                    <input v-bind:disabled="is_searched_flg" type="radio" value=0 v-model="timeUnitInput">時：分
                                </div>
                                <div class="col-sm-1">
                                    <input v-bind:disabled="is_searched_flg" type="radio" value=1 v-model="timeUnitInput">時間
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            -->
            <!--
            <div class="row mb-3">
                <div class="col-12">
                    <div class="card shadow h-100" style="background-color:#BCD2EE;">
                        <div class="card-body">
                            <div class="card-title text-left">詳細検索条件設定</div>
                            <div class="row" style="color:#000000;">      

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            -->
            <div class="row mb-3">
                <div class="col-12">
                    <button v-bind:disabled="is_searched_flg" class="btn btn-success" style="font-size: 11pt; width: 300pt;" v-on:click="clickSearchButton" >検索</button>
                    <button class="btn btn-primary" style="font-size: 11pt; width: 115pt; margin-left: 20px;" v-on:click="openM117Modal">検索条件保存</button>
                </div>
                <!-- 未実装のため非表示
                <div class="col-2">
                    <button class="btn btn-primary" style="font-size:11pt;width:120pt">EXCELダウンロード</button>
                </div>
                -->
            </div>

        </div>
        <div v-if="is_searched_flg && isVisibleSearchConditions" class="container-fluid p-3 h-100 w-100 shadow-sm board" style="margin-top:20pt;">
            <div class="row mb-3">
                <div class="px-2">
                    <div class="text-left">
                        検索結果
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-12">
                    <div class="card shadow h-100" style="background-color:#BCD2EE;">
                        <div class="card-body">
                            <div class="row" style="color:#000000;height:250pt">      
                                <div class="col-12" style="height:250pt;overflow: auto;white-space:nowrap; border: 1px solid rgba(0, 0, 0, 0.125);">
                                    <table class="table-general" style="font-size:12pt;">
                                        <thead>
                                            <tr>
                                                <th v-for="header in dataHeader" :key="header.colIndex">{{header.value}}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="content in dataContents" :key="content.rowIndex">
                                                <td v-for="item in content.contentLine" :key="item.colIndex">{{item.value}}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="row" style="color:#000000;margin-top:20pt">
                                <div class="col-sm-8">
                                </div>
                                <div class="col-sm-2">
                                    <VueJsonToCsv :json-data="csvBuff" :csv-title="csvFileName" :labels="labelBuff">
                                    <button class="btn btn-primary" style="font-size:11pt;width:115pt" v-on:click="downloadCsv()">CSVダウンロード</button>
                                    </VueJsonToCsv>
                                </div>
                                <div class="col-sm-2">
                                    <button class="btn btn-primary" style="font-size:11pt;width:115pt" v-on:click="closeSearchedView">検索結果を閉じる</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</template>

<script>
import vuejsDatepicker from 'vuejs-datepicker'
import Loading from 'vue-loading-overlay';
import 'vue-loading-overlay/dist/vue-loading.css';
import VueJsonToCsv from 'vue-json-to-csv'

export default {
    props: {
        session_data: Object,
        is_office_page: Boolean,

    },
    components: {
        "loading":Loading,
        vuejsDatepicker,
        VueJsonToCsv,
    },
    data() {
        return {
            modalOption_112: {
                message: '',
                buttons:[
                    {
                        exec : ()=>{
                            $('body').removeClass('modal-open');
                            this.cleanModal();
                            $('.modal-backdrop').remove();
                        },
                        caption : "OK",  //'OK','はい','いいえ','キャンセル','再試行','閉じる'
                        btnclass : "btn-success"
                    }],
            },
            modalOption_112_ng: {
                message: '',
                buttons:[
                    {
                        exec : ()=>{
                            $('body').removeClass('modal-open');
                            this.cleanModal();
                            $('.modal-backdrop').remove();
                        },
                        caption : "OK",  //'OK','はい','いいえ','キャンセル','再試行','閉じる'
                        btnclass : "btn-danger"
                    }],
            },
            selectedStartDate: this.serialToDateStr(this.todaySerial(), "YYYY-MM-DD"),
            selectedEndDate: this.serialToDateStr(this.todaySerial(), "YYYY-MM-DD"),
            selectedStartMonth: new Date(),
            selectedEndMonth: new Date(),
            datePickerFormat: 'yyyy/MM',
            datePickerLanguage:{
                language: 'Japanese', 
                months: ['1月', '2月', '3月', '4月', '5月', '6月', '7月', '8月', '9月', '10月', '11月', '12月'], 
                monthsAbbr: ['1月', '2月', '3月', '4月', '5月', '6月', '7月', '8月', '9月', '10月', '11月', '12月'], 
                days: ['日', '月', '火', '水', '木', '金', '土'],
                rtl: false, 
                ymd: false,
                yearSuffix: '年'
            },
            datePickerDisabledDates: {
                from: new Date(),
            },
            aggregateFlag: 0,
            timeUnitInput: 0,
            office_options_list: [],
            approval_state_options_list: [],
            close_state_options_list: [],
            selectedOfficeID: 0,
            approvalStateInput: 0,
            closeStateInput: 0,
            unitInput: 1,
            shareClass: 0,
            stateInput: 1,
            selectedEmployeeCode: "",
            is_searched_flg: false,
            pageNumber: 1,
            isLoading: false,  //ローディング画面用プロパティ
            fullPage: true,    //ローディング画面用プロパティ
            selectedDisplayListSelectable: [],
            displayListSelectable: {},
            selectedDisplayListSelected: [],
            displayListSelected: [],
            defineList: {},
            jobID: 0,
            dataHeader: [],
            dataContents: [],
            csvBuff: [],
            csvBuffRow: [],
            labelBuff: {},
            csvFileName: '',
            defaultDate: new Date(),
            progress: 0,
            progressTotal: 0,
            isVisibleSearchConditions: false,
            isNewSearch: true,
            saveName: "",
            isClickedCancel: false,
        };
    },
    methods: {
        //初期化メソッド（親から呼ばれる）
        initialize()
        {
        },
        //新規検索用に初期化
        initializeNewSearch()
        {

            this.isVisibleSearchConditions = true;
            //新規検索フラグON
            this.isNewSearch = true;
            //リストクリア
            this.removeListAll();
            //月次と日次の選択初期化
            this.selectedStartDate = this.serialToDateStr(this.todaySerial(), "YYYY-MM-DD");
            this.selectedEndDate = this.serialToDateStr(this.todaySerial(), "YYYY-MM-DD");
            this.selectedStartMonth = new Date();
            this.selectedEndMonth = new Date();
            this.unitInput = 1;
            //見えていないけれど管理区分と名前も初期化
            this.shareClass = 0;
            this.saveName = "";
        },
        //検索条件一覧表示
        openM118Modal()
        {
            //モーダルを開く
            this.openModal('m118_select_general_search', "", {
                onSelectTarget: this.onSelectSavedList,
                onDeleteTarget: this.onDeleteGeneralSearch,
                isSelectedCondition: this.isVisibleSearchConditions,
            });
        },
        //検索条件を保存
        openM117Modal()
        {
            //選択ゼロの場合はエラーモーダル出して終了
            if(this.getTargetList().length == 0)
            {
                this.modalOption_112_ng.message = "検索条件が未選択です。保存する条件を指定してください。";
                this.openModal("m112_common_message", "", this.modalOption_112_ng);
                return;
            }
            //モーダルを開く
            this.openModal("m117_save_general_search", "", {
                targetList: this.getTargetList(),
                saveName: this.saveName,
                unitType: this.unitInput,
                onSaveList: this.onSaveList,
                shareClass: this.shareClass,
            });
        },
        //新規検索
        onClickNewSearch()
        {
            //下部隠れている場合は表示
            if(!this.isVisibleSearchConditions)
            {
                this.initializeNewSearch();
            }
            else
            {
                //隠れていない場合はコーション表示して条件削除
                this.openModal("m112_common_message", "", {
                    message: '現在指定している条件がクリアされますがよろしいですか？',
                    buttons:[
                        {
                            exec : ()=>{
                                this.initializeNewSearch();
                                $('body').removeClass('modal-open');
                                this.cleanModal();
                                $('.modal-backdrop').remove();
                            },
                            caption : "OK",  //'OK','はい','いいえ','キャンセル','再試行','閉じる'
                            btnclass : "btn-success"
                        },
                        {
                            exec : ()=>{
                                //キャンセルは何もしない
                                $('body').removeClass('modal-open');
                                this.cleanModal();
                                $('.modal-backdrop').remove();
                            },
                            caption : "キャンセル",  //'OK','はい','いいえ','キャンセル','再試行','閉じる'
                            btnclass : "btn-danger"
                        }
                    ],
                });
            }
        },
        //保存した後のコールバック
        onSaveList(save_name, share_class){
            this.saveName = save_name;
            this.shareClass = share_class;
            this.isNewSearch = false;
        },
        //モーダルから選択された場合のコールバック
        async onSelectSavedList(save_id){
            //ローディング出す
            this.isLoading = true;
            this.isClickedCancel = true;
            axios.get('getGeneralSearchSave',{
                params:{
                    'saveID' : save_id,
                }
            }).then(async (response) =>{
                if(response.data.result)
                {
                    const save_info = response.data.values.general_search_save;
                    const save_params = response.data.values.general_search_save_params;
                    //保存名表示
                    this.saveName = save_info.general_search_save_name;
                    //日次・月次の選択変更
                    this.unitInput = save_info.unit_type;
                    //管理区分
                    this.shareClass = save_info.share_class;

                    //リスト再生成されるまでsleep(とりあえず500ms)
                    await this.sleep(500);

                    if(this.unitInput == 1)
                    {
                        //1が日次
                        this.selectedStartDate = this.serialToDateStr(this.todaySerial(), "YYYY-MM-DD");
                        this.selectedEndDate = this.serialToDateStr(this.todaySerial(), "YYYY-MM-DD");
                    }
                    else
                    {
                        //2は月次
                        this.selectedStartMonth = new Date();
                        this.selectedEndMonth = new Date();
                    }
                    //リストクリア
                    this.removeListAll();
                    let item_list = [];
                    //リストへ追加
                    for(let i = 0; i < save_params.length; i++)
                    {
                        let item = this.displayListSelectable.find(elm => {return elm.kind_index == save_params[i].kind && elm.item_index == save_params[i].columns && (save_params[i].type == null || elm.type == save_params[i].type)});
                        if(item == null)
                        {
                            //リストのエラー
                            //無効なアイテムが選択されました。この保存条件は使用できません。
                            this.modalOption_112_ng.message = "無効なアイテムが選択されました。この保存条件は使用できません。";
                            this.openModal("m112_common_message", "", this.modalOption_112_ng);
                            break;
                        }
                        item_list.push({
                            list_index: item.list_index,
                            detail_no: save_params[i].detail_no,
                        });
                    }
                    //detail_no順にソート
                    item_list.sort((a, b) =>{ 
                        if(a.detail_no < b.detail_no) return -1;
                        if(a.detail_no < b.detail_no) return 1;
                        return 0;
                    });
                    //一つずつaddList
                    for(let i = 0; i < item_list.length; i++)
                    {
                        this.selectedDisplayListSelectable.push(item_list[i].list_index);
                        this.addList();
                    }
                    //新規検索フラグOFF
                    this.isNewSearch = false;
                    //検索条件表示
                    this.isVisibleSearchConditions = true;
                }
                else
                {
                    //エラー処理
                }
                this.isClickedCancel = false;
                this.isLoading = false;
            }).catch(error =>{
                this.isClickedCancel = false;
                this.isLoading = false;
            });
            return null;
        },
        async onDeleteGeneralSearch(delete_id){
            this.isLoading = true;
            this.isClickedCancel = true;
            axios.post('deleteGeneralSearchCondition',{
                delete_id : delete_id,
            }).then(async (response) => {
                
                if(response.data.result){
                    // reset search if general search delete is current search loaded.
                    if(response.data.values.delete_name == this.saveName){
                        this.initializeNewSearch();
                    }

                    this.openModal("m112_common_message", "", {
                        message: response.data.values.message,
                        buttons:[
                            {
                                exec : ()=>{
                                    $('body').removeClass('modal-open');
                                    this.cleanModal();
                                    $('.modal-backdrop').remove();
                                },
                                caption : "OK",
                                btnclass : "btn-success"
                            },
                        ]
                    });
                }else{
                    this.openModal("m112_common_message", "", {
                        message: response.data.values.message,
                        buttons:[
                            {
                                exec : ()=>{
                                    $('body').removeClass('modal-open');
                                    this.cleanModal();
                                    $('.modal-backdrop').remove();
                                },
                                caption : "OK",
                                btnclass : "btn-danger"
                            },
                        ]
                    });
                }
                this.isClickedCancel = false;
                this.isLoading = false;
            }).catch(error =>{
                this.isClickedCancel = false;
                this.isLoading = false;
            });
        },
        onCancel() {
            //Loading画面のキャンセル
        },
        closeSearchedView() {
            this.is_searched_flg = false;
        },
        validate(){
            //ToDo
            //リストを選択済みか

            //対象期間の前後指定が正しいか

            return true;
            
        },
        //選択可能リスト
        setDisplayListSelectable: function(){
            this.displayListSelectable = [];
            let list_index = 0;
            //マスタデータから取得して表示、ただし選択済みは除外
            for(let i in this.defineList)
            {
                if(this.defineList[i].unitType != 0 && this.defineList[i].unitType != this.unitInput)
                {
                    continue;
                }
                let totalTypeArray = [];
                if(this.defineList[i].totalTimeSerial)
                {
                    totalTypeArray.push({
                        type: "totalTimeSerial",
                        displayName: "[時間]",
                    });
                }
                if(this.defineList[i].totalCount)
                {
                    totalTypeArray.push({
                        type: "totalCount",
                        displayName: "[回数]",
                    });
                }
                if(totalTypeArray.length == 0)
                {
                    totalTypeArray.push({
                        type: null,
                        displayName: "",
                    });
                }
                for(let j in totalTypeArray)
                {
                    for(let k in this.defineList[i].columns)
                    {
                        this.displayListSelectable.push({
                            list_index : list_index,
                            kind_index: i,
                            kind_name: this.defineList[i].displayName,
                            item_index: k,
                            item_name: this.defineList[i].columns[k].displayName,
                            total_name: totalTypeArray[j].displayName,
                            convert: totalTypeArray[j].type == null ? this.convertMethod(this.defineList[i].columns[k].type) : this.convertMethod(totalTypeArray[j].type),
                            total: totalTypeArray[j].type,
                            type: totalTypeArray[j].type == null ? this.defineList[i].columns[k].type : totalTypeArray[j].type,
                            is_selected: false,
                        });
                        list_index++;
                    }
                }
            }
        },
        //左リストで選択中のものを選択中に変更、右リストの一番下へ追加
        addList(){
            for(let i in this.selectedDisplayListSelectable)
            {
                //選択済みに変更
                this.displayListSelectable[this.selectedDisplayListSelectable[i]].is_selected = true;
                //選択済みリストに追加
                let addIndex = this.displayListSelected.length;
                this.displayListSelected.push({
                    list_index: addIndex,
                    list_item: this.displayListSelectable[this.selectedDisplayListSelectable[i]],
                    org_list_index: this.displayListSelectable[this.selectedDisplayListSelectable[i]].list_index,
                });
            }
            this.selectedDisplayListSelectable = [];
        },
        //右のリストから削除、左のリストの元の場所へ表示
        removeList(){
            let target_org_list_index = [];
            for(let i in this.selectedDisplayListSelected)
            {
                //未選択に変更
                this.displayListSelectable[this.displayListSelected[this.selectedDisplayListSelected[i]].list_item.list_index].is_selected = false;
                target_org_list_index.push(this.displayListSelectable[this.displayListSelected[this.selectedDisplayListSelected[i]].list_item.list_index].list_index);
            }
            for(let i in target_org_list_index)
            {
                //要素番号取得（削除するたびにindexは変わるため、org_list_indexで検索
                let target_index = this.displayListSelected.findIndex((elm)=>{return target_org_list_index[i] == elm.org_list_index});
                //削除
                this.displayListSelected.splice(target_index, 1);
            }
            //list_indexを振り直し
            for(let i = 0; i < this.displayListSelected.length; i++)
            {
                this.displayListSelected[i].list_index = i;
            }
            this.selectedDisplayListSelected = [];
        },
        //右のリストから全削除
        removeListAll(){
            this.displayListSelected = [];
            this.selectedDisplayListSelected = [];
            for(let i in this.displayListSelectable)
            {
                this.displayListSelectable[i].is_selected = false;
            }
        },
        //右のリストへ全挿入
        addListAll(){
            this.selectedDisplayListSelected = [];
            this.selectedDisplayListSelectable = [];
            for(let i in this.displayListSelectable)
            {
                if(!this.displayListSelectable[i].is_selected)
                {
                    let addIndex = this.displayListSelected.length;
                    this.displayListSelected.push({
                        list_index: addIndex,
                        list_item: this.displayListSelectable[i],
                        org_list_index: this.displayListSelectable[i].list_index,
                    });
                    this.displayListSelectable[i].is_selected = true;
                }
            }
        },

        itemMoveUp ()  {
            if(this.selectedDisplayListSelected.length > 0 && this.selectedDisplayListSelected[0] != 0){
                const selectedClone = this.selectedDisplayListSelected;
                this.selectedDisplayListSelected = [];
                selectedClone.forEach((value, i)=>{
                    var newList = this.displayListSelected;
                    let el = newList[value];

                    // reset list_index = current selected index
                    newList[value - 1].list_index = value;
                    newList[value] = newList[value - 1];
                    el.list_index = value - 1;
                    newList[value - 1] = el;
                    
                    this.displayListSelected = newList;
                    this.selectedDisplayListSelected.push(value - 1);
                });
            }

        },

        itemMoveTop(){
             if(this.selectedDisplayListSelected.length > 0 && this.selectedDisplayListSelected[0] != 0){
                const selectedClone = this.selectedDisplayListSelected;
                this.selectedDisplayListSelected = [];
                selectedClone.forEach((value, i)=>{
                    var newList = this.displayListSelected;
                    let el = newList[value];
                    newList.splice(value, 1);
                    newList.splice(i, 0, el)
                    this.displayListSelected = newList;
                    this.selectedDisplayListSelected.push(i);
                });

                // reset list_index all item list
                 for(let i in this.displayListSelected){
                    this.displayListSelected[i].list_index = parseInt(i); 
                }
            }

        },

        itemMoveDown ()  {
            if(this.selectedDisplayListSelected.length > 0 && this.selectedDisplayListSelected.slice(-1)[0] != this.displayListSelected.length - 1){
                var cloneSelected = this.selectedDisplayListSelected;
                const selectedClone = cloneSelected.slice().reverse();
                this.selectedDisplayListSelected = [];
                selectedClone.forEach((value, i)=>{
                    var newList = this.displayListSelected;
                    let el = newList[value];

                    // reset list_index = current selected index
                    newList[value + 1].list_index = value;
                    newList[value] = newList[value + 1];
                    el.list_index = value + 1;
                    newList[value + 1] = el;
                    this.displayListSelected = newList;
                    this.selectedDisplayListSelected.unshift(value + 1);
                });
            }

        },
        itemMoveBottom(){
            if(this.selectedDisplayListSelected.length > 0 && this.selectedDisplayListSelected.slice(-1)[0] != this.displayListSelected.length - 1){
               var cloneSelected = this.selectedDisplayListSelected;
                const selectedClone = cloneSelected.slice().reverse();
                const selectedLength = this.displayListSelected.length;

                this.selectedDisplayListSelected = [];
                selectedClone.forEach((value, i)=>{
                    var newList = this.displayListSelected;
                    let el = newList[value];
                    newList.splice(selectedLength - i, 0, el)
                    newList.splice(value, 1);
                    this.displayListSelected = newList;
                    this.selectedDisplayListSelected.unshift(selectedLength - i - 1);
                });

                // reset list_index all item list
                for(let i in this.displayListSelected){
                    this.displayListSelected[i].list_index = parseInt(i); 
                }

            }
        },
        //検索ボタンクリック
        clickSearchButton(){
            let targetList = this.getTargetList();
            if(targetList.length == 0)
            {
                //検索項目が未選択
                this.modalOption_112_ng.message = "検索項目を選択してください";
                this.openModal("m112_common_message", "", this.modalOption_112_ng);
                return;
            }
            //条件をconditionsへまとめる
            let start_date = 0;
            let end_date = 0;
            if(this.unitInput == 1)
            {
                start_date = this.dateStrToSerial(this.selectedStartDate);
                end_date = this.dateStrToSerial(this.selectedEndDate);
            }
            else
            {
                start_date = this.serialToDateStr(this.dateStrToSerial(this.selectedStartMonth), "YYYYMM");
                end_date = this.serialToDateStr(this.dateStrToSerial(this.selectedEndMonth), "YYYYMM");
            }
            if(start_date == null || end_date == null)
            {
                //期間入力が不正
                this.modalOption_112_ng.message = "期間の開始・終了を正しく入力してください";
                this.openModal("m112_common_message", "", this.modalOption_112_ng);
                return;
            }
            let employee_code = this.selectedEmployeeCode == "" ? null : this.selectedEmployeeCode;
            let office_id = this.selectedOfficeID;
            let is_office_page = this.is_office_page;
            let conditions = {
                start_date: start_date,
                end_date: end_date,
                employee_code: employee_code,
                office_id: office_id,
                unit_type: this.unitInput,
                is_office_page: is_office_page,
            };
            if(employee_code == null)
            {
                this.openModal("m112_common_message", "", {
                    message: '事業所全体や全社検索は時間がかかりますがよろしいですか？',
                    buttons:[
                        {
                            exec : ()=>{
                                this.execGeneralSearch(targetList, conditions);
                                $('body').removeClass('modal-open');
                                this.cleanModal();
                                $('.modal-backdrop').remove();
                            },
                            caption : "OK",  //'OK','はい','いいえ','キャンセル','再試行','閉じる'
                            btnclass : "btn-success"
                        },
                        {
                            exec : ()=>{
                                $('body').removeClass('modal-open');
                                this.cleanModal();
                                $('.modal-backdrop').remove();
                            },
                            caption : "キャンセル",  //'OK','はい','いいえ','キャンセル','再試行','閉じる'
                            btnclass : "btn-danger"
                        }
                        ],
                });
            }
            else
            {
                this.execGeneralSearch(targetList, conditions);
            }
        },
        //リクエスト用に選択結果をtargetListにまとめる
        getTargetList()
        {
            let targetList = [];
            
            //リストをdefineListの形式に合わせるように修正
            for(let i in this.displayListSelected)
            {
                let targetIndex = targetList.findIndex((elm) => elm.kind == this.displayListSelected[i].list_item.kind_index);
                if(targetIndex < 0)
                {
                    targetList.push({
                        kind: this.displayListSelected[i].list_item.kind_index,
                        columns: [this.displayListSelected[i].list_item.item_index],
                        detail_no: [i],
                        name: [this.displayListName(this.displayListSelected[i].list_item)],
                        type: [this.displayListSelected[i].list_item.type],
                    });
                }
                else
                {
                    targetList[targetIndex].columns.push(this.displayListSelected[i].list_item.item_index);
                    targetList[targetIndex].detail_no.push(i);
                    targetList[targetIndex].name.push(this.displayListName(this.displayListSelected[i].list_item));
                    targetList[targetIndex].type.push(this.displayListSelected[i].list_item.type);
                }
            }
            return targetList;
        },
        //検索実行の本体
        execGeneralSearch(targetList, conditions)
        {
            this.isLoading = true;
            //進捗リセット
            this.progress = 0;
            axios.post('execGeneralSearchByDefineList', {
                targetList: targetList,
                conditions: conditions,
            }).then(response => {
                if(response.data.result)
                {
                    this.jobID = response.data.values.job_id;
                    this.progressTotal = response.data.values.progress_total;
                    this.startCheckJobState(this.jobID);
                }
                else
                {
                    this.modalOption_112.message = response.data.values.message;
                    this.openModal("m112_common_message", "", this.modalOption_112);
                    this.isLoading = false;
                }
            })
            .catch(error => {
                //何らかのエラー
                this.isLoading = false;
                this.jobID = 0;
            });
        },
        //ジョブの状態確認開始（タイムアウトないので注意）
        async startCheckJobState(jobID){
            let isContinue = true;
            let message = "";
            while(isContinue)
            {
                await this.sleep(1000);
                let state = await this.getJobState(jobID);
                switch(state.state)
                {
                    case 0:
                    case 1:
                        //継続中
                        //進捗取得
                        this.progress = state.progress
                        break;
                    case 2:
                        isContinue = false;
                        this.getResult(jobID);
                        break;
                    case 3:
                        //キャンセル
                        isContinue = false;
                        message = "処理がキャンセルされました";
                        this.modalOption_112.message = message;
                        this.openModal("m112_common_message", "", this.modalOption_112);
                        this.isLoading = false;
                        break;
                    case 5:
                        //コンバート完了
                        //終了
                        isContinue = false;
                        this.getResult(jobID);   
                    case 9:
                        //異常終了
                        isContinue = false;
                        message = "処理が異常終了しました";
                        this.modalOption_112.message = message;
                        this.openModal("m112_common_message", "", this.modalOption_112);
                        this.isLoading = false;
                        break;
                }
            }
            this.jobID = 0;
        },
        //定周期で呼ばれる、ジョブの状態確認
        async getJobState(jobID)
        {
            return new Promise((resolve, reject) =>{
                axios.get('generalSearchCheckState',{
                    params:{
                        'jobID' : jobID,
                    }
                }).then(response => {
                    if(response.data.result)
                    {
                        resolve(response.data.values.state)
                    }
                    else
                    {
                        reject('error');
                    }
                });
            });
        },
        //検索結果をダウンロード※CSVダウンロードではない
        async getResult(jobID){
            //件数によって分岐
            if(1000 <= this.progressTotal)
            {
                //1000件以上はダウンロードのみ
                let targetList = await this.base64Encode(JSON.stringify(this.getTargetList()));
                axios.post('downloadByStream',{
                    'jobID': jobID,
                    'targetList': targetList,
                }).then(response => {
                    if(true)
                    {
                        
                        //モーダル表示
                        this.openModal("m112_common_message", "", {
                            message: '検索が完了しました。ダウンロードを行いますか？※ダウンロードを行わない場合、結果は破棄されます。',
                            buttons:[
                                {
                                    exec : ()=>{
                                        let inputDate = Number(this.serialToDateStr(this.dateStrToSerial(this.defaultDate), "YYYYMMDD"));
                                        let suff = this.saveName == "" ? "" : this.saveName + "_";
                                        this.csvFileName = "汎用検索_" + suff + inputDate;
                                        const bom  = new Uint8Array([0xEF, 0xBB, 0xBF]);
                                        const blob = new Blob([bom, response.data], {type: "text/csv"});
                                        const url = URL.createObjectURL(blob);
                                        var link = document.createElement('a');
                                        link.download = this.csvFileName;
                                        link.href = url;
                                        link.click();
                                        link.remove();
                                        URL.revokeObjectURL(url);
                                        $('body').removeClass('modal-open');
                                        this.cleanModal();
                                        $('.modal-backdrop').remove();
                                    },
                                    caption : "ダウンロード",
                                    btnclass : "btn-success"
                                },
                                {
                                    exec : ()=>{
                                        $('body').removeClass('modal-open');
                                        this.cleanModal();
                                        $('.modal-backdrop').remove();
                                    },
                                    caption : "キャンセル",  //'OK','はい','いいえ','キャンセル','再試行','閉じる'
                                    btnclass : "btn-danger"
                                },
                            ],
                        });
                        this.isLoading = false;
                    }
                }).catch(error => {
                    this.isLoading = false;
                });
                return;
                axios.post('convertDownloadFile',{
                    'jobID': jobID,
                    //'targetList': targetList,
                }).then(response => {
                    if(response.data.result)
                    {
                        //モーダル表示
                        this.openModal("m112_common_message", "", {
                            message: '検索が完了しました。ダウンロードを行いますか？※ダウンロードを行わない場合、結果は破棄されます。',
                            buttons:[
                                {
                                    exec : ()=>{
                                        let inputDate = Number(this.serialToDateStr(this.dateStrToSerial(this.defaultDate), "YYYYMMDD"));
                                        let suff = this.saveName == "" ? "" : this.saveName + "_";
                                        this.csvFileName = "汎用検索_" + suff + inputDate;
                                        var link = document.createElement('a');
                                        link.download = this.csvFileName;
                                        link.href = response.data.values.filePath;
                                        link.click();
                                        link.remove();
                                        $('body').removeClass('modal-open');
                                        this.cleanModal();
                                        $('.modal-backdrop').remove();
                                    },
                                    caption : "ダウンロード",
                                    btnclass : "btn-success"
                                },
                                {
                                    exec : ()=>{
                                        $('body').removeClass('modal-open');
                                        this.cleanModal();
                                        $('.modal-backdrop').remove();
                                    },
                                    caption : "キャンセル",  //'OK','はい','いいえ','キャンセル','再試行','閉じる'
                                    btnclass : "btn-danger"
                                },
                            ],
                        });
                        this.isLoading = false;
                    }
                }).catch(error => {
                    this.isLoading = false;
                });
            }
            else
            {
                //1000件未満は画面に表示
                axios.get('generalSearchDownloadResult',{
                    params:{
                        'jobID' : jobID,
                    }
                }).then(response => {
                    if(response.data.result)
                    {
                        let downloadData = response.data.values.content;
                        //ダウンロードデータの配列を選択済みリストに合わせて２次元連想配列に保持
                        //ラベル作成
                        this.dataHeader = [];
                        for(let i in this.displayListSelected)
                        {
                            this.dataHeader.push({
                                colIndex: this.dataHeader.length,
                                value: this.displayListName(this.displayListSelected[i].list_item),
                            });
                        }
                        //コンテンツ作成
                        this.dataContents = [];
                        //employeeループ
                        for(let i in downloadData)
                        {
                            //日毎ループ
                            for(let j in downloadData[i])
                            {
                                //ここで1行の表示
                                let lineData = [];
                                const item = downloadData[i][j];
                                for(let i in this.displayListSelected)
                                {
                                    const target = this.displayListSelected[i].list_item;
                                    let value = item[target.kind_index][target.item_index];
                                    if(target.total != null)
                                    {
                                        value = value[target.total]; //複数の値が保持されている場合はここで取得
                                    }
                                    lineData.push({
                                        colIndex: lineData.length,
                                        value: target.convert(value), // defineListからのconvertを継承している。表示方法変えるならここでもOK
                                    });
                                }
                                //1行分を配列に追加
                                this.dataContents.push({
                                    rowIndex: this.dataContents.length,
                                    contentLine: lineData,
                                });
                            }
                        }
                        this.is_searched_flg = true;
                    }
                    this.isLoading = false;
                }).catch(error =>{
                    this.isLoading = false;
                });
            }
        },
        //CSVダウンロード処理
        downloadCsv(){
            //データクリア
            this.csvBuff = [];
            this.labelBuff = {};

            var inputDate = Number(this.serialToDateStr(this.dateStrToSerial(this.defaultDate), "YYYYMMDD"));
            let suff = this.saveName == "" ? "" : this.saveName + "_";
            this.csvFileName = "汎用検索_" + suff + inputDate;

            for(let col = 0; col < this.dataHeader.length; col++){
                this.labelBuff[col] = {title: this.dataHeader[col].value};
            }
            for(let row = 0; row < this.dataContents.length; row++){
                for(let col = 0; col < this.dataHeader.length; col++){
                    this.csvBuffRow.push(this.dataContents[row].contentLine[col].value);
                }
                this.csvBuff.push(this.csvBuffRow);
                this.csvBuffRow = [];
            }
        },
        //検索中のキャンセルボタン実装
        cancelGeneralSearch(){
            this.isClickedCancel = true;
            axios.get('generalSearchCancel',{
                params:{
                    'jobID' : this.jobID,
                }
            }).then(response => {
                if(!response.data.result)
                {
                    //キャンセル処理失敗
                    this.modalOption_112.message = "キャンセル処理に失敗しました。";
                    this.openModal("m112_common_message", "", this.modalOption_112);
                }
                this.isLoading = false;
                this.isClickedCancel = false;
            });
        },
        //変換関数を返す
        convertMethod(type){
            switch(type)
            {
                case "text":
                    return (value)=>{return value}; //何もしない関数
                case "time":
                    return this.serialToTimeStr;
                case "hours":
                    return this.serialToHoursStr;
                case "totalTimeSerial":
                    return this.serialToHoursStr;
                case "date":
                    return this.serialToDateStr;
                case "totalCount":
                    return (value)=>{return value};
                case "indexedText":
                    return (value)=>{return value};
                default : 
                    return (value)=>{return value};
            }
        },
    },
    mounted(){
        this.office_options_list = this.getMasterData().office;
        this.approval_state_options_list = this.getMasterData().approval_state;
        this.close_state_options_list = this.getMasterData().close_state;
        if (this.is_office_page){
                this.selectedOfficeID = this.session_data.office_id;
        }
        axios.get('getGeneralSearchList')
        .then(response => {
            if(response.data.result)
            {
                this.defineList = response.data.values.defineList;
                this.setDisplayListSelectable();
            }
        });
    },
    computed:{
        displayListName: function(){
            return function(listItem){
                return "[" + listItem.kind_name + "]" + listItem.item_name + listItem.total_name;
            }
        },
        progressValue: function(){
            return "集計中・・・(" + this.progress + " / " + this.progressTotal + ")";
        },
    },
    watch: {
        unitInput: {
            handler(value){
                this.removeListAll();
                this.setDisplayListSelectable();
            }
        }
    }
}
</script>