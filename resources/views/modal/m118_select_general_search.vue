<template>
    <div class="modal-content" id="C117-01">
        <div class="modal-body">
            <div id="C117-01-01" class="mb-3 d-flex justify-content-left">保存条件を選択してください</div>
            <div class="row mb-3">
                <div class="col-sm-6">
                    <div class="form-check">
                        <span>
                            <input class="form-check-input" type="radio" style="cursor: pointer;" id="radioShareClassCommon" value=0 v-model="shareClass"/>
                            <label class="form-check-label" for="radioShareClassCommon" style="cursor: pointer;">
                                共通用
                            </label>
                        </span>
                        <span style="margin-left: 80px;">
                            <input class="form-check-input" type="radio" style="cursor: pointer;" id="radioShareClassPersonal" value=1 v-model="shareClass"/>
                            <label class="form-check-label" for="radioShareClassPersonal" style="cursor: pointer;">
                                個人用
                            </label>
                        </span>
                    </div>
                    <div v-if="saveItemList.length !== 0">
                        <div class="table-white" style="margin-top: 20pt; font-size: 12pt; width: 465px;">
                            <div>
                                保存条件名
                            </div>
                            <select class="form-select" size="7" v-model="selectedSaveItem" style="width: 100%;">
                                <option v-for="item in saveItemList" :key="item.list_index" v-bind:value="item.list_index">{{item.save_name}}</option>
                            </select>
                        </div>
                    </div>
                    <div v-else style="background-color: #ffffff; font-size: 15pt; color: #000000; line-height: 46px; height: 150pt;">
                        <div class="table-white" style="margin-top: 20pt; width: 465px;">
                            表示項目がありません
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer d-flex justify-content-center">
                <button id="C117-01-09" type="button" class="btn btn-primary" style="width: 100px" @click="onClickSelect">選択</button>
                <button id="C117-01-10" type="button" class="btn btn-danger" style="width: 100px" @click="onClickDelete">削除</button>
                <button id="C117-01-11" type="button" class="btn btn-success" style="width: 100px" data-dismiss="modal" @click="onClickClose">閉じる</button>
            </div>
        </div>
    </div>

</template>

<script>

export default {
    props: ['op1'],
    data() {
        return {
            selectedSaveItem: null,
            shareClass: 0,
            personalList: [],
            commonList: [],
        }
    },
    methods: {
        //選択ボタンクリック
        onClickSelect(){
            //表示中であればコーション
            if(this.isSelectedCondition)
            {
                this.openModal("m112_common_message", "", {
                    message: '現在指定している条件がクリアされますがよろしいですか？',
                    buttons:[
                        {
                            exec : ()=>{
                                //選択条件セット
                                this.op1.onSelectTarget(this.selectedSaveItem);
                                //モーダル全閉じ
                                $('body').removeClass('modal-open');
                                this.cleanModal();
                                $('.modal-backdrop').remove();
                            },
                            caption : "OK",  //'OK','はい','いいえ','キャンセル','再試行','閉じる'
                            btnclass : "btn-success"
                        },
                        {
                            exec : ()=>{
                                //キャンセルは何もしない
                            },
                            caption : "キャンセル",  //'OK','はい','いいえ','キャンセル','再試行','閉じる'
                            btnclass : "btn-danger"
                        }
                    ],
                });
            }
            else
            {
                //選択条件セット
                this.op1.onSelectTarget(this.selectedSaveItem);
                //モーダル全閉じ
                $('body').removeClass('modal-open');
                this.cleanModal();
                $('.modal-backdrop').remove();
            }
        },
        //削除ボタンクリック
        onClickDelete(){
            this.openModal("m112_common_message", "", {
                message: '選択されている項目を削除してよろしいですか？',
                buttons:[
                    {
                        exec : ()=>{
                            this.op1.onDeleteTarget(this.selectedSaveItem);
                            //モーダル全閉じ
                            $('body').removeClass('modal-open');
                            this.cleanModal();
                            $('.modal-backdrop').remove();
                        },
                        caption : "OK",  //'OK','はい','いいえ','キャンセル','再試行','閉じる'
                        btnclass : "btn-success"
                    },
                    {
                        exec : ()=>{
                            //キャンセルは何もしない
                        },
                        caption : "キャンセル",  //'OK','はい','いいえ','キャンセル','再試行','閉じる'
                        btnclass : "btn-danger"
                    }
                ],
            });
        },
        //閉じるボタンクリック
        onClickClose(){
            $('body').removeClass('modal-open');
            this.cleanModal();
            $('.modal-backdrop').remove();
        }
    },
    computed: {
        saveItemList: function(){
            this.selectedSaveItem = null;
            if(this.shareClass == 0)
            {
                return this.commonList;
            }
            else if(this.shareClass == 1)
            {
                return this.personalList;
            }
            return [];
        }
    },
    mounted() {
        //検索条件データ取得して保持
        axios.get('getGenralSearchSaveList')
        .then(response => {
            if(response.data.result)
            {
                const commonListRes = response.data.values.commonList;
                const personalListRes = response.data.values.personalList;
                //共通用
                for(let i = 0; i < commonListRes.length; i++)
                {
                    this.commonList.push({
                        list_index: commonListRes[i].general_search_save_id,
                        save_name: commonListRes[i].general_search_save_name,
                    });
                }
                //個人用
                for(let i = 0; i < personalListRes.length; i++)
                {
                    this.personalList.push({
                        list_index: personalListRes[i].general_search_save_id,
                        save_name: personalListRes[i].general_search_save_name,
                    });
                }
            }
            else
            {
                alert('データの取得に失敗しました。');
            }
        });
    },
    watch: {
    },
}
</script>