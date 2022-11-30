<template>
    <div class="modal-content" id="C109-01">
        <div class="modal-body">
    　      <div id="C109-01-01" class="mb-4 ml-2">対象社員を選択してください</div>
                <div class="form-group" style="padding-left: 37px;">
                    <input id="C109-01-02" class="form-check-input" type="checkbox" value="" v-on:click="allchkClick" style="transform: scale(2)">
                    <label id="C109-01-03" class="form-check-label ml-2">全て選択／選択を外す</label>
                </div>
                <div style="height: 250px; overflow: scroll; white-space:nowrap; border: 1px solid rgba(0, 0, 0, 0.125);">
                    <!-- ここから該当データ分繰り返し -->
                    <div id="C109-01-04" class="form-check ml-3 mt-3 mb-2" v-for="item in employee_list" :key="item.employee_code">
                        <input v-bind:id="'C109-01-05-' + item.employee_code" class="form-check-input" v-on:click="chkClick(item.employee_code)" type="checkbox" name="employeechk" style="transform: scale(2)">
                        <label id="C109-01-06-01" class="form-check-label abbreviation ml-2" style="width: 60px" v-bind:title="item.employee_code" v-html="item.employee_code"></label>
                        <label id="C109-01-06-02" class="form-check-label abbreviation" style="width: 140px" v-bind:title="item.employee_name" v-html="item.employee_name"></label>
                        <label id="C109-01-06-03" class="form-check-label abbreviation" style="width: 80px" v-bind:title="item.employee_post" v-html="item.employee_post"></label>
                        <label id="C109-01-06-03" class="form-check-label abbreviation" style="width: 140px" v-bind:title="item.employee_office" v-html="item.employee_office"></label>
                        <label id="C109-01-06-04" class="form-check-label abbreviation" v-html="item.employee_dept"></label>
                    </div>
                    <!-- ここまで該当データ分繰り返し -->
                </div>
            <div class="modal-footer d-flex justify-content-center">
                <button id="C109-01-07" type="button" class="btn btn-primary w-35" style="margin-right: 80px" data-dismiss="modal" v-on:click="selectClick">選択</button>
                <button id="C109-01-08" type="button" class="btn btn-danger w-35" data-dismiss="modal">キャンセル</button>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: ['op1'],
    data() {
        return {
            checked: true,
            check_list: [],
            checked_list: [],
            employee_list: [],
        }
    },
    mounted(){

        $("input[name='employeechk']").prop('checked',false);

        axios.get('getSelectedTargetList', {
            //年月を6桁で送信
            params:{
                'employeeId' : this.op1.employeeID,
                'setting_target_type' : 1,
                'referenceDate' : Math.floor(this.todaySerial()),
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
                    });
                }
                this.employee_list.sort(this.ascendingOrder);
            }
            else
            {
                //取得失敗
            }
        })
    },  
    methods: {
        selectClick() { //P109-05 選択処理

            this.check_list= [];
            var j = 0;
            for(let i = 0; i < this.employee_list.length; i++){
                if ($("#C109-01-05-" + this.employee_list[i].employee_code).prop("checked") === true) {
                    this.check_list[j] = this.employee_list[i];
                    $("#C109-01-05-" + this.employee_list[i].employee_code).prop("checked", false);
                    j++;
                }
            }

            this.op1.callback_select_m109(this.check_list);

            
        },
        //キャンセル処理
        cancelClick() {
            this.op1.callback_cancel_m109();
        },
        allchkClick() { //P109-04 全選択・全解除処理
            $("input[name='employeechk']").prop('checked', this.checked);
            this.checked = !this.checked;
        },
        chkClick(code) { //P109-04 選択
            var isCheckedHave = false;
            for(let i = 0; i < this.checked_list.length; i++){
                if(this.checked_list[i] === code){
                    isCheckedHave = true;
                    $("#C109-01-05-" + code).prop('checked', false);
                    this.checked_list[i].remove;
                    break;
                }
            }
            if(!isCheckedHave){
                this.checked_list.push(code);
                $("#C109-01-05-" + code).prop('checked', true);
            }
        },
    }
};
</script>