<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Prize extends Model
{

    protected $guarded = ['id'];




    public static function nextPrize()
    {
        $prizes = Prize::all();
        $totalProbability = $prizes->sum('probability');
        $random = rand(0, $totalProbability * 100) / 100;
        $calculateativeProbability = 0;
        foreach ($prizes as $prize) {
            $calculateativeProbability += $prize->probability;
            if ($random <= $calculateativeProbability) {
                $prize->increment('awarded_count');
                return $prize;
            }
        }
    }
    

}
