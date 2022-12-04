<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ZipCode;

class ZipConsultController extends Controller
{
    public function zipConsult($zip_code){

        //Consult the Zip Code withm the consult return 
        $zipCode = ZipCode::where('zip_code', $zip_code)->with('FederalEntity','Settlement', 'Municipality')->first();
        return $zipCode;
    }
}
