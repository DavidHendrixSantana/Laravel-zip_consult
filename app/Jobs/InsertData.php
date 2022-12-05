<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use PhpOffice\PhpSpreadsheet\Reader\Xls;
use App\Models\Municipality;
use App\Models\FederalEntity;
use App\Models\Settlement;
use App\Models\SettlementType;
use App\Models\ZipCode;
use Illuminate\Support\Facades\DB;

class InsertData implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $file;


    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($file)
    {
        $this->file = $file;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        /**
         * Reading the file and  instantiated the object to read the xls
         */
        $path =  public_path('Excel/'.$this->file);
        $reader = new Xls();
     
         /**
          * Formating the name of the file to read it after
          */

        $fileRenamed = str_replace('.xls', '', $this->file);
        $fileRenamed = str_replace(' ', '_', $fileRenamed);
     
     
        /**
         * loading the xls and getting parameters to loop through it
         */
        $reader->setLoadSheetsOnly([$fileRenamed]);
        $spreadsheet = $reader->load($path);
        $sheet = $spreadsheet->getActiveSheet();
        $workSheetInfo = $reader->listWorksheetInfo($path);
        $totalRows = $workSheetInfo[1]['totalRows'];
     
        /**
         *Declaring the Arrays Needed for Data Insertion
         */
        $zipMunicipality = [];
        $municipality=[];
        $settlement_type= [];
        $settlements = [];
        $settlement=[];
        $settlementSaved=[];
        $FederalEntity=[];
        $FederalEntitys=[];
        $FederalEntitySaved=[];
        $zipCode = [];
        $zipCodes = [];
        $zipCodeSaved = [];

        /**
         * process to verify which types of settlements are already registered in the database
         */
        $settlement_type_register=[];
        $ConultSettlementType = SettlementType::all();
        foreach( $ConultSettlementType as $settlement_type_element){                
            array_push($settlement_type_register, $settlement_type_element->name) ;
        }

       
        /**
         * Here starts the loop of any file
         */
        for ($row=2; $row <= $totalRows ; $row++) { 
            $tempArray=[];
            //getting data for municipality
            $cellKeyMunicipality = $sheet->getCell("L{$row}")->getValue();
            $cellName = $sheet->getCell("D{$row}")->getValue();

            //getting data for settlement_type
            $cellSettlement_type = $sheet->getCell("C{$row}")->getValue();

            //getting data for settlement
            $cellZipCode= $sheet->getCell("A{$row}")->getValue();
            $cellNameSettlement= $sheet->getCell("B{$row}")->getValue();
            $cellKeySettlement= $sheet->getCell("M{$row}")->getValue();
            $cellZoneType= $sheet->getCell("N{$row}")->getValue();

            //getting data for federal_entity
            $cellKeyFederalEntity= $sheet->getCell("H{$row}")->getValue();
            $cellNameFederalEntity= $sheet->getCell("E{$row}")->getValue();
            $cellCodeFederalEntity= $sheet->getCell("J{$row}")->getValue();

            //getting data for zip_code
            $cellLocality= $sheet->getCell("F{$row}")->getValue();


            //assigning data to save the array of zip_code
            if(!in_array($cellZipCode, $zipCodeSaved)){
                array_push($zipCodeSaved, $cellZipCode);
                $zipCode['zip_code']= $cellZipCode;
                $zipCode['locality']= $cellLocality ? $cellLocality : '' ;
                $zipCode['federal_entity']= $cellKeyFederalEntity;
                $zipCodes[$row-2]=$zipCode;
            
            }

            //assigning data to save the array of federal_entity
            if(!in_array($cellKeyFederalEntity, $FederalEntitySaved)){
                array_push($FederalEntitySaved, $cellKeyFederalEntity);
                $FederalEntity['key']= $cellKeyFederalEntity;
                $FederalEntity['name']= $cellNameFederalEntity;
                $FederalEntity['code']= $cellCodeFederalEntity;
                $FederalEntitys[$row-2]=$FederalEntity;

            
            }

        //assigning data to save the array of settlement_type
        if(!in_array($cellSettlement_type, $settlement_type_register)){
            array_push($settlement_type, $cellSettlement_type);
            array_push($settlement_type_register, $cellSettlement_type);
        }


           //assigning data to save the array of settlement
           if(!in_array($cellKeySettlement, $settlementSaved)){
            array_push($settlementSaved, $cellKeySettlement);
            $settlement['key']= $cellKeySettlement;
            $settlement['name']= $cellNameSettlement;
            $settlement['zone_type']= $cellZoneType;
            $settlement['settlement_type']= array_search($cellSettlement_type, $settlement_type_register)+1;
            $settlement['zip_code']= $cellZipCode;
            $settlements[$row-2] = $settlement;
            }
            
            //assigning data to save the array of municipality
            if(!in_array($cellZipCode, $zipMunicipality)){
                array_push($zipMunicipality, $cellZipCode);
                $tempArray['key'] = $cellKeyMunicipality;
                $tempArray['name'] = $cellName;
                $tempArray['zip_code'] = $cellZipCode;
                $municipality[$row-2] =  $tempArray; 
            }

        }

    /**
     * Once the file data is obtained, it is time to assign them to the BD
     */
    DB::beginTransaction();

        /**
         * @var \App\Models\SettlementType
        */
        foreach ($settlement_type as $field ) {
            SettlementType::create([
                'name' => $this->quitarAcentos($field)
            ]);
        }

        /**
         * @var \App\Models\FederalEntity
        */            
        foreach ($FederalEntitys as $fields ) {
            FederalEntity::create([
                'key' => (int) $fields['key'],
                'name' => strtoupper($this->quitarAcentos($fields['name'])),
                'code' => $fields['code']
            ]);
        }

        /**
         * @var \App\Models\ZipCode
        */ 
        foreach ($zipCodes as $fields ) {
            ZipCode::create([
                'zip_code' => $fields['zip_code'],
                'locality' => strtoupper($this->quitarAcentos($fields['locality'])),
                'federal_entity' => $fields['federal_entity'],
            ]);
        }


        /**
         * @var \App\Models\Municipality
        */ 
        foreach ($municipality as $fields ) {
            Municipality::create([
                'key' => (int) $fields['key'],
                'name' => strtoupper($this->quitarAcentos($fields['name'])),
                'zip_code' => $fields['zip_code']
            ]);
        }

        /**
         * @var \App\Models\Settlement
        */         
        foreach ($settlements as $fields ) {
            Settlement::create([
                'key' =>  (int) $fields['key'],
                'name' => strtoupper($this->quitarAcentos($fields['name'])),
                'zone_type' => strtoupper($this->quitarAcentos($fields['zone_type'])),
                'settlement_type' => strtoupper($this->quitarAcentos($fields['settlement_type'])),
                'zip_code' => $fields['zip_code']
            ]);
        }
        DB::commit();
    }

    public function quitarAcentos($cadena){
        $originales = 'ÁÉÍÓÚáéíóúÑñ';
        $modificadas = 'AEIOUaeiou??';
        $cadena = utf8_decode($cadena);
        $cadena = strtr($cadena, utf8_decode($originales), $modificadas);
        return utf8_encode($cadena);



    }
}
