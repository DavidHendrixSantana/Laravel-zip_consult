<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ZipCode;

class ZipConsultController extends Controller
{

    /**
     * Summary of zipConsult
     * @param mixed $zip_code
     * @param   string
     */
    public function zipConsult(int $zip_code){
        $zip_code = (int) $zip_code;

        if(gettype($zip_code)== 'integer')
        {
        //Consult the Zip Code  
        $zipCode = ZipCode::where('zip_code', $zip_code)->with('FederalEntity','Settlement', 'Municipality')->first();
         
            return $zipCode;
        }else{
            abort(404);

        }
       


    }
}
