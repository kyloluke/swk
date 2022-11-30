const { data } = require("jquery");

const COEFFICIENT = 24 * 60 * 60 * 1000; //日数とミリ秒を変換する係数
const DATES_OFFSET = 70 * 365 + 17 + 1 + 1; //「1900/1/0」～「1970/1/1」 (日数)
const MILLIS_DIFFERENCE = 9 * 60 * 60 * 1000; //UTCとJSTの時差 (ミリ秒)
const DAY_OF_WEEK_STR = [ "日", "月", "火", "水", "木", "金", "土" ];


Vue.mixin({
    methods: {
        /**
         * 今日の日付をシリアル値で取得
         */
        todaySerial: function(){
            return Math.floor((Date.now() + MILLIS_DIFFERENCE) / COEFFICIENT) + DATES_OFFSET;
        },
        /**
         * 日付文字列からシリアル値を取得
         * @param {strign} date_str 日付文字列
         * @returns 1900/1/1を1としたシリアル値
         */
        dateStrToSerial: function(date_str){
            if(typeof(date_str) == "string")
            {
                //yyyy-mm-dd型の時はyyyy/mm/dd型へ変更
                date_str = date_str.replace(/-/g, '/');
            }
            //date型（UNIXタイムスタンプ）へ変換
            const js_date = Date.parse(date_str);
            //変換エラー
            if(!js_date)
            {
                return null;
            }
            //1900/1/1を1としたシリアル値へ変換
            return (js_date + MILLIS_DIFFERENCE) / COEFFICIENT + DATES_OFFSET;
        },

        /**
         * シリアル値から時間文字列を取得する
         * @param {number} time_serial 
         * @param {bool} isReturnEmpty 0:00の時、空文字を返すかどうか
         * @returns 時間文字列2:30 => 2.50
         */
        serialToHoursNumberStr: function(time_serial, isReturnEmpty = false){
            if(isNaN(time_serial))
            {
                //NaN対策
                time_serial = 0;
            }
            if(isReturnEmpty && time_serial === 0)
            {
                return "";
            }
            const hour = (Math.floor(time_serial / 60));
            const minute = ("00" + Math.round((time_serial - hour * 60) / 60 * 100)).slice(-2);
            return hour + "." + minute;
        },
        /**
         * 日付文字列と締め区分から6桁の年月値を取得
         * @param {strign} date_str 日付文字列
         * @returns 6桁の年月値 YYYYMM
         */
         dateStrToyearMonthNumber: function(date_str, close_date){
            let date_serial = this.dateStrToSerial(date_str);
            let year_month = this.serialToDateStr(date_serial, "YYYYMM");
            let date = Number(this.serialToDateStr(date_serial, "D"));
    
            //15日締めの時に日付が15日以前だったら前の月が対象年月
            if(close_date != 0){
                if(date <= close_date){
                    let year = Number(this.serialToDateStr(date_serial, "YYYY"));
                    let month = Number(this.serialToDateStr(date_serial, "MM"));
                    //1月だったら前の年の12月
                    if(month == 1){
                        month = 12;
                        year -= 1;
                    }
                    else{
                        month -= 1;
                    }
                    year_month = String(year) + String(('0' + month).slice(-2));
                }                
            }
            return year_month;
        },

        /**
         * シリアル値とフォーマットから日付文字列を取得する
         * @param {number} date_serial 1900/1/1を1としたシリアル値
         * @param {string} format フォーマット（例）YYYY/MM/DD (A)　→2021/03/04 (水)
         * @returns 
         */
        serialToDateStr: function(date_serial, format){
            if(!format)
            {
                format = "YYYY/MM/DD";
            }
            const js_date = new Date((date_serial - DATES_OFFSET) * COEFFICIENT - MILLIS_DIFFERENCE);
            format = format.replace(/YYYY/g, js_date.getFullYear());
            format = format.replace(/MM/g, ('00' + String(js_date.getMonth() + 1)).slice( -2 ));
            format = format.replace(/M/g, String(js_date.getMonth() + 1));
            format = format.replace(/DD/g, ('00' + String(js_date.getDate())).slice( -2 ));
            format = format.replace(/D/g, String(js_date.getDate()));
            format = format.replace(/A/g, DAY_OF_WEEK_STR[js_date.getDay()]);
            return format;
        },

        /**
         * シリアル値とフォーマットから日付文字列を取得する
         * @param {number} date_serial 1900/1/1を1としたシリアル値
         * @returns 
         */
        serialToWeekNumber: function(date_serial){

            let format = "A";
            const js_date = new Date((date_serial - DATES_OFFSET) * COEFFICIENT - MILLIS_DIFFERENCE);
            format = Number(format.replace(/A/g,js_date.getDay()));
            return format;
        },

        /**
         * 表示切替日チェックして取得
         * @param {number} display_switch_date 表示切替日
         * @param {number} display_switch_date 日付のシリアル値、デフォルト値は当日
         * @returns チェックして、計算して取得された表示切替日
         */
        getDisplaySwitchDate: function(display_switch_date, specifiedDateSerial)
        {
            let dateSerial = specifiedDateSerial !== undefined ? specifiedDateSerial : this.todaySerial();
            let checked_display_switch_date = display_switch_date;
            let yearmonth = Number(this.serialToDateStr(dateSerial, "YYYYMM"));
            const date = Number(this.serialToDateStr(dateSerial, "DD"));
            const month = Number(this.serialToDateStr(dateSerial, "MM"));
            const year = Number(this.serialToDateStr(dateSerial, "YYYY"));
            const dt = new Date(year,month,display_switch_date);
            if(dt.getFullYear() != year || dt.getMonth() != month || dt.getDate() != checked_display_switch_date){
                checked_display_switch_date = 1;
            }
            if(checked_display_switch_date > date){
                yearmonth = Number(this.calcYearMonth(yearmonth, -1,"YYYYMM"));
            }
            return yearmonth;
        },
        /**
         * シリアル値から時間を取得する
         * @param {number} date_serial 1900/1/1を1としたシリアル値
         * @returns 
         */
        displayHours: function(data_serial){
            return this.timeUnitInput == 0 ? this.serialToHoursStr(data_serial) : this.serialToHoursNumberStr(data_serial);
        },
        /**
         * シリアル値から時間文字列を取得する
         * @param {number} time_serial 
         * @param {bool} isZeroPadding 0の時、ゼロパディングを行うかどうか
         * @returns 時間文字列HH:MM、 H:MM
         */
        serialToTimeStr: function(time_serial, isZeroPadding = true){
            if(isNaN(time_serial))
            {
                //NaN対策
                time_serial = 0;
            }
            if(time_serial < 0 || time_serial === null)
            {
                return "";
            }
            time_serial = time_serial >= 1440 ? time_serial - (1440 * Math.floor(time_serial / 1440)) : time_serial;
            const hour = isZeroPadding ? ("00" + Math.floor(time_serial / 60)).slice(-2) : Math.floor(time_serial / 60);
            const minute = ("00" + (time_serial - hour * 60)).slice(-2);
            return hour + ":" + minute;
        },
        /**
         * シリアル値から「時」の文字列を取得する
         * @param {number} time_serial 
         * @returns 時間文字列HH
         * @param {bool} isReturnEmpty 0:00の時、空文字を返すかどうか
         */
         serialToHourTimeStr: function(time_serial, isReturnEmpty = false){
            if(time_serial === null)
            {
                return "";
            }
            return ("00" + Math.floor(time_serial / 60)).slice(-2);
        },
        /**
         * シリアル値から「分」の文字列を取得する
         * @param {number} time_serial 
         * @returns 時間文字列MM
         * @param {bool} isReturnEmpty 0:00の時、空文字を返すかどうか
         */
         serialToMinuteTimeStr: function(time_serial, isReturnEmpty = false){
            if(time_serial === null)
            {
                return "";
            }
            return ("00" + (time_serial % 60)).slice(-2);
        },
        /**
         * シリアル値から時間文字列を取得する
         * @param {number} time_serial 
         * @returns 時間文字列H:MM
         */
         serialToHoursStr: function(time_serial){
            if(isNaN(time_serial))
            {
                //NaN対策
                time_serial = 0;
            }
            if(time_serial < 0 || time_serial === null)
            {
                return "";
            }
            const hour = Math.floor(time_serial / 60)
            const minute = ("00" + (time_serial - hour * 60)).slice(-2);
            return hour + ":" + minute;
        },
        /**
         * 時間文字列HH:MM形式からシリアル値を取得する
         * @param {string} time_str HH:MM 形式
         * @returns 時間シリアル値
         */
        timeStrToSerial: function(time_str){
            //形式違いは-1リターン
            if(time_str.match(/^((0?[0-9]|1[0-9])|2[0-3]):([0-5][0-9])$/) === null)
            {
                return -1;
            }
            //コロンで分割してシリアル値換算
            const time_array = time_str.split(":");
            return Number(time_array[0]) * 60 + Number(time_array[1]);
        },
        /**
         * 時の文字列HH形式からシリアル値を取得する
         * @param {string} time_str MM 形式
         * @returns 時間シリアル値
         */
         hourTimeStrToSerial: function(time_str){
            if(time_str === null)
            {
                return "";
            }
            return Number(time_str) * 60;
        },
        /**
         * 分の文字列MM形式からシリアル値を取得する
         * @param {string} time_str MM 形式
         * @returns 時間シリアル値
         */
         minuteTimeStrToSerial: function(time_str){
            if(time_str === null)
            {
                return "";
            }
            return Number(time_str);
        },
        /**
         * 指定のメニューを開く
         * ページ移動時の一番左ページ遷移に使用を想定
         * @param {string} ref_id 
        */
        showTopMenu: function(ref_id){
            $('#pills-tab a[href="' + ref_id + '"]').tab('show');
        },
        /**
         * 指定のメニューを開く
         * ページ移動時の左側メニューの遷移
         * @param {string} ref_id 
        */
         vshowTopMenu: function(ref_id){
            $('#v-pills-tab a[href="' + ref_id + '"]').tab('show');
        },

        /**
         * 指定した子ページの初期化
         * @param {string} refs_name 
        */
        initializePage: function(refs_name){
            this.$refs[refs_name].initialize();
        },
        /**
         * 6桁の年月値から、YYYY年MM月形式の文字列を返す
         * @param {Number} yearMonthNumber 
         * @returns 
         */
        yearMonthNumberToText: function(yearMonthNumber){
            const yearMonthText = String(yearMonthNumber);
            if(yearMonthText.length < 6)
            {
                return "";
            }
            return yearMonthText.slice(0, 4) + "年" + yearMonthText.slice(4, 6) + "月";
        },
        /**
         * 6桁の年月値に、additionalMonthを足した年月を返す。
         * マイナス値も指定可能
         * @param {Number} yearMonthNumber 
         * @param {Number} additionalMonth 
         * @param {String} format
         * @returns 
         */
        calcYearMonth: function(yearMonthNumber, additionalMonth, format){
            //念のため数値変換
            yearMonthNumber = Number(yearMonthNumber);
            //6文字の数値以外は空文字返す
            const yearMonthText = String(yearMonthNumber);
            if(yearMonthText.length < 6)
            {
                return "";
            }
            const yearVal = ~~(yearMonthNumber / 100);
            const sumMonth = yearVal * 12 + yearMonthNumber - yearVal * 100;
            const addedSumMonth = sumMonth + additionalMonth;
            const addedYear = ~~((addedSumMonth - 1) / 12);
            const addedMonth = (addedSumMonth - 1) % 12 + 1;
            if(format)
            {
                //フォーマット指定の場合、YYYYとMMにそれぞれ置き換える
                format = format.replace(/YYYY/g, addedYear);
                format = format.replace(/MM/g, ('00' + addedMonth).slice( -2 ));
                return format;
            }
            else
            {
                return addedYear * 100 + addedMonth; 
            }
        },
        /**
         * 6桁の年月値に該当する年間の年月リストを返す。対象年月が含まれる7月～翌6月
         * @param {Number} yearMonthNumber 
         * @returns 
         */
        getYearMonthListFromMonth: function(yearMonthNumber,firstMonth){
            let yearMonthList = [];
            //念のため数値変換
            yearMonthNumber = Number(yearMonthNumber);
            firstMonth = Number(firstMonth);
            //6文字の数値以外は空文字返す
            const yearMonthText = String(yearMonthNumber);
            if(yearMonthText.length < 6)
            {
                return "";
            }
            if(firstMonth < 0 || firstMonth > 12){
                return "";
            }
            const yearVal = ~~(yearMonthNumber / 100);
            const monthVal = yearMonthNumber - yearVal * 100;
            if(monthVal > firstMonth - 1){
                for(let i = 0; i < 12; i++){
                    yearMonthList.push(this.calcYearMonth(yearVal * 100 + firstMonth,i));
                }               
            }else{
                for(let i = 0; i < 12; i++){
                    yearMonthList.push(this.calcYearMonth(yearVal * 100 - 100 + firstMonth,i));
                }  
            }
            return yearMonthList; 
        },
        /**
         * 検証結果が問題なければシリアル値を取得する。日付ではない場合、-1を返す
         * @param {string} date 
         * @returns 
         */
        checkDate: function(date){
            if(date === "" || date == null){
                return -1;
            }
            if(!isNaN(date)){
                if(date.length == 8){
                    return this.dateStrToSerial(date.slice(0, 4) + '/' + date.slice(4, 6) + '/' + date.slice(6, 8));
                }else{
                    return -1; 
                }
            }else{
                if((date.length == 8) && ((date.slice(4, 5) == '-' && date.slice(6, 7) == '-') || (date.slice(4, 5) == '/' && date.slice(6, 7) == '/'))){
                    if(!isNaN(date.slice(0, 4)) && !isNaN(date.slice(5, 6)) && !isNaN(date.slice(7, 8))){
                        return this.dateStrToSerial(date.slice(0, 4) + '/0' + date.slice(5, 6) + '/0' + date.slice(7, 8));
                    }else{
                        return -1;
                    }
                }else if((date.length == 9) && ((date.slice(4, 5) == '-' && date.slice(6, 7) == '-') || (date.slice(4, 5) == '/' && date.slice(6, 7) == '/'))){
                    if(!isNaN(date.slice(0, 4)) && !isNaN(date.slice(5, 6)) && !isNaN(date.slice(7, 9))){
                        return this.dateStrToSerial(date.slice(0, 4) + '/0' + date.slice(5, 6) + '/' + date.slice(7, 9));
                    }else{
                        return -1;
                    }
                }else if((date.length == 9) && ((date.slice(4, 5) == '-' && date.slice(7, 8) == '-') || (date.slice(4, 5) == '/' && date.slice(7, 8) == '/'))){
                    if(!isNaN(date.slice(0, 4)) && !isNaN(date.slice(5, 7)) && !isNaN(date.slice(8, 9))){
                        return this.dateStrToSerial(date.slice(0, 4) + '/' + date.slice(5, 7) + '/0' + date.slice(8, 9));
                    }else{
                        return -1;
                    }
                }else if((date.length == 10) && ((date.slice(4, 5) == '-' && date.slice(7, 8) == '-') || (date.slice(4, 5) == '/' && date.slice(7, 8) == '/'))){
                    if(!isNaN(date.slice(0, 4)) && !isNaN(date.slice(5, 7)) && !isNaN(date.slice(8, 10))){
                        return this.dateStrToSerial(date);
                    }else{
                        return -1;
                    }
                }else{
                    return -1; 
                }
            }
        },
        /**
         * 検証結果が問題なければシリアル値を取得する。時間ではない場合、-1を返す
         * @param {string} time 
         * @returns 
         */
        checkTime: function(time){
            if(!isNaN(time)){
                if(time.length == 4){
                    return this.timeStrToSerial(time.slice(0, 2) + ':' + time.slice(2, 4));
                }else{
                    return -1; 
                }
            }else{
                if(time.length == 3 && time.slice(1, 2) == ':'){
                    if(!isNaN(time.slice(0, 1)) && !isNaN(time.slice(2, 3))){
                        return this.timeStrToSerial('0' + time.slice(0, 2) + '0' + time.slice(2, 3));
                    }else{
                        return -1;
                    }
                }else if(time.length == 4 && time.slice(1, 2) == ':'){
                    if(!isNaN(time.slice(0, 1)) && !isNaN(time.slice(2, 4))){
                        return this.timeStrToSerial('0' + time.slice(0, 4));
                    }else{
                        return -1;
                    }
                }else if(time.length == 4 && time.slice(2, 3) == ':'){
                    if(!isNaN(time.slice(0, 2)) && !isNaN(time.slice(3, 4))){
                        return this.timeStrToSerial(time.slice(0, 3) + '0' + time.slice(3, 4));
                    }else{
                        return -1;
                    }
                }else if(time.length == 5 && time.slice(2, 3) == ':'){
                    if(!isNaN(time.slice(0, 2)) && !isNaN(time.slice(3, 5))){
                        return this.timeStrToSerial(time);
                    }else{
                        return -1;
                    }
                }else{
                    return -1; 
                }
            }
        },
        /**
         * 日付文字列の正当性チェック
         * 全角半角両対応
         * @param {string} dateStr 
         * @returns 正当だったらtrue
         */
        checkDateStr: function(dateStr)
        {
            let y = -1;
            let m = -1;
            let d = -1;
            //全角スラッシュを置き換える
            dateStr = dateStr.replace(/[／]/g, "/");
            //全角数値を半角数値に置き換える
            dateStr = dateStr.replace(/[０-９]/g, (s)=>{return String.fromCharCode(s.charCodeAt(0) - 0xFEE0);});
            //yyyy/m/dパターン
            if(dateStr.match(/^\d{4}\/\d{1,2}\/\d{1,2}$/))
            {
                y = Number(dateStr.split("/")[0]);
                m = Number(dateStr.split("/")[1]) - 1;
                d = Number(dateStr.split("/")[2]);
            }
            //yyyy-m-dパターン
            if(dateStr.match(/^\d{4}\-\d{1,2}\-\d{1,2}$/))
            {
                y = Number(dateStr.split("-")[0]);
                m = Number(dateStr.split("-")[1]) - 1;
                d = Number(dateStr.split("-")[2]);
            }
            //yyyymmddパターン
            if(dateStr.match(/^\d{8}$/))
            {
                y = Number(dateStr.slice(0, 4));
                m = Number(dateStr.slice(4, 6)) - 1;
                d = Number(dateStr.slice(6, 8));
            }
            //パターンアンマッチ
            if(y < 0 || m < 0 || d < 0)
            {
                return false;
            }
            //日付の正当性
            var date = new Date(y,m,d);
            if(date.getFullYear() != y || date.getMonth() != m || date.getDate() != d){
                return false;
            }
            //正当な日付文字列
            return true;
        },
        /**
         * 日付文字列をyyyy-mm-ddの形に整形して返す
         * 全角半角両対応
         * @param {string} dateStr 
         * @returns 正当な日付文字列であれば、yyyy-mm-ddの形、不正であればnull
         */
        getValidDateStr: function(dateStr)
        {
            // 形式チェック
            if(!this.checkDateStr(dateStr))
            {
                return null;
            }
            let y = 0;
            let m = 0;
            let d = 0;
            
            //全角スラッシュを置き換える
            dateStr = dateStr.replace(/[／]/g, "/");
            //全角数値を半角数値に置き換える
            dateStr = dateStr.replace(/[０-９]/g, (s)=>{return String.fromCharCode(s.charCodeAt(0) - 0xFEE0);});

            // yyyy/m/dパターン
            if(dateStr.match(/^\d{4}\/\d{1,2}\/\d{1,2}$/))
            {
                y = Number(dateStr.split("/")[0]);
                m = Number(dateStr.split("/")[1]);
                d = Number(dateStr.split("/")[2]);
            }
            // yyyy-m-dパターン
            if(dateStr.match(/^\d{4}\-\d{1,2}\-\d{1,2}$/))
            {
                y = Number(dateStr.split("-")[0]);
                m = Number(dateStr.split("-")[1]);
                d = Number(dateStr.split("-")[2]);
            }
            // yyyymmddパターン
            if(dateStr.match(/^\d{8}$/))
            {
                y = Number(dateStr.slice(0, 4));
                m = Number(dateStr.slice(4, 6));
                d = Number(dateStr.slice(6, 8));
            }
            // yyyy-mm-ddの形で返す
            return y + "-" + ("0" + m).slice(-2) + "-" + ("0" + d).slice(-2);
        },
        /**
         * 時間の正当性チェック
         * 全角半角両対応
         * @param {string}} timeStr 
         */
        checkTimeStr: function(timeStr)
        {
            let h = -1;
            let m = -1;
            //全角コロンを置き換える
            timeStr = timeStr.replace(/[：]/g, ":");
            //全角数値を半角数値に置き換える
            timeStr = timeStr.replace(/[０-９]/g, (s)=>{return String.fromCharCode(s.charCodeAt(0) - 0xFEE0);});

            // h:mパターン
            if(timeStr.match(/^\d{1,2}\:\d{1,2}$/))
            {
                h = Number(timeStr.split(":")[0]);
                m = Number(timeStr.split(":")[1]);
            }
            // hhmmパターン
            if(timeStr.match(/^\d{4}$/))
            {
                h = Number(timeStr.slice(0, 2));
                m = Number(timeStr.slice(2, 4));
            }
            //0～59分が有効　※時間は制限なし
            return 0 <= h && 0 <= m && m <= 59;
        },
        /**
         * 時間文字列をhh:mmの形に整形して返す
         * 全角半角両対応
         * @param {string} timeStr 
         * @returns 
         */
        getValidTimeStr: function(timeStr)
        {
            if(!this.checkTimeStr(timeStr))
            {
                return null;
            }
            
            let h = 0;
            let m = 0;
            //全角コロンを置き換える
            timeStr = timeStr.replace(/[：]/g, ":");
            //全角数値を半角数値に置き換える
            timeStr = timeStr.replace(/[０-９]/g, (s)=>{return String.fromCharCode(s.charCodeAt(0) - 0xFEE0);});

            // h:mパターン
            if(timeStr.match(/^\d{1,2}\:\d{1,2}$/))
            {
                h = Number(timeStr.split(":")[0]);
                m = Number(timeStr.split(":")[1]);
            }
            // hhmmパターン
            if(timeStr.match(/^\d{4}$/))
            {
                h = Number(timeStr.slice(0, 2));
                m = Number(timeStr.slice(2, 4));
            }
            return ("0" + h).slice(-2) + ":" + ("0" + m).slice(-2);
        },
        /**
         * 数字の正当性チェック
         * 全角半角両対応
         * @param {string}} numberStr 
         */
        checkNumberStr: function(numberStr)
        {
            //全角数値を半角数値に置き換える
            numberStr = numberStr.replace(/[０-９]/g, (s)=>{return String.fromCharCode(s.charCodeAt(0) - 0xFEE0);});

            if(numberStr.match(/^\d+/))
            {
                return true;
            }
            return false;
        },
        /**
         * 全角半角両対応
         * @param {string} numberStr 
         * @returns 
        */
        getValidNumberStr: function(numberStr)
        {
            //全角数値を半角数値に置き換える
            numberStr = numberStr.replace(/[０-９]/g, (s)=>{return String.fromCharCode(s.charCodeAt(0) - 0xFEE0);});
            return numberStr;
        },

        /**
         * 半角英数字の正当性チェック
         * @param {string}} numberStringStr 
        */
        checkNumberStringStr: function(numberStringStr)
        {
            if(numberStringStr.match(/^[A-Za-z0-9]+/))
            {
                return true;
            }
            return false;
        },
        /**
         * 
         * @param {Number} targetYearMonth 対象年月
         * @param {Number} closeDateID 締め日区分ID
         */
        getCloseDateTerm: function(targetYearMonth, closeDateID)
        {
            //closeDateIDから期間終了日を取得
            const close_date_info = this.getMasterData().close_date;
            const close_date = close_date_info.find((elm) => elm.close_date_id == closeDateID).close_date;
            if(close_date == null)
            {
                //closeDateID異常
                return null;
            }
            if(isNaN(targetYearMonth))
            {
                //数値ではない異常
                return null;
            }
            const year = Math.floor(targetYearMonth / 100);
            const month = targetYearMonth - year * 100;
            if(year < 1000 || 100000 < year || month < 1 || 12 < month)
            {
                //年月がおかしい異常
                return null;
            }
            //エラーチェック終了
            let target_term_start = 0;
            let target_term_end = 0;
            if(close_date == 0)
            {
                //当月初～当月末が対象期間
                target_term_start = this.dateStrToSerial((new Date(year, month - 1, 1)).toLocaleDateString());
                target_term_end = this.dateStrToSerial((new Date(year, month, 0)).toLocaleDateString());
            }
            else
            {
                //当月のclose_date + 1が開始日
                target_term_start = this.dateStrToSerial((new Date(year, month - 1, close_date + 1)).toLocaleDateString());
                //翌月のclose_dateが終了日
                target_term_end = this.dateStrToSerial((new Date(year, month, close_date)).toLocaleDateString());
            }
            return {
                'target_term_start': target_term_start,
                'target_term_end': target_term_end,
            };
        },
        async base64Encode(...parts) {
            return new Promise(resolve => {
              const reader = new FileReader();
              reader.onload = () => {
                const offset = reader.result.indexOf(",") + 1;
                resolve(reader.result.slice(offset));
              };
              reader.readAsDataURL(new Blob(parts));
            });
        },
        /**
         * 対象月分の日数を取得する
         * @param {Number} year 年分
         * @param {Number} month 月分
         */
        getDayOfMonth: function (year, month) 
        {
            let date = new Date(year, month - 1, '01');
            date.setDate(1);
            date.setMonth(date.getMonth() + 1);
            let cdate = new Date(date.getTime() - 1000 * 60 * 60 * 24);
            return cdate.getDate();
        },
        /**
         * 現時点の当月の1日のシリアル値を取得する
         */
        getFirstDaySerialOfCurrentYearMonth: function () 
        {
            let date = new Date();
            return this.dateStrToSerial(date.getFullYear() + '/' + ('00' + String(date.getMonth() + 1)).slice(-2) + '/01');
        },
        /**
         * 対象年月分の1日のシリアル値を取得する
         * @param {Number} dateSerial 年月日シリアル値
         */
        getFirstDaySerialOfSpecifiedDateSerial: function (dateSerial) 
        {
            return this.dateStrToSerial(this.serialToDateStr(dateSerial).slice(0, -2)+'01');
        },
    }
});