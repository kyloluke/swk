<template>
    <div>
        <div class="container-fluid p-3 h-100 w-100 shadow-sm board" style="margin-top:20pt;">
            <div class="row">
                <div class="px-2">
                    <div class="text-left">
                        インフォメーション一覧
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="px-3 col-12 text-right">
                    <input type="checkbox" v-model="isShowOvertime" v-on:click="showOvertime()"/> 掲載期間が過ぎたものは表示しない
                </div>
            </div>
            <div class="row">
                <div class="p-2 col-12 text-left">
                    <div class="card shadow h-100" style="background-color:#BCD2EE;">
                        <div class="card-body">
                            <table v-if="m002_information.length != 0" class="table-master" style="margin-top:20pt;font-size:12pt;">
                                <thead>
                                    <tr>
                                        <th rowspan="2" style="width:3%;">No</th>
                                        <th rowspan="2" style="width:7%;">種別</th>
                                        <th rowspan="2" style="width:7%;">事業所</th>
                                        <th rowspan="2" style="width:28%;">件名</th>
                                        <th rowspan="2" style="width:30%;">内容</th>
                                        <th class="noBorder" colspan="2" style="width:20%;">表示期間</th>
                                        <th rowspan="2" style="width:5%;">削除</th>
                                    </tr>
                                    <tr>
                                        <th style="width:10%;">開始</th>
                                        <th style="width:10%;">終了</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="item in m002_information" :key="item.information_id">
                                        <td style="cursor: pointer; color: blue; text-decoration: underline; text-decoration-color: blue;width:3%;" v-on:click="editInformation(item)">{{item.index}}</td>
                                        <td style="width:7%;">{{item.information_type_name}}</td>
                                        <td style="width:7%;">{{item.office_name}}</td>
                                        <td style="width:28%;">{{item.short_information_subject_name}}</td>
                                        <td style="text-align: left;width:30%;">{{item.short_information_contants}}</td>
                                        <td style="width:10%;">{{item.valid_date_start == 0 || item.valid_date_start == 2958465 ? '無期限' : serialToDateStr(item.valid_date_start)}}</td>
                                        <td style="width:10%;">{{item.valid_date_end == 0 || item.valid_date_end == 2958465 ? '無期限' : serialToDateStr(item.valid_date_end)}}</td>
                                        <td style="width:5%;"><button type="button" class="clear-decoration" v-on:click="deleteInformation(item.information_id)"><i class="fas fa-trash-alt fa-2x" data-fa-transform="down-1"></i></button></td>
                                    </tr>
                                </tbody>
                            </table>
                            <div v-else>
                                <div class="text-center" style="color:#000000;font-size:15pt">
                                データがありません
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="px-3 col-12 text-right">
                    <button style="font-size:11pt;width:100pt" class="btn btn-primary" v-on:click="insertInformation()">新規作成</button>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    data() {
        return {
            op1:{
                callback_regist: ()=>{this.m121_callback_regist();},
                callback_cancel: ()=>{this.m121_callback_cancel();},
                information: null,
            },
            modalOption: {
                message: '',
                buttons:[　//【暫定】どのボタン指定するかは呼び出し元などから決定し指定する
                    {
                        exec : ()=>{
                            this.btn = "OK"; //ボタンが押された時の処理をここに記載
                            //M112モーダルを閉じる
                            this.cleanModal();
                            $('.modal-backdrop').remove();
                        },
                        caption : "OK",  //'OK','はい','いいえ','キャンセル','再試行','閉じる'
                        btnclass : "btn-success"
                    }],
            },
            deleteModalOption: {
                message: '',
                buttons:[　//【暫定】どのボタン指定するかは呼び出し元などから決定し指定する
                    {
                        exec : ()=>{
                            this.btn = "OK"; //ボタンが押された時の処理をここに記載
                            //M112モーダルを閉じる
                            $('body').removeClass('modal-open');
                            this.cleanModal();
                            $('.modal-backdrop').remove();
                            this.delete();
                        },
                        caption : "OK",  //'OK','はい','いいえ','キャンセル','再試行','閉じる'
                        btnclass : "btn-success"
                    },
                    {
                        exec : ()=>{
                            this.btn="キャンセル";
                        },
                        caption : "キャンセル",
                        btnclass : "btn-danger"
                    }],
            },
            m002_information: [],
            isShowOvertime: false,
            selectedInformationId: 0,
        }
    },
    methods:{
        editInformation(information)
        {
            //モーダルを開く
            this.op1.information = information;
            this.openModal('m121_register_information', '', this.op1)
        },
        insertInformation()
        {
            this.op1.information = null;
            //モーダルを開く
            this.openModal('m121_register_information', '', this.op1)
        },
        deleteInformation(information_id)
        {
            this.selectedInformationId = information_id;
            this.deleteModalOption.message = 'インフォメーションを削除しますか';
            this.openModal("m112_common_message", "", this.deleteModalOption);
        },
        delete(){
            axios.post('deleteInformation', {
                params:{
                    'information_id': this.selectedInformationId,
                }
            }).then(response => {
                if(response.data.result)
                {
                    this.updateInformation();
                }
                else
                {
                    this.modalOption.message = response.data.values.message;
                    this.openModal("m112_common_message", "", this.modalOption);
                }
            });
        },
        showOvertime(){
            this.updateInformation();
            this.isShowOvertime = !this.isShowOvertime;
        },
        updateInformation(){
            //新着情報取得
            //this.isLoading = true;
            this.m002_information = [];
            let index = 1;
            let short_information_contants = '';
            let short_information_subject_name = '';
            axios.post('getAllInformation', {
            }).then(response => {
                for(let i = 0; i < response.data.values.information_info.length; i++)
                {
                    let info = response.data.values.information_info[i];
                    
                    if (this.isShowOvertime && (this.todaySerial() > info.valid_date_end || this.todaySerial() < info.valid_date_start)){
                        continue;
                    }
                    if(info.information_subject_name.length > 15){
                        short_information_subject_name = info.information_subject_name.slice(0,17) + '...';
                    }else{
                        short_information_subject_name = info.information_subject_name;
                    }
                    if(info.information_contants.length > 16){
                        short_information_contants = info.information_contants.slice(0,18) + '...';
                    }else{
                        short_information_contants = info.information_contants;
                    }
                    this.m002_information.push({
                        'index': index,
                        'information_id': info.information_id,
                        'information_type_id': info.information_type_id,
                        'information_type_name': info.information_type_name,
                        'office_id': info.office_id,
                        'office_name': info.office_name,
                        'information_subject_name': info.information_subject_name,
                        'short_information_subject_name': short_information_subject_name,
                        'information_contants': info.information_contants,
                        'short_information_contants': short_information_contants,
                        'valid_date_start': info.valid_date_start,
                        'valid_date_end': info.valid_date_end,
                    });
                    index++;
                }
                //this.isLoading = false;
            }).catch(error => {
                //this.isLoading = false;
                //取得エラー発生
            });
        },
        m121_callback_regist(){
            this.updateInformation();
        },
        m121_callback_cancel(){
        
        },
    },
    computed: {

    },
    mounted() {
        this.updateInformation();
    },
}
</script>
