<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\LockDetail;

class Lock extends Model
{
    //
   // public $timestamps = false;
        protected $fillable = [
            'id','lock_type_id', 'created_at', 'updated_at', 'scheme_step_id', 'prospect_saved_search_progress_id' 
        ];


    public static function attemptUnlock($lock, $time_now) {

        $time_now = (int) $time_now;

        $do_unlock = true;

        $lockDetails = LockDetail::where('lock_id', $lock->id)->get();

        echo 'looping through lock details';

        foreach($lockDetails as $lockDetail) {

                if ($lock->lock_type_id == 1) { //how many days to wait

                        $seconds_since_lock_was_created = ($time_now - strtotime($lock->created_at));
                        $days_since_lock_was_created = ($seconds_since_lock_was_created / 86400);

                        echo "its been $days_since_lock_was_created days since this lock created";

                        if (!($days_since_lock_was_created > $lockDetail->value)) {
                                $do_unlock = false;
                        }
                }

                if ($lock->lock_type_id == 2) {
//                      if (!$has approval){
                                $do_unlock = false;
//                      }
                }
        }

        if ($do_unlock) {
                echo "UNLOCKING $lock->id";
                $lock->is_unlocked = 1;
                $lock->save();
                return true;
        }else{
                echo "NOT UNLOCKING $lock->id";
                return false;
        }

    }

}
	

