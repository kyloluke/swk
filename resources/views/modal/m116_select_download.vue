<template>
    <div class="modal-content" id="C116-01">
        <div class="modal-body">
            <div id="C116-01-01" class="mb-3 d-flex justify-content-center">ダウンロード対象の事業所及び所属を指定してください</div>
            <div class="form-group row">
                <div class="col-sm-1"></div>
                <div class="col-sm-10">
                    <label id="C116-01-02" class="modal-form-label" for="C116-01-03">事業所</label>
                    <select id="C116-01-03" class="form-control" v-model="adjectiveOfficeInput" value="">
                        <option></option>
                        <option v-for="option in office_options_list" :key="option.office_id" v-bind:value="option.office_id">{{ option.office_name }}</option>
                    </select>
                </div>
                <div class="col-sm-1"></div>
            </div>
            <div class="form-group row">
                <div class="col-sm-1"></div>
                <div class="col-sm-10">
                    <label id="C116-01-04" class="modal-form-label" for="C116-01-05">部署</label>
                    <select id="C116-01-05" class="form-control" v-model="adjectiveDeptInput" value="">
                        <option></option>
                        <option v-for="option in dept_options_list" :key="option.dept_id" v-bind:title="option.dept_name" v-bind:value="option.dept_id">{{ option.dept_name }}</option>
                    </select>
                </div>
                <div class="col-sm-1"></div>
            </div>
            <div class="modal-footer d-flex justify-content-center">
                <button id="C116-01-06" type="button" class="btn btn-primary w-35" style="margin-right: 80px" data-dismiss="modal" v-on:click="selectClick" v-bind:disabled="!isEnableSelect">選択</button>
                <button id="C116-01-07" type="button" class="btn btn-danger w-35" data-dismiss="modal" v-on:click="cancelClick">キャンセル</button>
            </div>
        </div>
    </div>

</template>

<script>
export default {
    props: ['op1'],
    data() {
        return {
            adjectiveOfficeInput: [],
            adjectiveDeptInput: [],
            office_options_list: [],
            dept_options_list: [],
        }
    },
    methods: {
        //P116-04 選択処理
        selectClick() {
            this.op1.callback_select(this.officeInput, this.adjectiveInput);
        },
        //P116-02 キャンセル処理
        cancelClick() {
            this.op1.callback_cancel();
        },
        //バリデーション
        validateDate(){
            //未入力チェック
            if(this.officeInput === null)
            {
                return false;
            }
            //問題なし
            return true;
        }
    },
    mounted(){  //初期化処理
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
    }
};
</script>
