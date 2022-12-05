<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Jobs\InsertData;
use Illuminate\Support\Facades\DB;


class MigrateDataController extends Controller
{
    /**
     * Create the call of the jobs.
     *
     * @return void
     */
    public function insertData(){



        try {
            //Scan the name of the xls files  in the folder Excel
            $pathNames = array_slice(scandir(public_path('Excel')),2); 

            //iterating the xls files to generate a job that migrates each sheet
            foreach ($pathNames as $file) {
              InsertData::dispatch($file);
            }
      
            
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }
        

    }


    public function quitarAcentos($cadena){
        $originales = 'ÁÉÍÓÚáéíóúÑñ';
        $modificadas = 'AEIOUaeiou??';

        $cadena = utf8_decode($cadena);
        $cadena = strtr($cadena, utf8_decode($originales), $modificadas);
        return utf8_encode($cadena);
    }
}
