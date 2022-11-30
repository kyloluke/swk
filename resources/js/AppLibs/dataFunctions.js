const SESSION_TIMEOUT = 60 * 60 * 1000;//最終取得後からのタイムアウト扱い（要再取得）の時間
const SESSION_TIME_ATT_INFO = "timestamp_attendance_information";
const SESSION_DATA_ATT_INFO = "data_attendance_information";
const MASTER_DATA_KEY = "master";
let IS_WAIT_GET_ATTENDANCE_INFO = false;


Vue.mixin({
    methods: {
        /**
         * Sleep関数
         * awaitで受けると、指定したミリ秒Sleepする
         * @param {Number} sleepTime ミリ秒指定
         * @returns 
         */
        sleep: async function(sleepTime)
        {
            return new Promise((resolve) => setTimeout(resolve, sleepTime));
        },
        /**
         * マスターデータをSessionStorageへセットする
         * @param {Object}} sessionData 
         */
        setMasterData: function(sessionData)
        {
            sessionStorage.setItem(MASTER_DATA_KEY, JSON.stringify(sessionData)); 
        },
        /**
         * SessionStorage上のマスタデータを取得
         * @returns マスターデータ
         */
        getMasterData: function()
        {
            return JSON.parse(sessionStorage.getItem(MASTER_DATA_KEY));
        },
        /**
         * マスタデータの特定の値をリセットする
         * @param {String} property_name 
         * @returns 
         */
        resetMasterData: async function(property_name){
            return new Promise((resolve, reject)=>{
                axios.get('resetMasterData', {
                    params:{
                        'property_name': property_name,
                    }
                }).then(response => {
                    if(response.data.result)
                    {
                        let masterData = this.getMasterData();
                        //レスポンスの内容によって書き換え
                        masterData[response.data.values.name] = response.data.values.value;
                        this.setMasterData(masterData);

                        resolve("done");
                    }
                    else
                    {
                        reject("failed");
                    }
                }).catch(error =>{
                    reject("error");
                });
            });
        },
        /**
         * T002勤怠情報を1か月分取得する
         * isNeedRefreshがfalseの場合、最終取得より1時間以内ならばSessionStorageから取得する
         * 取得したデータはSessionStorageへ保持される
         * @param {Number} employeeID 社員ID
         * @param {Number} yearMonth 年月
         * @param {Boolean} isNeedRefresh 強制再取得フラグ
         */
        getAttendanceInformationMonthly: async function(employeeID, yearMonth, isNeedRefresh = false){
            const timeout = 5000;
            let timeoutCount = 0;
            let timeInterval = 100;
            while(IS_WAIT_GET_ATTENDANCE_INFO)
            {
                //他からの呼び出しで実行中のためsleep
                await this.sleep(timeInterval);
                timeoutCount += timeInterval;
                if(timeout < timeoutCount)
                {
                    //タイムアウト
                    break;
                }
            }
            return new Promise((resolve, reject) =>{
                IS_WAIT_GET_ATTENDANCE_INFO = true;
                //再取得必要かどうか
                let isNeedAccessDB = false;
                if(isNeedRefresh)
                {
                    isNeedAccessDB = true;
                }
                //強制更新フラグfalseの場合は日時確認
                else
                {
                    //最終取得時刻とデータがセットされているか
                    const latestTime = sessionStorage.getItem(SESSION_TIME_ATT_INFO + employeeID + yearMonth);
                    const latestData = JSON.parse(sessionStorage.getItem(SESSION_DATA_ATT_INFO + employeeID + yearMonth));
                    if(!latestTime || !latestData)
                    {
                        isNeedAccessDB = true;
                    }
                    //最終取得時刻からタイムアウトしていないか
                    else if(SESSION_TIMEOUT < Date.now() - latestTime)
                    {
                        isNeedAccessDB = true;
                    }
                }
    
                //DBから取得必要
                if(isNeedAccessDB)
                {
                    //DBからデータ取得
                    axios.get('getInputAttendanceInfo', {
                        //年月を6桁で送信
                        params:{
                            'yearMonth' : yearMonth,
                            'employeeID': employeeID,
                        }
                    }).then(response => {
                        if(response.data.result)
                        {
                            //SessionStorageへセット
                            sessionStorage.setItem(SESSION_DATA_ATT_INFO + employeeID + yearMonth, JSON.stringify(response.data.values));
                            //タイムスタンプを更新
                            sessionStorage.setItem(SESSION_TIME_ATT_INFO + employeeID + yearMonth, Date.now());
                            //データをresolve
                            resolve(JSON.parse(sessionStorage.getItem(SESSION_DATA_ATT_INFO + employeeID + yearMonth)));
                        }
                        else
                        {
                            reject("不正なリクエストが発生しました。ページを読み込みなおしてください(001)");
                        }
                        IS_WAIT_GET_ATTENDANCE_INFO = false;
                     }).catch(error =>{
                        reject("不正なリクエストが発生しました。ページを読み込みなおしてください(002)");
                        IS_WAIT_GET_ATTENDANCE_INFO = false;
                     });
                }
                else
                {
                    //データをresolve
                    resolve(JSON.parse(sessionStorage.getItem(SESSION_DATA_ATT_INFO + employeeID + yearMonth)));
                    IS_WAIT_GET_ATTENDANCE_INFO = false;
                }
            });
        },
        /**
         * T002勤怠情報を1日分取得する
         * isNeedRefreshがfalseの場合、最終取得より1時間以内ならばSessionStorageから取得する
         * 取得したデータはSessionStorageへ保持される
         * @param {Number} employeeID 社員ID
         * @param {Number} dateSerial 年月日シリアル値 
         * @param {Boolean} isNeedRefresh 強制再取得フラグ
         */
        getAttendanceInformationDaily: async function(employeeID, dateSerial, isNeedRefresh = false){

        },
    }
});