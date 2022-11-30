<template>
    <div style="width: 72px; display: flex; border: 1px solid #ced4da; border-radius: 0.25rem">
        <div>
            <input
                type="number"
                min="0"
                max="99"
                class="form-control form-control-sm p-0 no-spin"
                style="text-align: center; border:none; font-size: 14px;"
                @input="onInputChagedHoure"
                @focus="onFocusInputHour"
                v-model="input_hour"
                v-bind:disabled="!isEnableInput"
                ref="input_hour"
            />
        </div>
        <div :class="$style[inputColorClass]" style="text-align: center; padding: 3px 0 0 0;">
            :
        </div>
        <div>
            <input 
                type="number"
                min="0"
                max="59"
                class="form-control form-control-sm p-0 no-spin"
                style="text-align: center; border:none; font-size: 14px;"
                @input="onInputChagedMinute"
                @focus="onFocusInputMinute"
                v-model="input_minute"
                v-bind:disabled="!isEnableInput"
                ref="input_minute"
            />
        </div>
    </div>
</template>

<script>
export default {
    name: "inputTypeTime",
    props: {
        timeSerial: Number,
        isEnableInput: Boolean,
    },
    data() {
        return {
            input_hour: "",
            input_minute: "",
            day_count: 0,
        };
    },
    methods:{
        onInputChagedHoure: function(){
            if(this.input_hour.length == 3 && this.input_hour.slice(0, 2) == "23")
            {
                this.input_hour = this.input_hour.slice(2, 3);
            }
            else
            {
                this.input_hour = 2 < this.input_hour.length ? this.input_hour.slice(1, 3) : this.input_hour;
            }
            if(23 < this.input_hour)
            {
                this.input_hour = 23;
            }
            this.$emit("onInput", this.timeSerialSum);
        },
        onInputChagedMinute: function(){
            if(this.input_minute.length == 3 && this.input_minute.slice(0, 2) == "59")
            {
                this.input_minute = this.input_minute.slice(2, 3);
            }
            else
            {
                this.input_minute = 2 < this.input_minute.length ? this.input_minute.slice(1, 3) : this.input_minute;
            }
            if(59 < this.input_minute)
            {
                this.input_minute = 59;
            }
            this.$emit("onInput", this.timeSerialSum);
        },
        onFocusInputHour: function(){
            this.$refs.input_hour.select();
        },
        onFocusInputMinute: function(){
            this.$refs.input_minute.select();
        },
    },
    computed:{
        timeSerialSum(){
            if(this.input_hour == "" || this.input_minute == "")
            {
                return null;
            }
            return Number(this.input_hour) * 60 + Number(this.input_minute) + this.day_count * 60 * 24;
        },
        inputColorClass:function(){
            return this.isEnableInput ? 'input_color_enable' : 'input_color_disable'
        },
    },
    watch: {
        input_hour: {
            handler(value){
                if(2 <= value.length)
                {
                    this.$refs.input_minute.focus();
                }
            }
        },
        timeSerial: {
            immediate: true,
            handler(value){
                if(value != null && 0 <= value)
                {
                    this.day_count = Math.floor(value / (24 * 60));
                    const hourStr = this.serialToHourTimeStr(value - this.day_count * 24 * 60);
                    const minuteStr = this.serialToMinuteTimeStr(value - this.day_count * 24 * 60);
                    if(this.input_hour === "" || Number(hourStr) !== Number(this.input_hour))
                    {
                        this.input_hour = hourStr;
                    }
                    if(this.input_minute === "" || Number(minuteStr) !== Number(this.input_minute))
                    {
                        this.input_minute = minuteStr;
                    }
                }
                else
                {
                    this.input_hour = "";
                    this.input_minute = "";
                }
                this.$emit("onInput", this.timeSerialSum);
            }
        }
    }
}
</script>

<style module> 
.input_color_enable{
    background-color: #fcfcfc !important;
}
.input_color_disable{
    background-color: #e9ecef !important;
}
</style>