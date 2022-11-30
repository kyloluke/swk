<template>
    <div class="modal-content" id="C110-01">
        <div class="modal-body">
            <loading :active.sync="isLoading"
            :can-cancel="true"
            :on-cancel="onCancel"
            :is-full-page="fullPage"></loading> 
            <div class="form-group row">
                <div class="col-sm-1"></div>
                <div class="col-sm-10">
                    <label id="C110-01-01" class="modal-form-label" for="C110-01-02">社員番号</label>
                    <input id="C110-01-02" type="text" v-model="employeeNumberInput" class="form-control" placeholder="社員番号" value="">
                </div>
                <div class="col-sm-1"></div>
            </div>
            <div class="form-group row">
                <div class="col-sm-1"></div>
                <div class="col-sm-10">
                    <label id="C110-01-03" class="modal-form-label" for="C110-01-04">名前</label>
                    <input id="C110-01-04" type="text" v-model="nameInput" class="form-control" placeholder="名前" value="">
                </div>
                <div class="col-sm-1"></div>
            </div>
            <div class="form-group row">
                <div class="col-sm-1"></div>
                <div class="col-sm-10">
                    <label id="C110-01-05" class="modal-form-label" for="C110-01-06">事業所</label>
                    <select id="C110-01-06" v-model="adjectiveOfficeInput" class="form-control" :disabled="!this.op1.isEnableSelectOffice" value="">
                        <option></option>
                        <option v-for="option in office_options_list" :key="option.office_id" v-bind:value="option.office_id">{{ option.office_name }}</option>
                    </select>
                </div>
                <div class="col-sm-1"></div>
            </div>
            <div class="form-group row">
                <div class="col-sm-1"></div>
                <div class="col-sm-10">
                    <label id="C110-01-07" class="modal-form-label" for="C110-01-08">部署</label>
                    <select id="C110-01-08" v-model="adjectiveDeptInput" class="form-control" value="">
                        <option></option>
                        <option v-for="option in dept_options_list" :key="option.dept_id" v-bind:title="option.dept_name" v-bind:value="option.dept_id">{{ option.dept_name }}</option>
                    </select>
                </div>
                <div class="col-sm-1"></div>
            </div>
            <div class="modal-footer d-flex justify-content-center">
                <button id="C110-01-09" type="button" class="btn btn-primary w-35" style="margin-right: 80px" v-on:click="searchClick">検索</button>
                <button id="C110-01-10" type="button" class="btn btn-danger w-35" data-dismiss="modal">キャンセル</button>
            </div>

            <div v-if="searchflg">
                <div v-if="this.op1.select_period_type">
                    <div class="form-group" style="padding-left: 37px;">
                        <input id="C110-01-13" class="form-check-input" type="checkbox" value="" v-on:click="allchkClick" style="transform: scale(2)">
                        <label id="C110-01-14" class="form-check-label ml-2">全て選択／選択を外す</label>
                    </div>
                    <div style="height: 250px; overflow: scroll; white-space:nowrap; border: 1px solid rgba(0, 0, 0, 0.125);">
                        <!-- ここから該当データ分繰り返し -->
                        <div v-if="employee_list.length">
                            <div class="form-check ml-3 mt-3 mb-2" v-for="item in employee_list" :key="item.employee_code">
                                <input id="C110-01-12" class="form-check-input" type="checkbox" v-model="item.isChecked" style="transform: scale(2)">
                                <label id="C110-01-12-01" class="form-check-label abbreviation ml-2" style="width: 60px" v-bind:title="item.employee_code" v-html="item.employee_code"></label>
                                <label id="C110-01-12-02" class="form-check-label abbreviation" style="width: 140px" v-bind:title="item.employee_name" v-html="item.employee_name"></label>
                                <label id="C110-01-12-03" class="form-check-label abbreviation" style="width: 80px" v-bind:title="item.employee_post" v-html="item.employee_post"></label>
                                <label id="C110-01-12-04" class="form-check-label abbreviation" style="width: 140px" v-bind:title="item.employee_office" v-html="item.employee_office"></label>
                                <label id="C110-01-12-05" class="form-check-label abbreviation" v-html="item.employee_dept"></label>
                            </div>
                        </div>
                        <div v-if="!employee_list.length">　対象者がいません</div>
                        <!-- ここまで該当データ分繰り返し -->
                    </div>
                </div>
                <div v-if="!this.op1.select_period_type">
                    <div style="height: 250px; overflow: scroll; white-space:nowrap; border: 1px solid rgba(0, 0, 0, 0.125);">
                        <!-- ここから該当データ分繰り返し -->
                        <div v-if="employee_list.length">
                            <div class="form-check ml-3 mt-3 mb-2" v-for="(item,index) in employee_list" :key="item.employee_code">
                                <input id="C110-01-11" class="form-check-input" type="radio" name="employeeRadios" style="transform: scale(2)" @click='getIndex(index)'>
                                <label id="C110-01-11-01" class="form-check-label abbreviation ml-2" style="width: 60px" v-bind:title="item.employee_code" v-html="item.employee_code"></label>
                                <label id="C110-01-11-02" class="form-check-label abbreviation" style="width: 140px" v-bind:title="item.employee_name" v-html="item.employee_name"></label>
                                <label id="C110-01-11-03" class="form-check-label abbreviation" style="width: 80px" v-bind:title="item.employee_post" v-html="item.employee_post"></label>
                                <label id="C110-01-11-04" class="form-check-label abbreviation" style="width: 140px" v-bind:title="item.employee_office" v-html="item.employee_office"></label>
                                <label id="C110-01-11-05" class="form-check-label abbreviation" v-html="item.employee_dept"></label>
                            </div>
                        </div>
                        <div v-if="!employee_list.length">　対象者がいません</div> 
                        <!-- ここまで該当データ分繰り返し -->
                    </div>
                </div>
                <div class="modal-footer d-flex justify-content-center">
                    <button id="C110-01-15" type="button" class="btn btn-primary w-35" v-on:click="selectClick()" data-dismiss="modal">選択</button>
                </div>
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
            message: null,
            employeeNumberInput: '',
            nameInput: '',
            adjectiveOfficeInput: [],
            adjectiveDeptInput: [],
            searchflg: false,
            checked: true,
            checkedIndex: 0,
            check_list: [],
            checked_list: [],
            employee_list: [],
            office_options_list: [],
            dept_options_list: [],
            officeId: 0,
            deptId: 0,
            office_visit: false,
            referenceDate: 0,
            isLoading: false,  //ローディング画面用プロパティ
            fullPage: true,    //ローディング画面用プロパティ
        }
    },
    methods: {
        searchClick() { //P110-04 検索処理
            this.isLoading = true;
            this.searchflg = true;

            //総務画面からモーダル開いている場合、officeIdは事業所指定無しの0
            if(this.op1.officeId === 0){
                this.officeId = this.adjectiveOfficeInput;
                this.deptId = this.adjectiveDeptInput;
            }
            //事業所画面からモーダル開いている場合
            else{
                this.officeId = this.op1.officeId;
                this.deptId = this.adjectiveDeptInput;
            }
            this.referenceDate = (null == this.op1.referenceDate) ? this.todaySerial() : this.op1.referenceDate;
            axios.get('officeTargetList', {
                //年月を6桁で送信
                params:{
                    'deptId' : this.deptId,
                    'closeDateId' : this.op1.closeDateId,
                    'officeId' : this.officeId,
                    'nameInput' : this.nameInput,
                    'employeeNumberInput' : this.employeeNumberInput.replace(/^0+/, ''),
                    'referenceDate' : this.referenceDate,
                }
            }).then(response => {
                if(response.data.result)
                {
                    this.employee_list = [];
                    for(let i = 0; i < response.data.values.length; i++)
                    {
                        this.employee_list.push({
                            'employee_id': response.data.values[i].employee_id,
                            'employee_code': response.data.values[i].employee_code,
                            'employee_name': response.data.values[i].employee_name,
                            'employee_post': response.data.values[i].post_name,
                            'employee_office': response.data.values[i].office_name,
                            'employee_dept': response.data.values[i].dept_tree,
                            'employee_close_date_id': response.data.values[i].close_date_id,
                            'isChecked': false,
                        });
                    }
                    this.isLoading = false;
                }
                else
                {
                    //取得失敗
                    this.isLoading = false;
                }
            })

        },
        onCancel() {
            //Loading画面のキャンセル
        },
        selectClick() { //P110-05 選択処理
            if(this.op1.select_period_type){
                this.check_list= [];
                var j = 0;
                for(let i = 0; i < this.employee_list.length; i++){
                    if (this.employee_list[i].isChecked) {
                        this.check_list[j] =  this.employee_list[i];
                        this.employee_list[i].isChecked = false;
                        j++;
                    }
                }
                this.op1.callback_select(this.check_list);
            }
            else{
                this.op1.callback_select(this.employee_list[this.checkedIndex]);
            }
        },
        getIndex(index){
            this.checkedIndex = index;
        },
        //キャンセル処理
        cancelClick() {
            this.op1.callback_cancel();
        },
        allchkClick() { //P110-06 全選択・全解除処理
            for(let i = 0; i < this.employee_list.length; i++){
                this.employee_list[i].isChecked = this.checked;
            }
            this.checked = !this.checked;
        },
    },
    mounted(){  //P110-01 初期化処理
        this.message = null;
        this.employeeNumberInput = '';
        this.nameInput = '';
        this.adjectiveOfficeInput = '';
        this.adjectiveDeptInput = '';

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
        })
        if(!this.op1.isEnableSelectOffice){
            this.adjectiveOfficeInput = this.op1.officeId;
        }
    }
};
</script>
