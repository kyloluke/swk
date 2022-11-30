<template>
    <div class="modal-content">
        <div class="modal-body">
            <loading :active.sync="isLoading"
            :can-cancel="true"
            :on-cancel="onCancel"
            :is-full-page="fullPage"></loading>
            <div class="row">
                <div class="col col-6">
                    <div class="form-group row">
                        <div class="col-12">
                            企業情報
                        </div>
                        <div class="col-1"></div>
                        <div class="col-10">
                            <label class="modal-form-label" for="mng_modal_01_01">会社ID</label>
                            <input id="mng_modal_01_01" type="text" class="form-control" v-model="company_id" disabled>
                        </div>
                        <div class="col-1"></div>
                        <div class="col-1"></div>
                        <div class="col-10">
                            <label class="modal-form-label" for="mng_modal_01_02">会社コード</label>
                            <input id="mng_modal_01_02" type="text" class="form-control" placeholder="会社コード" v-model="company_code">
                        </div>
                        <div class="col-1"></div>
                        <div class="col-1"></div>
                        <div class="col-10">
                            <label class="modal-form-label" for="mng_modal_01_03">会社名称</label>
                            <input id="mng_modal_01_03" type="text" class="form-control" placeholder="会社名称" v-model="company_name">
                        </div>
                        <div class="col-1"></div>
                        <div class="col-1"></div>
                        <div class="col-10">
                            <label class="modal-form-label" for="mng_modal_01_04">会社略称</label>
                            <input id="mng_modal_01_04" type="text" class="form-control" placeholder="会社略称" v-model="company_short_name">
                        </div>
                        <div class="col-1"></div>
                        <div class="col-1"></div>
                        <div class="col-10">
                            <label class="modal-form-label" for="mng_modal_01_05">期首月</label>
                            <select id="mng_modal_01_05" v-model="beginning_month" style="display: block; width: 100%">
                                <option value=1>1月</option>
                                <option value=2>2月</option>
                                <option value=3>3月</option>
                                <option value=4>4月</option>
                                <option value=5>5月</option>
                                <option value=6>6月</option>
                                <option value=7>7月</option>
                                <option value=8>8月</option>
                                <option value=9>9月</option>
                                <option value=10>10月</option>
                                <option value=11>11月</option>
                                <option value=12>12月</option>
                            </select>
                        </div>
                        <div class="col-1"></div>
                        <div class="col-1"></div>
                        <div class="col-10">
                            <label class="modal-form-label" for="mng_modal_01_06">有効期限開始</label>
                            <input id="mng_modal_01_06" type="date" class="form-control" placeholder="有効期限開始" v-model="valid_date_start">
                        </div>
                        <div class="col-1"></div>
                        <div class="col-1"></div>
                        <div class="col-10">
                            <label class="modal-form-label" for="mng_modal_01_07">有効期限終了</label>
                            <input id="mng_modal_01_07" type="date" class="form-control" placeholder="有効期限終了" v-model="valid_date_end">
                        </div>
                        <div class="col-1"></div>
                    </div>
                </div>
                <div class="col col-6">
                    <div class="form-group row">
                        <div class="col-12">
                            企業管理者
                        </div>
                        <div class="col-1"></div>
                        <div class="col-10">
                            <label class="modal-form-label" for="mng_modal_02_01">管理者ID（社員ID）</label>
                            <input id="mng_modal_02_01" type="text" class="form-control" v-model="employee_id" disabled>
                        </div>
                        <div class="col-1"></div>
                        <div class="col-1"></div>
                        <div class="col-10">
                            <label class="modal-form-label" for="mng_modal_02_02">管理者コード（社員コード）</label>
                            <input id="mng_modal_02_02" type="text" class="form-control" placeholder="管理者コード（社員コード）" v-bind:disabled="!isCreateMode" v-model="employee_code">
                        </div>
                        <div class="col-1"></div>
                        <div class="col-1"></div>
                        <div class="col-10">
                            <label class="modal-form-label" for="mng_modal_02_03">管理者パスワード（社員パスワード）</label>
                            <input v-if="isCreateMode" id="mng_modal_02_03" type="text" class="form-control" placeholder="管理者パスワード（社員パスワード）" v-model="employee_password">
                            <input v-if="!isCreateMode" id="mng_modal_02_03" type="password" class="form-control" value="********" disabled>
                        </div>
                        <div class="col-1"></div>
                        <div class="col-1"></div>
                        <div class="col-10">
                            <label class="modal-form-label" for="mng_modal_02_04">管理者名</label>
                            <input id="mng_modal_02_04" type="text" class="form-control" placeholder="管理者名" v-bind:disabled="!isCreateMode" v-model="employee_name">
                        </div>
                        <div class="col-1"></div>
                        <div class="col-12" style="margin-top: 20px;">
                            初期設定組織情報
                        </div>
                        <div class="col-1"></div>
                        <div class="col-10">
                            <label class="modal-form-label" for="mng_modal_03_01">事業所ID</label>
                            <input id="mng_modal_03_01" type="text" class="form-control" disabled v-model="office_id">
                        </div>
                        <div class="col-1"></div>
                        <div class="col-1"></div>
                        <div class="col-10">
                            <label class="modal-form-label" for="mng_modal_03_02">事業所名</label>
                            <input id="mng_modal_03_02" type="text" class="form-control" placeholder="事業所名" v-bind:disabled="!isCreateMode" v-model="office_name">
                        </div>
                        <div class="col-1"></div>
                    </div>
                </div>
            </div>
            <div class="modal-footer d-flex justify-content-center">
                <button type="button" class="btn btn-primary w-35" style="margin-right: 80px" v-if="isCreateMode" @click="onClickCreate()">新規作成</button>
                <button type="button" class="btn btn-primary w-35" style="margin-right: 80px" v-if="!isCreateMode" @click="onClickUpdate()">保存</button>
                <button type="button" class="btn btn-danger w-35" data-dismiss="modal">キャンセル</button>
            </div>
        </div>
    </div>
</template>

<script>
import Loading from 'vue-loading-overlay';
import 'vue-loading-overlay/dist/vue-loading.css';
export default {
    props: ['op1'],
    components: {
        "loading":Loading
    },
    data() {
        return {
            isLoading: false,  //ローディング画面用プロパティ
            fullPage: true,    //ローディング画面用プロパティ
            company_id: null,
            company_code: "",
            company_name: "",
            company_short_name: "",
            beginning_month: 1,
            valid_date_start: null,
            valid_date_end: null,
            employee_id: null,
            employee_code: "",
            employee_password: "",
            employee_name: "",
            office_id: null,
            office_name: "",
        }
    },
    computed:{
        isCreateMode: function(){
            return this.op1.isCreate;
        },
        valid_date_start_serial: function(){
            return this.dateStrToSerial(this.valid_date_start);
        },
        valid_date_end_serial: function(){
            return this.dateStrToSerial(this.valid_date_end);
        },
    },
    methods: {
        onCancel() {
            //Loading画面のキャンセル
        },
        //新規作成ボタンクリック
        onClickCreate(){
            if(this.company_code == "" ||
                this.company_name == "" ||
                this.company_short_name == "" ||
                this.valid_date_start == null ||
                this.valid_date_end == null ||
                this.employee_code == "" ||
                this.employee_password ==  "" ||
                this.employee_name ==  "" ||
                this.office_name == "")
            {
                return;
            }
            axios.post('createCompany', {
                company_info:{
                    company_code: this.company_code,
                    company_name: this.company_name,
                    company_short_name: this.company_short_name,
                    beginning_month: this.beginning_month,
                    valid_date_start: this.valid_date_start_serial,
                    valid_date_end: this.valid_date_end_serial,
                },
                employee_info:{
                    employee_code: this.employee_code,
                    employee_password: this.employee_password,
                    employee_name: this.employee_name,
                },
                office_info:{
                    office_name: this.office_name,
                },
            }).then(response => {
                if(response.data.result)
                {
                    this.openModal("m112_common_message", "", {
                        message: '企業情報を登録しました',
                        buttons:[{
                            exec : ()=>{
                                $('body').removeClass('modal-open');
                                this.cleanModal();
                                $('.modal-backdrop').remove();
                            },
                            caption : "OK",
                            btnclass : "btn-success"
                        }],
                    });
                    this.op1.onUpdate();
                }
                else
                {
                    this.openModal("m112_common_message", "", {
                        message: "企業情報の登録に失敗しました[" + response.data.values.message + "]",
                        buttons:[{
                            exec : ()=>{
                                $('body').removeClass('modal-open');
                                this.cleanModal();
                                $('.modal-backdrop').remove();
                            },
                            caption : "OK",
                            btnclass : "btn-success"
                        }],
                    });
                }
            });
        },
        //更新ボタンクリック
        onClickUpdate(){
            if(this.company_code == "" ||
                this.company_name == "" ||
                this.company_short_name == "" ||
                this.valid_date_start == null ||
                this.valid_date_end == null ||
                this.employee_code == "" ||
                this.employee_password ==  "" ||
                this.employee_name ==  "" ||
                this.office_name == "")
            {
                return;
            }
            axios.post('updateCompany', {
                company_info: {
                    company_id: this.op1.company_id,
                    company_code: this.company_code,
                    company_name: this.company_name,
                    company_short_name: this.company_short_name,
                    beginning_month: this.beginning_month,
                    valid_date_start: this.valid_date_start_serial,
                    valid_date_end: this.valid_date_end_serial,
                }
            }).then(response => {
                if(response.data.result)
                {
                    this.openModal("m112_common_message", "", {
                        message: '企業情報を更新しました',
                        buttons:[{
                            exec : ()=>{
                                $('body').removeClass('modal-open');
                                this.cleanModal();
                                $('.modal-backdrop').remove();
                            },
                            caption : "OK",
                            btnclass : "btn-success"
                        }],
                    });
                    this.op1.onUpdate();
                }
                else
                {
                    this.openModal("m112_common_message", "", {
                        message: "企業情報の更新に失敗しました[" + response.data.values.message + "]",
                        buttons:[{
                            exec : ()=>{
                                $('body').removeClass('modal-open');
                                this.cleanModal();
                                $('.modal-backdrop').remove();
                            },
                            caption : "OK",
                            btnclass : "btn-success"
                        }],
                    });
                }
            });
        },
    },
    mounted(){
        if(!this.isCreateMode)
        {
            //データ取得
            axios.get('getCompanyInfo', {
                    params:{
                        'company_id': this.op1.company_id,
                    }
            }).then(response => {
                if(response.data.result)
                {
                    this.company_id = response.data.values.company_info.company_id;
                    this.company_code = response.data.values.company_info.company_code;
                    this.company_name = response.data.values.company_info.company_name;
                    this.company_short_name = response.data.values.company_info.company_short_name;
                    this.beginning_month = response.data.values.company_info.beginning_month;
                    this.valid_date_start = this.serialToDateStr(response.data.values.company_info.valid_date_start, "YYYY-MM-DD");
                    this.valid_date_end = this.serialToDateStr(response.data.values.company_info.valid_date_end, "YYYY-MM-DD");
                    this.office_id = response.data.values.office_info.office_id;
                    this.office_name = response.data.values.office_info.office_name;
                    this.employee_id = response.data.values.employee_info.employee_id;
                    this.employee_code = response.data.values.employee_info.employee_code;
                    this.employee_name = response.data.values.employee_info.employee_name;
                }
                else
                {
                }
            });
        }
    }
};
</script>
