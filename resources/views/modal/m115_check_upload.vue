<template>
    <div class="modal-content" id="C115-01">
        <div class="modal-body">
    　      <div id="C115-01-01" class="mb-3 d-flex justify-content-center">以下の内容で一括アップロードを行います。</div>
            <div class="row">
                <div class="col-sm-3"></div>
                <div id="C115-01-02" class="col-sm-3 pl-0 pr-0">新規登録対象：</div>
                <div id="C115-01-03" class="col-sm-3" style="text-align: right">{{op1.signupdata}}件</div>
                <div class="col-sm-3"></div>
            </div>
            <div class="row">
                <div class="col-sm-3"></div>
                <div id="C115-01-04" class="col-sm-3 pl-0 pr-0">変更対象：</div>
                <div id="C115-01-05" class="col-sm-3" style="text-align: right">{{op1.changedata}}件</div>
                <div class="col-sm-3"></div>
            </div>
            <div class="row">
                <div class="col-sm-3"></div>
                <div id="C115-01-06" class="col-sm-3 pl-0 pr-0">削除対象：</div>
                <div id="C115-01-07" class="col-sm-3" style="text-align: right">{{op1.deletedata}}件</div>
                <div class="col-sm-3"></div>
            </div>
    　      <div id="C115-01-08" class="mt-3 mb-1 d-flex justify-content-center">一度アップロードを行うと修正ができません。よろしいですか？</div>
            <div class="modal-footer d-flex justify-content-center">
                <button id="C115-01-09" type="button" class="btn btn-primary w-35" data-dismiss="modal" style="margin-right: 80px" v-on:click="okClick">OK</button>
                <button id="C115-01-10" type="button" class="btn btn-danger w-35" data-dismiss="modal" v-on:click="cancelClick">キャンセル</button>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: ['op1'],
    methods: {
        //P115-04 アップロード処理
        okClick() {
            this.op1.buttons[0].exec();
        },
        //P115-02 キャンセル処理
        cancelClick() {
            this.op1.buttons[1].exec();
        }
    },
};
</script>
