<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class t001_web_punch_clock extends Model
{
    use HasFactory;
    // テーブルの関連付け
    protected $table = 't001_web_punch_clock';
    // 更新可能な項目の設定
    protected $fillable = [
        'employee_id',
        'punch_clock_date',
        'clocking_in_out_id',
        'punch_clock_time',
        'transfer_class',
        'input_class',
        'detail_no',
        'is_delete',
        'created_user',
        'updated_user'
    ];
    protected $primaryKey = "web_punch_clock_id";
    public function employee()
    {
        return $this->belongsTo('App\Models\m007_employee', 'employee_id');
    }
    public function clocking_in_out()
    {
        return $this->belongsTo('App\Models\m025_clocking_in_out', 'clocking_in_out_id');
    }
    public function getData()
    {
        $t001WebPunchClock = DB::table($this->table)->get();

        return $t001WebPunchClock;
    }
    /**
     * 対象社員の指定期間内の打刻を取得
     */
    public function getDataWithinTerm($employee_id, $start_date_serial, $end_date_serial)
    {
        return DB::table($this->table)
            ->select('punch_clock_date', 'clocking_in_out_id', 'punch_clock_time', 'input_class')
            ->where('employee_id', $employee_id)
            ->whereBetween('punch_clock_date', [$start_date_serial, $end_date_serial])
            ->where('is_delete', 0)
            ->get();
    }
    /**
     * 対象社員の指定期間内の未転送の打刻を取得
     */
    public function getUnTransferData()
    {
        return DB::table($this->table)
            ->select('web_punch_clock_id', 'employee_id', 'punch_clock_date', 'clocking_in_out_id', 'punch_clock_time', 'input_class')
            ->where('transfer_class', 0)
            ->where('is_delete', 0)
            ->get();
    }
    /**
     * 転送済み打刻に転送完了フラグを登録
     */
    public function updateTransferClass($web_punch_clock_id, $transfer_class)
    {
        return DB::table($this->table)
            ->where('web_punch_clock_id', $web_punch_clock_id)
            ->update(['transfer_class' => $transfer_class]);
    }
    /**
     * 社員ID,打刻年月日、出退勤ID、打刻時間、をDBへ登録
     */
    public function insertWebPunchClock($employee_id, $punch_clock_date, $clocking_in_out_id, $punch_clock_time)
    {
        return DB::table($this->table)->insert(
            ['employee_id' => $employee_id, 'punch_clock_date' => $punch_clock_date, 'clocking_in_out_id' => $clocking_in_out_id, 'punch_clock_time' => $punch_clock_time]
        );
    }
    /**
     * 社員ID,打刻年月日、出退勤ID、打刻時間、をDBへ新規、更新
     */
    public function upsertInputPunchClock($employee_id, $punch_clock_date, $clocking_in_out_id, $punch_clock_time)
    {
        return DB::table($this->table)
            ->updateOrInsert(
                ['employee_id' => $employee_id, 
                'punch_clock_date' => $punch_clock_date, 
                'clocking_in_out_id' => $clocking_in_out_id, 
                'input_class' => 1,
                'is_delete' => 0],
                ['employee_id' => $employee_id, 
                'punch_clock_date' => $punch_clock_date, 
                'clocking_in_out_id' => $clocking_in_out_id, 
                'punch_clock_time' => $punch_clock_time,
                'transfer_class' => 0,
                'input_class' => 1,
                'is_delete' => 0]
            );
    }
    /**
     * 指定日の手入力打刻を削除
     */
    public function deleteInputWebPunch($employee_id, $punch_clock_date, $clocking_in_out_id)
    {
        return DB::table($this->table)
            ->where('employee_id', $employee_id)
            ->where('punch_clock_date', $punch_clock_date)
            ->where('clocking_in_out_id', $clocking_in_out_id)
            ->where('input_class', 1)
            ->where('is_delete', 0)
            ->update(['is_delete' => 1]);
    }

    /**
     * 対象社員の指定期間内の転送済み手入力打刻を取得
     */
    public function getInputDataWithinData($employee_id, $punch_clock_date, $clocking_in_out_id)
    {
        return DB::table($this->table)
            ->where('employee_id', $employee_id)
            ->where('punch_clock_date', $punch_clock_date)
            ->where('clocking_in_out_id', $clocking_in_out_id)
            ->where('transfer_class', 1)
            ->where('input_class', 1)
            ->where('is_delete', 0)
            ->first();
    }
    /**
     * 対象社員の指定日のWeb打刻を取得
     */
    public function getWebPunchByClockingInOutID($employee_id, $punch_clock_date, $clocking_in_out_id)
    {
        return DB::table($this->table)
            ->where('employee_id', $employee_id)
            ->where('punch_clock_date', $punch_clock_date)
            ->where('clocking_in_out_id', $clocking_in_out_id)
            ->where('input_class', 0)
            ->where('is_delete', 0)
            ->get();
    }
    /**
     * 対象社員のその日の戻り打刻一番遅い時刻を取得する
     */
    public function getLastComeBackPunchedTime($employeeID, $punch_clock_date, $clocking_in_out_id)
    {
        return DB::table($this->table)
            ->where('employee_id', $employeeID)
            ->where('is_delete', 0)
            ->where('punch_clock_date', $punch_clock_date)
            ->where('clocking_in_out_id', $clocking_in_out_id)
            ->orderBy('punch_clock_time', 'desc')
            ->first();
    }
}
