<template>
    <div id="C015-01" class="container-fluid p-3 h-100 w-100">
        <div id="C015-01-01" class="container-fluid p-3 h-100 w-100 shadow-sm board" style="margin-top:20pt;">
            <div class="row">
                <div class="col-12">
                    <button id="C015-01-01-01" class="btn btn-primary" style="font-size:15pt;width:150pt" v-on:click="openSettingTargetModal()">対象者設定</button>
                </div>
            </div>
        </div>
        <div id="C015-01-02" class="container-fluid p-3 h-100 w-100 shadow-sm board" style="margin-top:20pt;">
            <div class="row">
                <div class="px-2">
                    <div id="C015-01-02-01" class="text-left">
                        設定されている対象者一覧
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div id="C015-01-02-02" class="d-inline-block" style="color:#000000;">基準日</div>
                    <input id="C015-01-02-03" type="date" v-model="selectedDate" min="1901-01-01" style="font-size:15pt" v-on:change="dateChange()"/>
                    <div v-if="isVisibleTargetList">
                        <div id="C015-01-02-04" class="card shadow w-75" style="background-color:#BCD2EE;margin-top:20pt;">
                            <div class="card-body">
                                <table class="table-white" style="font-size:12pt;">
                                    <thead>
                                        <tr>
                                            <th>社員番号</th>
                                            <th>名前</th>
                                            <th>役職</th>
                                            <th>所属</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- ここから日付分繰り返し -->
                                        <tr v-for="item in delegator_data_list" :key="item.employee_code">
                                            <td v-html="item.employee_code"></td>
                                            <td v-html="item.employee_name"></td>
                                            <td v-html="item.employee_post"></td>
                                            <td v-html="item.employee_dept"></td>
                                        </tr>
                                        <!-- ここまで日付分繰り返し -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div v-else class="text-center" style="background-color:#ffffff;font-size:12pt;color:#000000;margin-top:20pt;line-height: 46px;">対象者は設定されていません</div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    props: {
        employee_id: Number,
        setting_target_type: Number,
    },
    data() {
        return {
            delegator_data_list:[],
            selectedDate: String,
            show: false,
            isVisibleTargetList: false,
            modalOption: {
                isEnableSelectOffice: true, //全事業所選択
                employeeID: 0, //ここでの値保持＆子へ渡す用
                closeDateId: 0, //締め区分指定なし
                setting_target_type: this.setting_target_type, //承認対象者：1、代理入力者：2
                callback: this.updateSelectedTargetList,
            },
        };
    },
    methods:{
        openSettingTargetModal() {
            this.openModal('m108_setting_target', 'modal-xl', this.modalOption);
        },
        //日付が選択されたとき
        dateChange() {
            this.updateSelectedTargetList();
        },
        //リスト更新
        updateSelectedTargetList() {
            axios.get('get_setting_target', {
                params: {
                    'target_date': this.dateStrToSerial(this.selectedDate),
                    'setting_target_type': this.setting_target_type,
                },
            }).then(response => {
                if(response.data.result) {
                    if(response.data.values.length === 0){
                        this.isVisibleTargetList = false;
                    }else{
                        this.isVisibleTargetList = true;
                        this.delegator_data_list = [];
                        for(let i = 0; i < response.data.values.length; i++)
                        {
                            this.delegator_data_list.push({
                                'employee_id': response.data.values[i].employee_id,
                                'employee_code': response.data.values[i].employee_code,
                                'employee_name': response.data.values[i].employee_name,
                                'employee_post': response.data.values[i].employee_post,
                                'employee_dept': response.data.values[i].employee_dept,
                                'employee_sort': response.data.values[i].employee_sort,
                            });
                        }
                        this.delegator_data_list.sort(this.ascendingOrder);
                    }  
                }
                else
                {
                    //取得失敗
                }
            })
        },
        ascendingOrder(a, b) { //t005 or t006のID昇順並べ替え
            if (a.employee_sort < b.employee_sort) return -1;
            if (a.employee_sort > b.employee_sort) return 1;
            return 0;
        },
    },
    watch: {
        employee_id: { // 外からプロパティの中身が変更になったら実行される
            immediate: true,
            handler(value) {
                this.modalOption.employeeID = value;
            }
        },
    },
    mounted() {
        this.selectedDate = this.serialToDateStr(this.todaySerial(), "YYYY-MM-DD");
        this.updateSelectedTargetList();
    },
}
</script>