<template>
    <div id="C004-01-01" class="container-fluid p-3 h-100 w-100 shadow-sm board">
        <loading :active.sync="isLoading"
            :can-cancel="true"
            :on-cancel="onCancel"
            :is-full-page="fullPage"></loading> 
        <div id="C004-01-01-01" v-html="title" style="font-size:12pt">
        </div>
        <div class="row" style="padding: 20px 35px 0 35px;">
            <button id="C106-01-17" type="button" class="btn btn-primary w-15 col-sm-1 mr-2" v-on:click="applyInformationReaded(null, true)" :disabled="is_nocheck">既読にする</button>
            <button id="C106-01-17" type="button" class="btn btn-primary w-15 col-sm-1" v-on:click="applyInformationReaded(null, false)" :disabled="is_nocheck">未読にする</button>
            <div class="col-sm-7 mr-3"></div>
            <button id="C106-01-17" type="button" class="btn btn-primary w-15 col-sm-1 mr-2" v-on:click="changeShowMode(false)" v-bind:disabled="!is_allshow">未読</button>
            <button id="C106-01-17" type="button" class="btn btn-primary w-15 col-sm-1" v-on:click="changeShowMode(true)" v-bind:disabled="is_allshow">全件</button>
        </div>
        <div v-if="m002_information.length > 0" class="container-C106-1 text-left" style="margin-top:10pt;padding-left:20px;padding-right:20px">
            <div class="row" style="height: 40px; line-height:40px; font-size: 1rem; padding: 0 15px;">
                <div id="C106-01-05" class="col-sm-1 C106-tblidx"><input type="checkbox" id="checkAll" name="checkAll" v-on:click="onClickCheckAll()" v-model="isCheckedCheckAll"/></div>
                <div id="C106-01-06" class="col-sm-5 C106-tblidx C106-tblidx-border-left">タイトル</div>
                <div id="C106-01-07" class="col-sm-2 C106-tblidx C106-tblidx-border-left">種別</div>
                <div id="C106-01-08" class="col-sm-2 C106-tblidx C106-tblidx-border-left">投稿日時</div>
                <div id="C106-01-09" class="col-sm-2 C106-tblidx C106-tblidx-border-left">掲載期間</div>
            </div>
            <div style="height: 256px; padding:0 15px; overflow-y: auto; overflow-x: hidden; background: #FFFFFF;">
                <div v-bind:class="{'row C106-form-even': item.index % 2 === 0, 'row C106-form-odd': item.index % 2 !== 0,'list_font_weight_bolder': item.is_readed === 0,'list_font_weight_normal': item.is_readed !== 0 }" v-for="item in m002_information" :key="item.information_id" v-show="is_allshow || !item.is_readed">
                    <div class="form-group col-sm-1">
                        <input type="checkbox" v-model="item.isChecked" v-on:change="chkClick()"/><label style="padding-left: 13px;color:#cc0000">{{new_text(item.updated_at, item.is_readed)}}</label>
                    </div>
                    <div class="form-group col-sm-5">
                        <label style="cursor: pointer;" v-on:click="openSystemInformationModal(item)">{{item.information_subject_name}}</label>
                    </div>
                    <div class="form-group col-sm-2">
                        {{item.information_type_name}}
                    </div>
                    <div class="form-group col-sm-2">
                        {{item.created_at}}
                    </div>
                    <div class="form-group col-sm-2">
                        {{valid_date(item.valid_date_start, item.valid_date_end)}}
                    </div>
                </div>
                <div v-if="no_unlead_info" style="margin-top: 20px; margin-left: 20px; font-size: 1.2rem;">
                    <label>未読のインフォメーションはありません</label>
                </div>
            </div>
        </div>
        <div v-else class="container-C106-1 text-left" style="margin-top:10pt;padding-left:20px;padding-right:20px">
            <div class="text-center" style="color:#000000;font-size:15pt">
                データがありません
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
    props: {
        title: String,
    },
    data() {
        return {
            isLoading: false,  //ローディング画面用プロパティ
            fullPage: true,    //ローディング画面用プロパティ
            is_nocheck: true,
            m002_information: [],
            isCheckedCheckAll: false,
            is_allshow: false, //デフォルトの表示モード
            modalOptionRead: {
                message: '',
                buttons:[{
                        exec : ()=>{
                            this.btn="OK"; //ボタンが押された時の処理をここに記載
                            //モーダルを閉じる
                            $('body').removeClass('modal-open');
                            this.cleanModal();
                            $('.modal-backdrop').remove();
                        },
                        caption : "OK",  //'OK','はい','いいえ','キャンセル','再試行','閉じる'
                        btnclass : "btn-success"
                    }],
            },
            system_option: {
                subject: "",
                content: "",
                term: "",
                date: "",
                new: "",
            },
        };
    },
    mounted() {
        this.updateInformation();
    },
    methods: {
        updateInformation(){
            //新着情報取得
            this.isLoading = true;
            axios.post('getInformation', {
            }).then(response => {
                for(let i = 0; i < response.data.length; i++)
                {
                    let info = response.data[i];
                    let item = this.m002_information.find((elm)=>{return elm.information_id == info.information_id});
                    if(item)
                    {
                        item.index = i;
                        item.isChecked = false;
                        item.information_id = info.information_id;
                        item.information_type_id = info.information_type_id;
                        item.information_type_name = info.information_type_name;
                        item.nformation_subject_name = info.information_subject_name;
                        item.information_contants = info.information_contants;
                        item.valid_date_start = info.valid_date_start;
                        item.valid_date_end = info.valid_date_end;
                        item.updated_at = info.updated_at.slice(0,16);
                        item.created_at = info.created_at.slice(0,16);
                        item.is_readed = info.is_readed;
                    }
                    else
                    {
                    this.m002_information.push({
                        'index': i,
                        'isChecked': false,
                        'information_id': info.information_id,
                        'information_type_id': info.information_type_id,
                        'information_type_name': info.information_type_name,
                        'information_subject_name': info.information_subject_name,
                        'information_contants': info.information_contants,
                        'valid_date_start': info.valid_date_start,
                        'valid_date_end': info.valid_date_end,
                        'updated_at': info.updated_at.slice(0,16),
                        'created_at': info.created_at.slice(0,16),
                        'is_readed': info.is_readed,
                    });
                    }
                }
                this.isLoading = false;
            }).catch(error => {
                this.isLoading = false;
                //取得エラー発生
            });
        },
        onClickCheckAll(){
            //チェック状態をあわせる
            this.m002_information.forEach((e)=>{
                this.$set(e, 'isChecked', !this.isCheckedCheckAll);//なぜか反転させなければいけない・・・？
            });
            this.chkClick();
        },
        //チェックボックスクリック
        chkClick(){
            var checked = false;
            for(let key in this.m002_information)
            {
                if(this.m002_information[key].isChecked && (this.is_allshow || !this.m002_information[key].is_readed))
                {
                    checked = true;
                    break;
                }
            }
            this.is_nocheck = !checked;
        },
        changeShowMode(is_all){
            this.is_allshow = is_all;
        },
        applyInformationReaded(target_item, is_change_to_readed){
            //チェック済みかつ表示されているattendanceInformation取得
            let checked_array = [];
            if(!target_item)
            {
                //nullの場合は、チェックボックスでチェックされているものをすべて取得
                for(let key in this.m002_information)
                {
                    if(this.m002_information[key].isChecked && (this.is_allshow || !this.m002_information[key].is_readed))
                    {
                        checked_array.push(this.m002_information[key]);
                    }
                }
            }
            else
            {
                //単一選択の場合
                checked_array.push(target_item);
            }
            this.isLoading = true;
            //既読にするか、未読にするか
            let axios_name = is_change_to_readed ? "readCheckedInformation" : "unreadCheckedInformation";
            axios.post(axios_name, {
                params: {
                    info_array: checked_array,
                }
            }).then(response => {
                if(response.data.result)
                {
                    this.updateInformation();
                    this.isCheckedCheckAll = false;
                    this.is_nocheck = true;
                    
                }
                else
                {
                    //選択がない状態でリクエストなど行われた場合
                    this.modalOptionRead.message = response.data.values.message;
                    if(!target_item)
                    {
                        this.openModal("m112_common_message", "", this.modalOptionRead);
                    }
                }
                this.isLoading = false;
            }).catch(error => {
                console.log(error.response);
                this.isLoading = false;
            });
        },
        onCancel() {
            //Loading画面のキャンセル
        },
        openSystemInformationModal(data) {
            this.applyInformationReaded(data, true);
            this.system_option.subject = data.information_subject_name;
            this.system_option.content = data.information_contants;
            this.system_option.term = this.valid_date(data.valid_date_start, data.valid_date_end);
            this.system_option.date = data.created_at;
            this.system_option.new = this.new_text(data.updated_at, data.is_readed);
            this.openModal('m102_information', 'modal-lg', this.system_option);
        },
    },
    computed:{
        valid_date(){
            return function(start, end){
                if(start == 0)
                {
                    if(end == 2958465)
                    {
                        //無期限
                        return "なし";
                    }
                    else
                    {
                        //最初～無期限
                        return "～ " + this.serialToDateStr(end); 
                    }
                }
                else if(end == 2958465)
                {
                    //start～無期限
                    return this.serialToDateStr(start) + " ～ ";
                }
                else
                {
                    //start～end
                    return this.serialToDateStr(start) + " ～ " + this.serialToDateStr(end);
                }
            }
        },
        new_text(){
            return function(date, is_readed){
                const updated_at = new Date(date);
                const today = new Date();
                let term = (today - updated_at) / 86400000;
                if(!is_readed && term < 14)
                {
                    //未読かつ14日以内
                    return "新着";
                }
                else
                {
                    return "";
                }
            }
        },
        no_unlead_info(){
            return !this.is_allshow && this.m002_information.filter((elm)=>{return !elm.is_readed}).length == 0;
        }
    },
}
</script>