<template>
    <div>
        <div class="row">
            <div class="px-2">
                <div id="C016-01-01-01" class="text-left">
                    対象者状態一覧
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-2 text-center">
                <card id="C016-01-01-02" title="未申請項目有り" :number="unappliedCnt.toString()" unit="名" :comment="unappliedComment" :blink="unappliedBlink"></card>
            </div>
            <div v-if="target_type === 1" class="col-2 text-center">
                <card id="C016-01-01-03" title="未承認項目有り" :number="unapprovedCnt.toString()" unit="名" :comment="unapprovedComment" :blink="unapprovedBlink"></card>
            </div>
            <div v-if="!session_data.is_production" class="col-2 text-center">
                <card id="C016-01-01-04" title="勤怠警告あり" :number="warningCnt.toString()" unit="名" :comment="warningComment" :blink="warningBlink"></card>
            </div>
            <div v-if="!session_data.is_production" class="col-2 text-center">
                <card id="C016-01-01-05" title="勤怠違反あり" :number="violation_cnt.toString()" unit="名" :comment="violationComment" :blink="violationBlink"></card>
            </div>
            <div v-if=false class="col-2 text-center">
                <card id="C016-01-01-06" title="要締め対応" :number="closeStateCnt.toString()" unit="名" :comment="closeStateComment" :blink="closeStateBlink"></card>
            </div>
        </div>
    </div>
</template>

<script>

import VTooltip from 'v-tooltip'
export default {
    name: "targetPersonStatusList",
    props:{
        target_type: Number, //承認対象者：1　代理対象者：2
        unapplied_cnt: Number,
        unapproved_cnt: Number,
        warning_cnt: Number,
        violation_cnt: Number,
        close_state_cnt: Number,
        session_data: Object,
    },
    data() {
        return {
            targetType: 0,
            unappliedCnt: 0,
            unapprovedCnt: 0,
            warningCnt: 0,
            violationCnt: 0,
            closeStateCnt: 0,
            unappliedComment: '',
            unapprovedComment: '',
            warningComment: '',
            violationComment: '',
            closeStateComment: '',
            unappliedBlink: false,
            unapprovedBlink: false,
            warningBlink: false,
            violationBlink: false,
            closeStateBlink: false,
        };
    },
    mounted(){
        Vue.use(VTooltip);
    },
    watch: {
        target_type:{ // 外からプロパティの中身が変更になったら実行される
            immediate: true,
            handler(value){
                this.targetType = value;
            }
        },
        unapplied_cnt: {
            immediate: true,
            handler(value) {
                this.unappliedCnt = value;
                if(value > 0){
                    this.unappliedComment = "申請・修正を行ってください！";
                    this.unappliedBlink = true;
                }
                else{
                    this.unappliedComment = "";
                    this.unappliedBlink = false;
                }
            }
        },
        unapproved_cnt:{
            immediate: true,
            handler(value){
                this.unapprovedCnt = value;
                if(value > 0){
                    this.unapprovedComment = "承認を行ってください！";
                    this.unapprovedBlink = true;
                }
                else{
                    this.unapprovedComment = "";
                    this.unapprovedBlink = false;
                }
            }
        },
        warning_cnt:{
            immediate: true,
            handler(value){
                this.warningCnt = value;
                if(value > 0){
                    this.warningComment = "労働・休暇状況を確認してください！";
                    this.warningBlink = true;
                }
                else{
                    this.warningComment = "";
                    this.warningBlink = false;
                }
            }
        },
        violation_cnt:{
            immediate: true,
            handler(value){
                this.violationCnt = value;
                if(value > 0){
                    this.violationComment = "労働・休暇状況を確認してください！";
                    this.violationBlink = true;
                }
                else{
                    this.violationComment = "";
                    this.violationBlink = false;
                }
            }
        },
        close_state_cnt:{
            immediate: true,
            handler(value){
                this.closeStateCnt = value;
                if(value > 0){
                    this.closeStateComment = "管理者締めを行ってください！";
                    this.closeStateBlink = true;
                }
                else{
                    this.closeStateComment = "";
                    this.closeStateBlink = false;
                }
            }
        },
    }
}
</script>