<template>
    <div id="C103-01" class="modal-content" v-if="clocking_in_out_id === 1" style="border: 3px solid rgba(0, 123, 255, 1); border-top: 0px">
        <div class="modal-body m103-body">
            <div class="row">
                <div id="C103-01-01" class="col text-center C103-01-01" style="background-color:#007bff">出勤</div>
            </div>
            <div class="row">
                <div id="C103-01-02" class="col text-center C103-01-02" style="background-color:#007bff">以下の内容で打刻されました</div>
            </div>
            <div class="row mt-2 mb-3 C103-01-dayandtime">
                <div>
                    <span id="C103-01-03" class="C103-01-03">{{dateStr}}</span>
                    <span id="C103-01-04" class="C103-01-04">{{timeStr}}</span>
                </div> 
            </div>
            <div class="row C103-01-employee">
                <div>
                    <span id="C103-01-05" class="C103-01-03">{{employee_code}}</span>
                    <span id="C103-01-06" class="C103-01-04">{{employee_name}}</span>
                </div>
            </div>
        </div>
        <div class="modal-footer d-flex justify-content-center">
            <button id="C103-01-07" type="button" class="btn btn-primary w-35" data-dismiss="modal" ref="close_button_1" @click="onClickCloseButton" @keyup.enter="onClickCloseButton">閉じる</button>
        </div>
    </div>
    <div id="C103-01" class="modal-content" v-else-if="clocking_in_out_id === 2" style="border: 3px solid rgba(220, 53, 69, 1); border-top: 0px">
        <div class="modal-body m103-body">
            <div class="row">
                <div id="C103-01-01" class="col text-center C103-01-01" style="background-color:#dc3545">退勤</div>
            </div>
            <div class="row">
                <div id="C103-01-02" class="col text-center C103-01-02" style="background-color:#dc3545">以下の内容で打刻されました</div>
            </div>
            <div class="row mt-2 mb-3 C103-01-dayandtime">
                <div>
                    <span id="C103-01-03" class="C103-01-03">{{dateStr}}</span>
                    <span id="C103-01-04" class="C103-01-04">{{timeStr}}</span>
                </div> 
            </div>
            <div class="row C103-01-employee">
                <div>
                    <span id="C103-01-05" class="C103-01-03">{{employee_code}}</span>
                    <span id="C103-01-06" class="C103-01-04">{{employee_name}}</span>
                </div>
            </div>
        </div>
        <div class="modal-footer d-flex justify-content-center">
            <button id="C103-01-07" type="button" class="btn btn-danger w-35" data-dismiss="modal" ref="close_button_2" @click="onClickCloseButton" @keyup.enter="onClickCloseButton">閉じる</button>
        </div>
    </div>
    <div id="C103-01" class="modal-content" v-else-if="clocking_in_out_id === 3" style="border: 3px solid rgba(255, 153, 7, 1); border-top: 0px">
        <div class="modal-body m103-body">
            <div class="row">
                <div id="C103-01-01" class="col text-center C103-01-01" style="background-color:#ff9907">外出</div>
            </div>
            <div class="row">
                <div id="C103-01-02" class="col text-center C103-01-02" style="background-color:#ff9907">以下の内容で打刻されました</div>
            </div>
            <div class="row mt-2 mb-3 C103-01-dayandtime">
                <div>
                    <span id="C103-01-03" class="C103-01-03">{{dateStr}}</span>
                    <span id="C103-01-04" class="C103-01-04">{{timeStr}}</span>
                </div> 
            </div>
            <div class="row C103-01-employee">
                <div>
                    <span id="C103-01-05" class="C103-01-03">{{employee_code}}</span>
                    <span id="C103-01-06" class="C103-01-04">{{employee_name}}</span>
                </div>
            </div>
        </div>
        <div class="modal-footer d-flex justify-content-center">
            <button id="C103-01-07" type="button" class="btn btn-orange w-35" data-dismiss="modal" ref="close_button_3" @click="onClickCloseButton" @keyup.enter="onClickCloseButton">閉じる</button>
        </div>
    </div>
    <div id="C103-01" class="modal-content" v-else-if="clocking_in_out_id === 4" style="border: 3px solid rgba(255, 153, 7, 1)">
        <div class="modal-body m103-body">
            <div class="row">
                <div id="C103-01-01" class="col text-center C103-01-01" style="color: #ff9907">戻り</div>
            </div>
            <div class="row">
                <div id="C103-01-02" class="col text-center C103-01-02" style="background-color:#ff9907">以下の内容で打刻されました</div>
            </div>
            <div class="row mt-2 mb-3 C103-01-dayandtime">
                <div>
                    <span id="C103-01-03" class="C103-01-03">{{dateStr}}</span>
                    <span id="C103-01-04" class="C103-01-04">{{timeStr}}</span>
                </div> 
            </div>
            <div class="row C103-01-employee">
                <div>
                    <span id="C103-01-05" class="C103-01-03">{{employee_code}}</span>
                    <span id="C103-01-06" class="C103-01-04">{{employee_name}}</span>
                </div>
            </div>
        </div>
        <div class="modal-footer d-flex justify-content-center">
            <button id="C103-01-07" type="button" class="btn btn-orange w-35" data-dismiss="modal" ref="close_button_4" @click="onClickCloseButton" @keyup.enter="onClickCloseButton">閉じる</button>
        </div>
    </div>
</template>

<script>
export default {
    name: "M103data",
    props: ['op1'],
    data() {
        return {
        };
    },
    mounted(){
        //DOM生成後、種別に合わせてCloseボタンにフォーカスを合わせる
        this.$nextTick(function(){
            //nextTickしたとしても遅延が必要・・・とりあえず500ms
            setTimeout(()=>{
                switch(this.op1.stamp_info.clocking_in_out_id)
                {
                    case 1:
                        this.$refs.close_button_1.focus();
                        break;
                    case 2:
                        this.$refs.close_button_2.focus();
                        break;
                    case 3:
                        this.$refs.close_button_3.focus();
                        break;
                    case 4:
                        this.$refs.close_button_4.focus();
                        break;
                }
            }, 500);
        });
    },
    methods:{
        onClickCloseButton(){
            this.op1.callback();
        }
    },
    computed:{
        timeStr: function(){
            return this.serialToTimeStr(this.op1.stamp_info.web_punched_time);
        },
        dateStr: function(){
            return this.serialToDateStr(this.op1.stamp_info.web_punched_date, 'YYYY/MM/DD(A)');
        },
        employee_code: function(){
            return this.op1.stamp_info.employee_code;
        },
        employee_name: function(){
            return this.op1.stamp_info.employee_name;
        },
        clocking_in_out_id: function(){
            return this.op1.stamp_info.clocking_in_out_id;
        }

    }

}
</script>
