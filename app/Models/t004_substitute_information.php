<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Http\AppLibs\CommonFunctions;

class t004_substitute_information extends Model
{
    use HasFactory;
    // テーブルの関連付け
    protected $table = 't004_substitute_information';
    // 更新可能な項目の設定
    protected $fillable = [
        'employee_id',
        'attendance_information_id',
        'holiday_substitute_date',
        'substitute_holiday_date',
        'acquired_substitue_holiday_date',
        'substitute_reason',
        'detail_no',
        'is_delete',
        'created_user',
        'updated_user'
    ];
    protected $primaryKey = "substitute_information_id";
    public function employee()
    {
        return $this->belongsTo('App\Models\m007_employee', 'employee_id');
    }
    public function getData()
    {
        $t004SubstituteInformation = DB::table($this->table)->get();

        return $t004SubstituteInformation;
    }
    /**
     * 対象社員の指定期間内の振替休日年月日情報を取得
     */
    public function getSubstituteHolidayDateWithinTerm($employee_id, $firstday_of_month, $lastday_of_month)
    {
        return DB::table($this->table)
            ->select('employee_id', 'attendance_information_id', 'holiday_substitute_date', 'substitute_holiday_date', 'acquired_substitue_holiday_date', 'substitute_reason','detail_no','is_delete','created_user','updated_user')
            ->where('employee_id', $employee_id)
            ->whereBetween('substitute_holiday_date', [$firstday_of_month, $lastday_of_month])
            ->where('is_delete', 0)
            ->get();
    }
    /**
     * 対象社員の振替休日年月日情報をすべて取得
     */
    public function getSubstituteHolidayDate($employee_id)
    {
        //全期間で取得
        return $this->getSubstituteHolidayDateWithinTerm($employee_id, 0, 2958465);
    }

    /**
     * 対象社員の指定期間内の振替休日年月日情報を取得
     */
    public function getSubstituteHolidayWithinTerm($employee_id, $firstday_of_month, $lastday_of_month)
    {
        return DB::table($this->table)
            ->select('substitute_information_id', 'employee_id', 'attendance_information_id', 'holiday_substitute_date', 'substitute_holiday_date', 'acquired_substitue_holiday_date', 'substitute_reason','detail_no','is_delete','created_user','updated_user')
            ->where('employee_id', $employee_id)
            ->where(function($q) use ($firstday_of_month, $lastday_of_month) {
                $q->whereBetween('substitute_holiday_date', [$firstday_of_month, $lastday_of_month])
                    ->orWhere('substitute_holiday_date', 0);
            })
            ->orderBy('holiday_substitute_date')
            ->where('is_delete', 0)
            ->get();
    }
    /**
     * 本人締め
     */
    public function updateCloseState($employee_id, $substitute_holiday){
        foreach($substitute_holiday as $info){
            DB::table($this->table)
            ->where('employee_id', $employee_id)
            ->where('substitute_holiday_date', $info->attendance_date)
            ->where('is_delete', 0)
            ->update(['acquired_substitue_holiday_date' => $info->attendance_date]);
        }
    }
    /**
     * 本人締め取り消し
     */
    public function cancelCloseState($employee_id, $firstday_of_month, $lastday_of_month){
        return DB::table($this->table)
            ->where('employee_id', $employee_id)
            ->whereBetween('substitute_holiday_date', [$firstday_of_month, $lastday_of_month])
            ->where('is_delete', 0)
            ->update(['acquired_substitue_holiday_date' => 0]);
    }

    /**
     * 指定期間内の振替休日年月日情報を取得
     */
    public function getSubstituteHolidayDateByTerm($firstday_of_month, $lastday_of_month)
    {
        return DB::table($this->table)
            ->select('employee_id', 'attendance_information_id', 'holiday_substitute_date', 'substitute_holiday_date', 'acquired_substitue_holiday_date', 'substitute_reason','detail_no','is_delete','created_user','updated_user')
            ->whereBetween('substitute_holiday_date', [$firstday_of_month, $lastday_of_month])
            ->where('is_delete', 0)
            ->get();
    }

    /**
     * 指定社員の未取得振替休日年月日情報を取得
     */
    public function getSubstituteHolidayWithoutAcquired($employee_id)
    {
        return DB::table($this->table)
            ->select('employee_id', 'attendance_information_id', 'holiday_substitute_date', 'substitute_holiday_date', 'acquired_substitue_holiday_date', 'substitute_reason','detail_no','is_delete','created_user','updated_user')
            ->where('employee_id', $employee_id)
            ->where('acquired_substitue_holiday_date', 0)
            ->where('is_delete', 0)
            ->orderBy('substitute_holiday_date')
            ->get();
    }

    /**
     * 勤務情報IDで取得
     */
    public function getSubstituteHolidayByAttendanceInformationID($attendance_information_id)
    {
        return DB::table($this->table)
            ->where('attendance_information_id', $attendance_information_id)
            ->where('is_delete', 0)
            ->first();
    }

    /**
     * 当月削除
     */
    public function deleteSubstituteHolidayWithinTerm($employee_id, $substitute_holiday_date)
    {
        return DB::table($this->table)
            ->where('employee_id', $employee_id)
            ->where('substitute_holiday_date', $substitute_holiday_date)
            ->where('acquired_substitue_holiday_date', 0)
            ->where('is_delete', 0)
            ->update(['substitute_reason' => '',
                'substitute_holiday_date' => 0]);
    }

    /**
     * 休日振替年月日が古いもの取得
     */
    public function updateDeletedSubstituteHoliday($employee_id,$attendance_date)
    {
        $cf = new CommonFunctions();

        $data = DB::table($this->table)
            ->where('employee_id', $employee_id)
            ->where('substitute_holiday_date', $attendance_date)
            ->where('acquired_substitue_holiday_date', 0)
            ->where('is_delete', 0)
            ->orderBy('holiday_substitute_date')
            ->first();
        if($data != null){
            return "休日勤務(" . strval($cf->serialToDate($data->holiday_substitute_date)) . ")分の振替休日取得";
        }
        
        $data = DB::table($this->table)
            ->where('employee_id', $employee_id)
            ->where('substitute_holiday_date', 0)
            ->where('acquired_substitue_holiday_date', 0)
            ->where('is_delete', 0)
            ->orderBy('holiday_substitute_date')
            ->first();
        if($data == null){
            return "";
        }
        $substitute_reason = "休日勤務(" . strval($cf->serialToDate($data->holiday_substitute_date)) . ")分の振替休日取得";

        DB::table($this->table)
            ->where('substitute_information_id', $data->substitute_information_id)
            ->update(['substitute_reason' => $substitute_reason,
                'substitute_holiday_date' => $attendance_date]);

        return $substitute_reason;
    }

    /**
     * 振替休日情報削除
     */
    public function updateSubsttuteHolidayDate($employee_id, $substitute_holiday_date)
    {

        //レコード削除
        DB::table($this->table)
            ->where('employee_id', $employee_id)
            ->where('substitute_holiday_date', $substitute_holiday_date)
            ->update(['is_delete' => 1]);
        
    }
    
    /**
     * 振替休日情報削除
     */
    public function updateHolidaySubstituteDate($employee_id, $attendance_information_id, $attendance_date)
    {
        //休日振替年月日でレコードを削除
        DB::table($this->table)
            ->where('employee_id', $employee_id)
            ->where('attendance_information_id', $attendance_information_id)
            ->where('holiday_substitute_date', $attendance_date)
            ->where('substitute_holiday_date', 0)
            ->where('is_delete', 0)
            ->update(['is_delete' => 1]);

    }

    /**
     * 勤務情報にて更新
     */
    public function applySubsttuteHolidayDate($attendance_information,$substitute_holiday_reason)
    {
        //登録必要なし
        if($attendance_information['work_achievement_id'] != 7)
        {
            //レコード存在して、振替休日登録必要ないときは、削除
            $old_sub = $this->getSubstituteHolidayByAttendanceInformationID($attendance_information['attendance_information_id']);
            if($old_sub != null)
            {
                //レコード削除
                DB::table($this->table)
                    ->where('attendance_information_id', $attendance_information['attendance_information_id'])
                    ->update(['is_delete' => 1]);
            }
            //登録必要ないときここで終了
            return;
        }
        //アップサート
        return DB::table($this->table)
            ->updateOrInsert(
            [
                'attendance_information_id' => $attendance_information['attendance_information_id'], 
                'is_delete' => 0,
            ],
            [
                'employee_id' => $attendance_information['employee_id'],
                'holiday_substitute_date' => $attendance_information['attendance_date'],
                'substitute_holiday_date' => $attendance_information['substitute_holiday_date'],
                'substitute_reason' => $substitute_holiday_reason,
            ]
        );
    }
    /**
     * 対象社員の指定期間内の振替休日取得年月日が入ってないレコード数
     */
    public function getSubstituteInformationPaidLeaveDays($employee_id, $firstday_of_month, $lastday_of_month)
    {
        return DB::table($this->table)
            ->where('employee_id', $employee_id)
            ->whereBetween('substitute_holiday_date', [$firstday_of_month, $lastday_of_month])
            ->where('acquired_substitue_holiday_date', 0)
            ->where('is_delete', 0)
            ->count();
    }

    /**
     * 対象社員の振替休日取得年月日が入ってないレコード数
     */
    public function countUnused($employee_id)
    {
        return DB::table($this->table)
            ->where('employee_id', $employee_id)
            ->where('acquired_substitue_holiday_date', 0)
            ->where('is_delete', 0)
            ->count();
    }

}
