<template>
    <div id="C106-01" class="modal-content">
        <loading :active.sync="isLoading"
            :can-cancel="true"
            :on-cancel="onCancel"
            :is-full-page="fullPage"></loading> 
        <div class="modal-header">
            <div id="C106-01-01">日報の登録を行います</div>
        </div>
        <div class="modal-body">
            <div class="row">
                <label id="C106-01-02" class="col-sm-1 col-form-label">対象日付</label>
                <div class="form-group col-sm-2">
                    <input id="C106-01-03" type="date" class="form-control" v-model="selectedDate" min="1901-01-01"/>
                </div>
                <label id="C106-01-04" class="col-sm-9 col-form-label">※「保存」を行わずに日付を変更すると、入力内容は破棄されます</label>
            </div>
      　    <div class="message-group row ml-1 mr-1 mb-3">
                <div id="C106-01-20" class="error-message text-center col-sm-12">
                    <div>{{message}}</div>
                </div>
            </div>

            <div class="container-C106-1 text-center mr-2 ml-2">
                <div class="row">
                    <div id="C106-01-05" class="col-sm-1 C106-tblidx">No</div>
                    <div id="C106-01-06" class="col-sm-3 C106-tblidx C106-tblidx-border-left">作業時間</div>
                    <div id="C106-01-07" class="col-sm-3 C106-tblidx C106-tblidx-border-left">テーマ</div>
                    <div id="C106-01-08" class="col-sm-4 C106-tblidx C106-tblidx-border-left">内容</div>
                    <div id="C106-01-09" class="col-sm-1 C106-tblidx C106-tblidx-border-left">削除</div>
                </div>
                <div id="C106odd" v-bind:class="{'row pt-3 C106-form-even': item.work_no % 2 === 0, 'row pt-3 C106-form-odd': item.work_no % 2 !== 0 }" v-for="item in daily_report_list" :key="item.work_no">
                    <label class="col-sm-1 col-form-label" v-html="item.work_no"></label>
                    <div class="form-group col-sm-1 pr-0">
                        <inputTypeTimeModel v-model="item.work_time_start"></inputTypeTimeModel>
                    </div>
                    <label id="C106-01-12" class="col-sm-1 col-form-label pr-0 pl-0">～</label>
                    <div class="form-group col-sm-1 pl-0">
                        <inputTypeTimeModel v-model="item.work_time_end"></inputTypeTimeModel>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <select class="form-control" v-model="item.theme_id">
                                <option v-for="theme in theme_list" :key="theme.theme_id" v-bind:value="theme.theme_id">{{ theme.theme_content }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <input type="text" class="form-control" v-model="item.work_content">
                        </div>
                    </div>
                    <div class="col-sm-1">
                        <button type="button" class="clear-decoration" v-on:click="deleteClick(item.work_no)"><i class="fas fa-trash-alt fa-2x" data-fa-transform="down-1"></i></button>
                    </div>
                </div>
            </div>
        </div>
        <button id="C106-01-17" type="button" class="btn btn-primary w-15 mt-3 ml-3" v-on:click="addRowButtonClick()" v-bind:disabled="60 <= daily_report_list.length">行追加</button>
        <div class="modal-footer d-flex justify-content-center pb-0">
            <button id="C106-01-18" type="button" class="btn btn-primary w-25" v-on:click="saveButtonClick()" style="margin-right: 120px">保存</button>
            <button id="C106-01-19" type="button" class="btn btn-success w-25" v-on:click="closeButtonClick()" data-dismiss="modal">閉じる</button>
        </div>
    </div>
</template>

<script>
import Loading from 'vue-loading-overlay';
import 'vue-loading-overlay/dist/vue-loading.css';

export default {
    name: "inputAttendance_board",
    components: {
        "loading":Loading
    },
    name: "M106data",
    props: ['op1'],
    data() {
        return {
            selectedDate: this.serialToDateStr(this.op1.date, "YYYY-MM-DD"),
            selectedDateSerial: 0,
            message:'',
            number:5,
            daily_report_list: [],
            valid_daily_report_list: [],
            modalOption: {
                message: '',
                buttons:[　//【暫定】どのボタン指定するかは呼び出し元などから決定し指定する
                    {
                        exec : ()=>{
                            this.btn="OK"; //ボタンが押された時の処理をここに記載
                        },
                        caption : "OK",  //'OK','はい','いいえ','キャンセル','再試行','閉じる'
                        btnclass : "btn-success"
                    }],
            },
            modalOptionSuccess: {
                message: '日報を登録しました。',
                buttons:[　//【暫定】どのボタン指定するかは呼び出し元などから決定し指定する
                    {
                        exec : ()=>{
                            this.btn="OK"; //ボタンが押された時の処理をここに記載
                        },
                        caption : "OK",  //'OK','はい','いいえ','キャンセル','再試行','閉じる'
                        btnclass : "btn-success"
                    }],
            },
            theme_list: [],
            today: String,
            saveflg: true,
            isLoading: false,  //ローディング画面用プロパティ
            fullPage: true,    //ローディング画面用プロパティ
        }
    },
    methods: {
        //指定された日の日報を取得して画面更新
        getDailyReprot(){
            this.isLoading = true;
            //該当日付にてget実施
            axios.get('m106_get_daily_report', {
                params:{
                    'employee_id' : this.op1.employee_id,
                    'target_date' : this.selectedDateSerial,
                }
            }).then(response => {
                if(response.data.result)
                {
                    //配列クリア
                    this.daily_report_list = [];

                    //登録済みの日報
                    for(let i = 0; i < response.data.values.length; i++)
                    {
                        this.daily_report_list.push({
                            'daily_report_id': response.data.values[i].daily_report_id,
                            'work_no': response.data.values[i].work_no,
                            'work_time_start': response.data.values[i].work_time_start,
                            'work_time_end': response.data.values[i].work_time_end,
                            'theme_id': response.data.values[i].theme_id,
                            'work_content': response.data.values[i].work_content,
                        });
                    }
                    //5件に満たない場合は空配列作成
                    if(response.data.values.length < 5)
                    {
                        for(let i = response.data.values.length; i < 5; i++)
                        {
                            this.daily_report_list.push({
                                'daily_report_id': 0,
                                'work_no': i + 1,
                                'work_time_start': null,
                                'work_time_end': null,
                                'theme_id': 0,
                                'work_content': "",
                            });
                        }
                    }
                }
                else
                {
                    this.modalOption.message = response.data.values.message;
                    this.openModal("m112_common_message", "", this.modalOption);
                }
                this.isLoading = false;
            }).catch(error => {
                this.isLoading = false;
            })
        },
        //保存ボタンクリックの動作
        saveButtonClick() {
            if(!this.validate())
            {
                //バリデーションエラー
                if(this.message.length == 0)
                {
                    this.message = "入力エラーがあります。入力した項目を確認して下さい";
                }
                return;
            }
            this.message = "";
            //日報を登録、修正又は削除
            axios.post('m106_update_daily_report', {
                'employee_id' : this.op1.employee_id,
                'target_date' : this.selectedDateSerial,
                'daily_report_list' : this.valid_daily_report_list,
            }).then(response => {
                if(response.data.result)
                {
                    this.openModal("m112_common_message", "", this.modalOptionSuccess);
                }
                else
                {
                    this.modalOption.message = response.data.values.message;
                    this.openModal("m112_common_message", "", this.modalOption);
                }
                this.getDailyReprot();
            })
        },
        //行追加ボタンの動作
        addRowButtonClick: function() {
            if(this.daily_report_list.length < 60)
            {
                //空行追加
                this.daily_report_list.push({
                    'daily_report_id': 0,
                    'work_no': this.daily_report_list.length + 1,
                    'work_time_start': null,
                    'work_time_end': null,
                    'theme_id': 0,
                    'work_content': "",
                })
            }
        },
        //削除ボタンクリックの動作
        deleteClick(item_no) {
            //クリックしたItemを削除して番号詰める
            this.daily_report_list = this.daily_report_list.filter((item)=>{return item.work_no != item_no});
            for(let i = 0; i < this.daily_report_list.length; i++)
            {
                this.daily_report_list[i].work_no = i + 1; 
            }
            if(this.daily_report_list.length < 5)
            {
                //空行追加
                this.daily_report_list.push({
                    'daily_report_id': 0,
                    'work_no': this.daily_report_list.length + 1,
                    'work_time_start': null,
                    'work_time_end': null,
                    'theme_id': 0,
                    'work_content': "",
                })
            }

        },
        //閉じるボタンクリック
        closeButtonClick() {
        },
        //Loading画面のキャンセル
        onCancel() {
        },
        //入力値バリデーション
        validate(){
            this.valid_daily_report_list = [];
            //入力のある行のみ取得
            let filtered = this.daily_report_list.filter((item)=>{
                return(
                    item.work_time_start != null ||
                    item.work_time_end != null ||
                    item.work_content != ""
                );
            });
            //filteredがゼロの時は、すべてが空の場合のみ許容
            if(filtered.length == 0)
            {
                let input = this.daily_report_list.filter((item)=>{
                    return(
                        item.work_time_start != null ||
                        item.work_time_end != null ||
                        item.work_content != ""
                    );
                });
                return input.length == 0;
            }
            //入力チェック
            for(let i = 0; i < filtered.length; i++)
            {
                //半角、全角ブランクを除去した内容で入力チェック
                var remove_blank_content = filtered[i].work_content.replace(/\s+/g, "");
                //作業時間、内容は必須
                if(filtered[i].work_time_start == null ||
                    filtered[i].work_time_end == null ||
                    remove_blank_content == "")
                {
                    this.message = 'No' + filtered[i].work_no + 'の作業時間と内容を入力してください';
                    return false;
                }
                //時間の入力不備
                if(filtered[i].work_time_start == -1 ||
                    filtered[i].work_time_end == -1)
                {
                    this.message = 'No' + filtered[i].work_no + 'の作業時間の入力に不備があります';
                    return false;
                }
                //入力文字数
                if(200 < filtered[i].work_content.length)
                {
                    this.message = 'No' + filtered[i].work_no + 'の内容は200文字以内で入力してください';
                    return false;
                }
                //バリデーションOKの時は、時間シリアル値をセット
                filtered[i].work_time_start_serial = filtered[i].work_time_start;
                filtered[i].work_time_end_serial = filtered[i].work_time_end;
            }
            this.valid_daily_report_list = filtered;
            return true;
        }
    },
    mounted(){
        //テーマをセット
        this.theme_list = [];
        this.theme_list.push({
            theme_id: 0,
            theme_content: '',
        })
        for(let theme of this.op1.session_data.master_data.theme)
        {
            this.theme_list.push({
                theme_id: theme.theme_id,
                theme_content: theme.theme,
            }) 
        }
    },
    watch: {
        selectedDate: {
            immediate: true,
            handler(value) {
                if(this.checkDateStr(value))
                {
                    const valueSerial = this.dateStrToSerial(this.getValidDateStr(value));
                    if(valueSerial != this.selectedDateSerial)
                    {
                        //ここで未保存の入力がある場合はアラート出す？
                        
                        this.selectedDateSerial = valueSerial;
                        //データ取得して更新
                        this.getDailyReprot();
                    }
                }
            }
        }
    },
};
</script>