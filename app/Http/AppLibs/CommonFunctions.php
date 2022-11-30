<?php

namespace App\Http\AppLibs;
use App\Models\m031_unemployed;
use App\Models\m044_holiday_summary;
class CommonFunctions
{
    /**
     * 日付型をDB登録用のシリアル値に変換
     * シリアル値は、1990/1/1を1とした数値型
     */
    public function dateToSerial($date_val)
    {
        return floor((strtotime($date_val) + (9 * 60 * 60)) / (24 * 60 * 60) + 25569);
    }
    /**
     * DB上の日付シリアル値を日付型に変換
     * シリアル値は、1990/1/1を1とした数値型
     */
    public function serialToDate($serial_val)
    {
        return date('Y-m-d', ($serial_val - 25569) * 60 * 60 * 24);
    }
    /**
     * 日付シリアル値を文字列に変換
     * フォーマット指定
     */
    public function serialToDateStr($serial_val, $format)
    {
        return date($format, ($serial_val - 25569) * 60 * 60 * 24);
    }

    /**
     * DB上の日付シリアル値を数値型に変換
     * シリアル値は、1990/1/1を1とした数値型
     */
    public function serialToMonthDayNumber($serial_val)
    {
        return intval(date('md', ($serial_val - 25569) * 60 * 60 * 24));
    }
    /**
     * DB上の日付シリアル値を数値型に変換
     * シリアル値は、1990/1/1を1とした数値型
     */
    public function serialToYearNumber($serial_val)
    {
        return intval(date('Y', ($serial_val - 25569) * 60 * 60 * 24));
    }
    /**
     * DB上の日付シリアル値を数値型に変換
     * シリアル値は、1990/1/1を1とした数値型
     */
    public function serialToMonthNumber($serial_val)
    {
        return intval(date('m', ($serial_val - 25569) * 60 * 60 * 24));
    }
    /**
     * DB上の日付シリアル値を数値型に変換
     * シリアル値は、1990/1/1を1とした数値型
     */
    public function serialToYearMonthNumber($serial_val)
    {
        return intval(date('Ym', ($serial_val - 25569) * 60 * 60 * 24));
    }
    /**
     * DB上の日付シリアル値を数値型に変換
     * シリアル値は、1990/1/1を1とした数値型
     */
    public function serialToDayNumber($serial_val)
    {
        return intval(date('d', ($serial_val - 25569) * 60 * 60 * 24));
    }
    /**
     * DB上の日付シリアル値を数値型に変換
     * シリアル値は、1990/1/1を1とした数値型
     */
    public function serialToYearMonthDayNumber($serial_val)
    {
        return intval(date('Ymd', ($serial_val - 25569) * 60 * 60 * 24));
    }

    /**
     * DB上の日付シリアル値を数値型に変換
     * シリアル値は、1990/1/1を1とした数値型
     */
    public function getSerial($year, $month, $day)
    {
        return $this->dateToSerial(date('Y-m-d', strtotime($year . '-' . $month . '-' . $day)));
    }

    /**
     * 数値型をDB上の日付シリアル値に変換
     * シリアル値は、1990/1/1を1とした数値型
     */
    public function numberToDateSerial($date_number)
    {
        $year = intval($date_number / 10000);
        $month_day = $date_number - $year * 10000;
        $month = intval($month_day / 100);
        $day = $month_day - $month * 100;
        return $this->dateToSerial(date('Y-m-d', strtotime($year . '-' . $month . '-' . $day)));
    }

    /**
     * 8桁の年月日値に、additionalMonthを足した年月日の前日を返す。
     * 翌月の同日が存在しない年月日だった場合、翌日の月末日を返す
     * 翌月の同日が存在する年月日だった場合、翌日の同日の前日を返す
     */
    public function calcYearMonthDay($yearMonthDayNumber, $additionalMonth)
    {
        //8文字の数値以外は空文字返す
        $yearMonthText = strval(intval($yearMonthDayNumber));
        //8桁以外はエラー
        if(strlen($yearMonthText) != 8)
        {
            return null;
        }
        $yearMonthVal = floor($yearMonthDayNumber / 100);
        $nextYearMonthVal = $this->calcYearMonth($yearMonthVal,$additionalMonth);
        $day = $yearMonthDayNumber - $yearMonthVal * 100;

        $year = intval($nextYearMonthVal / 100);
        $month = $nextYearMonthVal - $year * 100;

        $end_serial = $this->dateToSerial(date('Y-m-d', strtotime('last day of ' . $year . '-' . $month)));
        $next_month_serial = $this->dateToSerial(date('Y-m-d', strtotime($year . '-' . $month . '-' . $day)));

        return $end_serial < $next_month_serial ? $end_serial:$next_month_serial - 1 ;
    }
    /**
     * DB上の日付シリアル値を曜日（文字）に変換
     */
    public function serialToWeekChar($serial_val)
    {
        $week = [
            '日', //0
            '月', //1
            '火', //2
            '水', //3
            '木', //4
            '金', //5
            '土', //6
          ];

        return $week[$this->serialToWeek($serial_val)];
    }
    /**
     * DB上の日付シリアル値を月に変換
     * シリアル値は、1990/1/1を1とした数値型
     */
    public function serialToMonth($serial_val)
    {
        return date('m', ($serial_val - 25569) * 60 * 60 * 24);
    }
    /**
     * DB上の日付シリアル値を曜日に変換
     */
    public function serialToWeek($serial_val)
    {
        return date('w', ($serial_val - 25569) * 60 * 60 * 24);
    }
    /**
     * 今日の日付をシリアル値で取得
     */
    public function getTodaySerial()
    {
        date_default_timezone_set('Asia/Tokyo');
        return floor((strtotime('today') + (9 * 60 * 60)) / (24 * 60 * 60) + 25569);
    }
    /**
     * 現在時刻をシリアル値で取得
     */
    public function getNowtimeSerial()
    {
        date_default_timezone_set('Asia/Tokyo');
        $today = date("H:i"); 
        $today_split = explode(":", $today);
        $time_serial = (($today_split[0]) * 60) + $today_split[1];
        return $time_serial;
    }
    /**
     * 時刻をシリアル値で取得
     */
    public function getTimeSerial($time)
    {
        $time_split = explode(":", $time);
        $time_serial = (($time_split[0]) * 60) + $time_split[1];
        return $time_serial;
    }
    /**
     * 時間シリアル値を文字列に変換
     * HH:MM形式のゼロパディングあり
     * 24時間以上は、24時間以内に丸められる
     */
    public function serialToTimeStr($timeSerial)
    {
        $hour = floor($timeSerial / 60);
        $hour_str = $hour - floor($hour / 24) * 24;
        $time_str = $timeSerial % 60;
        return str_pad($hour_str, 2, 0, STR_PAD_LEFT) . ":" . str_pad($time_str, 2, 0, STR_PAD_LEFT);
    }
    /**
     * 時間シリアル値を時間数文字列に変換
     * H:MM形式の分にゼロパディングあり
     * 24時間以上もそのまま表示する
     */
    public function serialToHoursStr($timeSerial)
    {
        $hour_str = floor($timeSerial / 60);
        $time_str = $timeSerial % 60;
        return $hour_str . ":" . str_pad($time_str, 2, 0, STR_PAD_LEFT);
    }
    /**
     * 締め日期間を取得
     */
    public function getCloseTerm($year_month, $close_date)
    {
        //6桁以外はエラー
        if(strlen($year_month) != 6)
        {
            return null;
        }
        //日付範囲以外はエラー
        if($close_date < 0 || 31 < $close_date)
        {
            return null;
        }
        //年月に分解
        $year = intval($year_month / 100);
        $month = $year_month - $year * 100;
        $date = $close_date;

        //期間開始日
        if($close_date == 0)
        {
            //月末締め日シリアル値
            $end_serial = $this->dateToSerial(date('Y-m-d', strtotime('last day of ' . $year . '-' . $month)));
            $start_serial = $this->dateToSerial(date('Y-m-d', strtotime($year . '-' . $month . '-1')));
            $total1_start = $start_serial; //給与連携集計１用
            $total1_end = $end_serial; //給与連携集計１用
            $total2_start = 0; //給与連携集計２用
            $total2_end = 0; //給与連携集計２用
        }
        else
        {
            //15日締め日シリアル値（翌月16日）
            $end_serial = $this->dateToSerial(date('Y-m-d', strtotime('+1 month ' . $year . '-' . $month . '-' . $date)));
            $start_serial = $this->dateToSerial(date('Y-m-d', strtotime($year . '-' . $month . '-' . ($date + 1))));
            $total1_start = $start_serial; //給与連携集計１用
            $total1_end = $this->dateToSerial(date('Y-m-d', strtotime('last day of ' . $year . '-' . $month))); //給与連携集計１用
            $total2_start = $this->dateToSerial(date('Y-m-d', strtotime('+1 month ' . $year . '-' . $month))); //給与連携集計２用
            $total2_end = $end_serial; //給与連携集計２用
        }
        return array(
            'start_sereial' => $start_serial,
            'end_sereial' => $end_serial,
            'total1_start_sereial' => $total1_start,
            'total1_end_sereial' => $total1_end,
            'total2_start_sereial' => $total2_start,
            'total2_end_sereial' => $total2_end,
        );
    }
    /**
     * 日付シリアル値と締め日から対象年月を取得（月末締めは締め日値0）
     */
    public function getTargetTerm($serial_val, $close_date)
    {
        return date('Ym', ($serial_val - 25569 - $close_date) * 60 * 60 * 24);
    }
    /**
     * 6桁の年月値に、additionalMonthを足した年月を返す。
     * マイナス値も指定可能
     */
    public function calcYearMonth($yearMonthNumber, $additionalMonth){
        //6文字の数値以外は空文字返す
        $yearMonthText = strval(intval($yearMonthNumber));
        //6桁以外はエラー
        if(strlen($yearMonthText) != 6)
        {
            return null;
        }
        $yearVal = floor($yearMonthNumber / 100);
        $sumMonth = $yearVal * 12 + $yearMonthNumber - $yearVal * 100;
        $addedSumMonth = $sumMonth + $additionalMonth;
        $addedYear = floor(($addedSumMonth - 1) / 12);
        $addedMonth = ($addedSumMonth - 1) % 12 + 1;
        return $addedYear * 100 + $addedMonth; 
    }
    public function compaireYearMonth($fromYearMonthNumber, $toYearMonthNumber)
    {
        //6桁以外はエラー
        if(strlen(strval(intval($fromYearMonthNumber))) != 6 || strlen(strval(intval($toYearMonthNumber))) != 6 )
        {
            return null;
        }
        $fromYearVal = floor($fromYearMonthNumber / 100);
        $fromMonth = $fromYearVal * 12 + $fromYearMonthNumber - $fromYearVal * 100;
        $toYearVal = floor($toYearMonthNumber / 100);
        $toMonth = $toYearVal * 12 + $toYearMonthNumber - $toYearVal * 100;
        return $toMonth - $fromMonth;
    }
    /**
     * 休憩時間の重複チェック
     */
    public function checkRestOverlap($baseStart, $baseEnd, $targetStart, $targetEnd){
        //nullがあるときは重複チェックしない
        if($baseStart == 0 || $baseEnd == 0 || $targetStart == 0 || $targetEnd == 0){
            return true;
        }
        //開始、終了ともに比較対象時間内の場合
        if($baseStart <= $targetStart && $targetEnd <= $baseEnd){
            return false;
        }
        //開始、終了が比較対象開始、終了をまたぐ場合
        else if($targetStart < $baseStart && $baseEnd < $targetEnd){
            return false;
        }     
        //開始、終了が比較対象開始をまたぐ場合
        else if($targetStart < $baseStart && $baseStart < $targetEnd){
            return false;
        }
        //開始、終了が比較対象終了をまたぐ場合
        else if($targetStart < $baseEnd && $baseEnd < $targetEnd){
            return false;
        }
        //開始、終了ともに比較対象時間外の場合
        else{
            return true;
        }
    }

    /**
     * 日付の入力検証
     */
    public function checkDate($date){
        if($date === "" || $date == null){
            return false;
        }
        if(is_numeric($date)){
            if(strlen($date) == 8){
                return true; 
            }else{
                return false; 
            }
        }else{
            if((strlen($date) == 8) && (substr($date,4, 1) == '-' && substr($date,6, 1) == '-') || (substr($date,4, 1) == '/' && substr($date,6, 1) == '/')){
                if(is_numeric(substr($date,0, 4)) && is_numeric(substr($date,5, 1)) && is_numeric(substr($date,7, 1))){
                    return true;
                }else{
                    return false;
                }
            }else if((strlen($date) == 9) && (substr($date,4, 1) == '-' && substr($date,6, 1) == '-') || (substr($date,4, 1) == '/' && substr($date,6, 1) == '/')){
                if(is_numeric(substr($date,0, 4)) && is_numeric(substr($date,5, 1)) && is_numeric(substr($date,7, 2))){
                    return true;
                }else{
                    return false;
                }
            }else if((strlen($date) == 9) && (substr($date,4, 1) == '-' && substr($date,7, 1) == '-') || (substr($date,4, 1) == '/' && substr($date,7, 1) == '/')){
                if(is_numeric(substr($date,0, 4)) && is_numeric(substr($date,5, 2)) && is_numeric(substr($date,8, 1))){
                    return true;
                }else{
                    return false;
                }
            }else if((strlen($date) == 10) && (substr($date,4, 1) == '-' && substr($date,7, 1) == '-') || (substr($date,4, 1) == '/' && substr($date,7, 1) == '/')){
                if(is_numeric(substr($date,0, 4)) && is_numeric(substr($date,5, 2)) && is_numeric(substr($date,8, 2))){
                    return true;
                }else{
                    return false;
                }
            }else{
                return false; 
            }
        }
    }

    /**
     * 時間の入力検証
     */
    public function checkTime($time){
        if($time === "" || $time == null){
            return false;
        }
        if(is_numeric($time)){
            if(strlen($time) == 4){
                return true; 
            }else{
                return false; 
            }
        }else{
            if(strlen($time) == 3 && substr($time,1, 1) == ':'){
                if(is_numeric(substr($time,0, 1)) && is_numeric(substr($time,2, 1))){
                    return true;
                }else{
                    return false;
                }
            }else if(strlen($time) == 4 && substr($time,1, 1) == ':'){
                if(is_numeric(substr($time,0, 1)) && is_numeric(substr($time,2, 2))){
                    return true;
                }else{
                    return false;
                }
            }else if(strlen($time) == 4 && substr($time,2, 1) == ':'){
                if(is_numeric(substr($time,0, 2)) && is_numeric(substr($time,3, 1))){
                    return true;
                }else{
                    return false;
                }
            }else if(strlen($time) == 5 && substr($time,2, 1) == ':'){
                if(is_numeric(substr($time,0, 2)) && is_numeric(substr($time,3, 2))){
                    return true;
                }else{
                    return false;
                }
            }else{
                return false; 
            }
        }
    }
    /**
     * IDをハッシュ化したファイルパスを取得
     * typeによってディレクトリ指定
     */
    public function getFilePath($type, $id)
    {
        $pref = "";
        $suff = "";
        switch($type)
        {
            case 1: //汎用検索
                $pref = $pref . "general_search/file_";
                break;
            case 2: //汎用検索ダウンロード
                $pref = $pref . "general_search_csv/output_";
                $suff = ".csv";
                break;
            case 3: //汎用検索ダウンロード（public
                $pref = $pref . "download/output_";
                $suff = ".csv";
                break;
        }
        //md5でidをハッシュ化（短めなのでmd5）
        $body = md5($id);
        return $pref . $body . $suff;
    }
}