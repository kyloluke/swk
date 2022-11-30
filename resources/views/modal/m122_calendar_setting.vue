<template>
    <div class="modal-content" id="C122-01" style="width: 500px">
        <div class="modal-body">
    　      <div id="C122-01-01" class="mb-3 d-flex justify-content-center">出休設定変更</div>
            <div class="row d-flex justify-content-center">
                <div class="form-group" style="width: 160px;">
                    <label id="C122-01-02" style="cursor: pointer;">対象日</label>
                </div>
                <div class="form-group" style="width: 160px;">
                    <label id="C122-01-03" style="cursor: pointer;">{{selectedDate}}</label>
                </div>
            </div>
            <div class="row d-flex justify-content-center">
                <div class="form-group" style="width: 160px;">
                    <label id="C122-01-04" style="cursor: pointer;">出休区分</label>
                </div>
                <div class="form-group" style="width: 160px;">
                    <select id="C122-01-05" class="form-control" v-model="work_holiday_id">
                        <option v-for="work_holiday in work_holiday_list" :key="work_holiday.work_holiday_id" v-bind:value="work_holiday.work_holiday_id">{{ work_holiday.work_holiday_name }}</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer d-flex justify-content-center">
                <button id="C122-01-06" type="button" class="btn btn-primary w-35" data-dismiss="modal" style="margin-right: 40px" v-on:click="updateClick">登録</button>
                <button id="C122-01-07" type="button" class="btn btn-danger w-35" data-dismiss="modal" v-on:click="cancelClick">キャンセル</button>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: ['op1'],
    data() {
        return {
            selectedDate: "",
            work_holiday_list: [],
            work_holiday_id: 0,
        }
    },
    methods: {
        updateClick() {
            if(this.work_holiday_id !== 0){
                this.op1.callback_regist(this.work_holiday_id);
            }
        },
        //キャンセル処理
        cancelClick() {
            this.op1.callback_cancel();
        },
    },
    mounted() {
        this.work_holiday_list =  this.getMasterData().work_holiday;
        if(this.op1.calendar_date !== null)
        {
            this.selectedDate = this.op1.calendar_date;
        }
        if(this.op1.work_holiday_id !== 0)
        {
            this.work_holiday_id = this.op1.work_holiday_id;
        }
    },
    computed:{

    }
};
</script>
