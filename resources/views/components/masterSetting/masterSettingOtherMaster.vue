<template>
    <div>
        <div class="container-fluid p-3 h-100 w-100 shadow-sm board" style="margin-top:20pt;">
            <div class="row">
                <div class="col sm-2">
                    <div class="text-left">
                        その他マスタ変更
                    </div>
                </div>
            </div>
            <div class="row" style="margin-top: 20px;">
                <div class="col" style="line-height: 40px;">
                    <span style="color: #000000; font-size: 15px; margin-left: 20px;">マスタ種別</span>
                    <select class="form-select" style="width: 400px; margin-left: 20px; height:32px;" v-bind:disabled="isSelectedMaster" v-model="selectedMasterID">
                        <option value="1">インフォメーション種別</option>
                        <option value="12">勤務形態</option>
                        <option value="13">雇用形態</option>
                        <option value="14">時間外区分</option>
                        <option value="15">控除事由</option>
                        <option value="16">締日</option>
                        <option value="27">出休</option>
                        <option value="28">Web打刻乖離時間</option>
                        <option value="30">勤務実績</option>
                        <option value="31">不就業</option>
                        <option value="32">有給付与条件パターン</option>
                        <option value="33">有給付与日数パターン</option>
                        <option value="35">36協定集計単位区分</option>
                        <option value="39">過重労働防止チェック</option>
                        <option value="43">休暇</option>
                    </select>
                </div>
                <div class="col">
                    <button style="font-size:11pt;width:100pt" class="btn btn-primary" @click="onSelectMasterKind" v-bind:disabled="isSelectedMaster">選択</button>
                </div>
            </div>
        </div>
        <div v-if="isSelectedMaster" class="container-fluid p-3 h-100 w-100 shadow-sm board" style="margin-top:20pt;">
            <div style="width: 100%; color: red; background-color: white; padding: 10px 20px;">
                <div v-if="isCriticalMasetr">※このマスタデータは日々の集計や登録に影響を与えます。運用開始後は、基本的に編集を行わないでください。</div>
                <div v-if="isNeedLogoutMaster">※マスタデータが各ユーザーへ反映されるには、各ユーザーのログアウトが必要になります。</div>
            </div>
            <div class="row">
                <div class="col">
                    <table  class="table-master" style="margin-top:20pt;font-size:12pt;">
                        <thead>
                            <tr>
                                <th v-for="label in masterTableLabels" v-bind:key="label.num">{{label.displayName}}</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="item in masterTableData" v-bind:key="item.key">
                                <td style="cursor: pointer; color: blue; text-decoration: underline; text-decoration-color: blue;" @click="onClickDetailNumber(item.key)">{{item.value.num}}</td>
                                <td v-for="define in masterDefineList" v-bind:key="define.id">{{item.value[define.column]}}</td>
                                <td>{{labelIsValid(item.value.is_valid)}}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="row" style="margin-top: 20px;">
                <div class="col" style="text-align: right; margin-right: 20px;">
                    <button style="font-size:11pt;width:100pt" class="btn btn-danger" @click="closeMasterTable">閉じる</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "masterSettingOtherMaster",
    data() {
        return {
            isCriticalMasetr: true,
            isNeedLogoutMaster: true, //基本的に表示しっぱなし　※非表示の場合は、外枠のpaddingの考慮が必要
            isSelectedMaster: false,
            masterTableLabels: [],
            masterTableData: [],
            masterList: [],
            masterDefineList: [],
            selectedMasterID: 0,
        }
    },
    methods: {
        //一覧表を閉じる
        closeMasterTable(){
            this.isSelectedMaster = false;
            this.masterTableData = [];
            this.masterTableLabels = [];
        },
        onSelectMasterKind(){
            if(this.selectedMasterID == 0)
            {
                return;
            }
            //選択されているマスタを一覧表に表示
            axios.get("getOtherMasterList",{
                params:{
                    "masterID" : this.selectedMasterID,
                }
            }).then(response => {
                if(response.data.result)
                {
                    this.masterList = response.data.values.masterList;
                    this.masterDefineList = response.data.values.defineList;
                    this.masterTableLabels = [];
                    this.masterTableData = [];
                    //ラベル作成
                    this.masterTableLabels.push({
                        num: this.masterTableLabels.length,
                        displayName: "No",
                    });
                    for(let j = 0; j < this.masterDefineList.length; j++)
                    {
                        this.masterTableLabels.push({
                            num: this.masterTableLabels.length,
                            displayName: this.masterDefineList[j].displayName,
                        });
                    }
                    this.masterTableLabels.push({
                        num: this.masterTableLabels.length,
                        displayName: "有効",
                    });
                    //データ作成
                    for(let i = 0; i < this.masterList.length; i++)
                    {
                        let item = {};
                        //defineListに対する列作成
                        for(let j = 0; j < this.masterDefineList.length; j++)
                        {
                            let value = this.masterList[i][this.masterDefineList[j].column];
                            let type = this.masterDefineList[j].type;
                            if(type == "class")
                            {
                                let elm = this.masterDefineList[j]["classes"].find(elm => {return elm.value == value});
                                value = elm["displayName"];
                            }
                            //カラム名でvalue登録
                            item[this.masterDefineList[j].column] = value;
                        }
                        //共通表示項目作成
                        item["num"] = i + 1;  //番号はデータのインデックス
                        item["is_valid"] = this.masterList[i]["is_invalid"] == 0;
                        this.masterTableData.push({
                            key: this.masterTableData.length,
                            value: item,
                        });
                    }
                    //一覧表を表示
                    this.isSelectedMaster = true;
                }
                else
                {
                }
            }).catch(error =>{
            });
        },
        onClickDetailNumber(key){
            //マスタ変更モーダル表示
            this.openModal("m120_master_data", "modal-l", {
                masterID: this.selectedMasterID,
                masterData: this.masterList[key],
                masterDefine: this.masterDefineList,
                callbackReload: this.onSelectMasterKind,
            });
        }
    },
    computed: {
        labelIsValid(){
            return function(value)
            {
                return value ? "有効" : "無効";
            }
        }
    },
    mounted(){
    }
}
</script>