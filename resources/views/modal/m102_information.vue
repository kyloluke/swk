<template>
    <div class="modal-content" id="C102-01">
        <div class="modal-body">

            <div class="card mb-3" style="height:350px">
                <div class="card-header C102-card-header card-bg-white" id="C102-01-01">
                    <ul style="list-style: none; padding-left: 0; margin-bottom: 0;">
                        <li><span style="margin-right: 0px;">{{op1.new}}</span><span>{{op1.subject}}</span></li>
                    </ul>
                </div>
                <textarea style="resize:none" readonly rows="13" class="form-control" v-model="op1.content"/>
            </div>
            <div>
                <label style="color: #777777;">投稿日時</label>
                <label style="color: #777777; margin-left: 20px;">{{op1.date}}</label>
            </div>
            <div>
                <label style="color: #777777;">掲載期間</label>
                <label style="color: #777777; margin-left: 20px;">{{op1.term}}</label>
            </div>
            <div class="modal-footer d-flex justify-content-center" style="padding-bottom: 0">
                <button type="button" class="btn btn-success w-50" data-dismiss="modal" id="C102-01-05">閉じる</button>
            </div>

        </div>
    </div>
</template>

<script>
export default {
    name: "M102data",
    props: ['op1'],
    data() {
        const perPage = 1;
        return {
        };
    },
    computed: {
    },
    methods: {
    },
    mounted() {
    }
};
</script>