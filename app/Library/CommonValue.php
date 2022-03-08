<?php

namespace App\Library;


use DateTime;
use Lang;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Auth;

class CommonValue
{
    const MASTER_WORKPLACE_BOTH = 62;
    const MASTER_WORKPLACE_OTHER = 63;
	const MASTER_WORKPLACE_JAPAN = 64;
	const MASTER_WORKPLACE_VIETNAM = 65;
  

    /*
     * key cache
     */
    const KEY_CACHE = 'load-data';

    public static function autoIncrement($lenght)
    {
        for ($i = 0; $i < $lenght; $i ++) {
            yield $i;
        }
    }

    /**
     * load data
     * @return array
     */
    

    /**
     * validate integer
     * @param string
     * @return boolean
     */
    public static function validateNumber($arrayValue, $integer = null)
    {
       
        return true;
    }

    /**
     * validate string
     * @param string
     * @param int
     * @return boolean
     */
    public static function validateString($value, $lenghtMax)
    {
        $value = trim($value);
        $lenghtValue = strlen($value);
        if ($lenghtValue > $lenghtMax || $lenghtValue == 0) {
            return false;
        }
        return true;
    }

    

}
