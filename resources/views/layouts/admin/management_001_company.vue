<template>
    <div style="font-family: 'Noto Sans JP', sans-serif;">
        <div class="container-fluid h-100" style="min-height:600pt">
            <div class="container-fluid p-3 h-100 w-100 shadow-sm board">
                <div class="row">
                    <div class="px-2">
                        <div class="text-left">
                            企業管理メニュー
                        </div>
                    </div>
                </div>
                <div class="row" style="margin-top: 20px;">
                    <div class="col">
                        <div class="btn btn-primary" @click="openDetailModal(true, null)">新規作成</div>
                    </div>
                </div>
            </div>
            <div class="container-fluid p-3 h-100 w-100 shadow-sm board" style="margin-top: 20px;">
                <div class="row">
                    <div class="px-2">
                        <div class="text-left">
                            企業一覧
                        </div>
                    </div>
                </div>
                <div class="card shadow w-100" style="background-color:#BCD2EE; margin-top:20pt;">
                    <div class="card-body">
                        <table class="table-white" style="font-size:12pt;">
                            <thead>
                                <tr>
                                    <th>会社ID</th>
                                    <th>会社コード</th>
                                    <th>会社名称</th>
                                    <th>会社略称</th>
                                    <th>期首月</th>
                                    <th>有効期限開始</th>
                                    <th>有効期限終了</th>
                                    <th>詳細</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="item in company_list" :key="item.company_id">
                                    <td>{{item.company_id}}</td>
                                    <td>{{item.company_code}}</td>
                                    <td>{{item.company_name}}</td>
                                    <td>{{item.company_short_name}}</td>
                                    <td>{{item.beginning_month}}</td>
                                    <td>{{item.valid_date_start}}</td>
                                    <td>{{item.valid_date_end}}</td>
                                    <td><div class="btn btn-primary" @click="openDetailModal(false, item.company_id)">詳細</div></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import vuejsDatepicker from 'vuejs-datepicker'
import VueJsonToCsv from 'vue-json-to-csv'
export default {
    components: {
        VueJsonToCsv,
        vuejsDatepicker,
    },
    props: {
    },
    data() {
        return {
            company_list: [],
        };
    },
    methods: {
        //新規作成クリック
        openDetailModal(isCreate, company_id){
            this.cleanModal();
            this.openModal("management_edit_company", "modal-xl", {
                isCreate: isCreate,
                onUpdate: this.updateList,
                company_id: company_id,
            });
        },
        //リスト更新
        updateList(){
            this.company_list = [];
            axios.get('getCompanyList').then(response => {
                if(response.data.result)
                {
                    for(let i in response.data.values.company_list)
                    {
                        this.company_list.push({
                            company_id: response.data.values.company_list[i].company_id,
                            company_code: response.data.values.company_list[i].company_code,
                            company_name: response.data.values.company_list[i].company_name,
                            company_short_name: response.data.values.company_list[i].company_short_name,
                            beginning_month: response.data.values.company_list[i].beginning_month + "月",
                            valid_date_start: this.serialToDateStr(response.data.values.company_list[i].valid_date_start),
                            valid_date_end: this.serialToDateStr(response.data.values.company_list[i].valid_date_end),
                        });
                    }
                }
                else
                {
                }
            });
        },
    },
    computed:{
    },
    watch: {
    },
    mounted() {
        //会社一覧取得
        this.updateList();
    }
}
</script>
