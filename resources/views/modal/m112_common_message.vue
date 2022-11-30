<template>
    <div class="modal-content" id="C112-01">
        <div class="modal-header"></div>
        <div class="modal-body">

    　      <div class="row mb-3">
                <div class="col-sm-1"></div>
                <div class="col-sm-10" id="C112-01-01">{{op1.message}}</div>
                <div class="col-sm-1"></div>
            </div>

            <div v-if="op1.buttons.length === 1">
                <div class="modal-footer d-flex justify-content-center">
                    <button type="button" class="btn w-35" :class="op1.buttons[0].btnclass" data-dismiss="modal" v-on:click="button1Click" id="C112-01-02">{{op1.buttons[0].caption}}</button>
                </div>
            </div>
            <div v-else>
                <div class="modal-footer d-flex justify-content-around">
                    <button type="button" class="btn w-35" :class="op1.buttons[0].btnclass" data-dismiss="modal" v-on:click="button1Click" id="C112-01-02">{{op1.buttons[0].caption}}</button>
                    <button type="button" class="btn w-35" :class="op1.buttons[1].btnclass" data-dismiss="modal" v-on:click="button2Click" id="C112-01-03">{{op1.buttons[1].caption}}</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "M112data",
    props: ['op1'],
    methods: {
        button1Click: function() {
            this.op1.buttons[0].exec();
        },
        button2Click: function() {
            this.op1.buttons[1].exec();
        },
    }
};
</script>