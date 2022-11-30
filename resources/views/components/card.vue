<template>
    <div class="card shadow h-100" :class="$style[backgroundClass]">
        <div class="card-body">
            <div class="card-title text-left" :class="$style[fontColorClass]">{{title}}</div>
            <div class="card-text" style="color:#000000;font-size:15pt">{{text}}</div>
            <div class="card-text d-inline-block" style="color:#000000;font-size:40pt">{{time}}</div>
            <div class="card-text d-inline-block" style="color:#000000;font-size:15pt">{{text2}}</div>
            <div class="card-text d-inline-block" style="color:#000000;font-size:40pt">{{number}}</div>
            <div class="card-text d-inline-block" style="color:#000000;font-size:16pt">{{unit}}</div>
            <div class="card-text" style="color:#000000;font-size:18pt">{{date}}</div>
            <div class="card-text" :class="blinkClass" style="color:#000000;font-size:10.5pt">{{comment}}</div>
            <div class="card-text" style="font-size:12pt">{{comment2}}</div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        title: String,
        text: String,
        text2: String,
        number: String,
        unit: String,
        time: String,
        date: String,
        comment: String,
        comment2: String,
        blink: {type: Boolean, default: false},
        background_type: {type: Number, default: 1}
    },
    data(){
        return {
            backgroundType: 1,
        };
    },
    computed:{
        backgroundClass: function() {
            switch(this.backgroundType)
            {
                case 1: //本人入力
                    return "card_background_blue";
                case 2: //代理入力
                    return "card_background_orange";
                case 3: //事業所・総務
                    return "card_background_green";
            }
            //未指定は青
            return "card_background_blue";
        },
        fontColorClass:function(){
            switch(this.backgroundType)
            {
                case 1: //本人入力
                    return "card_fontcolor_blue";
                case 2: //代理入力
                    return "card_fontcolor_orange";
                case 3: //事業所・総務
                    return "card_fontcolor_green";
            }
            //未指定は青
            return "card_background_blue";
        },
        blinkClass:function(){
            return this.blink ? "blinking" : "";
        }
    },
    watch: {
        background_type: { // 外からプロパティの中身が変更になったら実行される
            immediate: true,
            handler(value) {
                this.backgroundType = value;
            }
        }
    }
}
</script>
<style module> 
.card_background_blue{
    background-color: #BCD2EE !important;
}
.card_background_orange{
    background-color: #F8CBAD !important;
}
.card_background_green{
    background-color: #C5E0B4 !important;
}
.card_fontcolor_blue{
    color: #27408B !important;
}
.card_fontcolor_orange{
    color: #C55A11 !important;
}
.card_fontcolor_green{
    color: #385723 !important;
}

</style>
