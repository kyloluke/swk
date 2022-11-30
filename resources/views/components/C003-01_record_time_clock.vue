<template>
    <div>
        <div style="text-align:center;">
            <div id="C003-01-01" style="color: black;font-size:28pt;min-height:50pt;" v-html="display_date"></div>
        </div> 
        <div style="text-align:center;">
            <div id="C003-01-02" style="color: black;font-size:80pt;min-height:150pt;" v-html="display_time"></div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
    },
    data() {
        return {
            display_date: "",
            display_time: ""
        };
    },
    mounted() {
        let self = this;
        let count = 0;
        setInterval(function() {
            //更新処理
            self.display_date = self.nowDate();
            self.display_time = self.nowTime();
            //1分に1回認証切れチェック
            if(count == 0)
            {
                axios.get('/keepAlive')
                .then((response)=>{
                }).catch((error)=>{
                    //エラーの時は自身にリダイレクト
                    document.location.href = "/swk"; 
                });
                count = 60;
            }
            count--;
        },1000);
    },
    methods: {
        nowDate() {
            var date_str = ["(日)","(月)","(火)","(水)","(木)","(金)","(土)"];
            var date = new Date();
            return "" + date.toLocaleDateString() + " " + date_str[date.getDay()];
        },
        nowTime() {
            var date = new Date();
            return "" + date.toLocaleTimeString();
        }
    }
}
</script>