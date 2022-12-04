<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Settlement extends Model
{
    use HasFactory;
    protected $table = 'settlement';
    protected $guarded = [];
    protected $hidden=[
        'created_at',
        'updated_at',
        'zip_code'
    ];
    protected $with= 'SettlementType';

    public function ZipCode(){
        return $this->belongsTo(ZipCode::class, 'zip_code', 'zip_code');
    }
    public function SettlementType(){
        return $this->hasOne(SettlementType::class, 'id', 'settlement_type');
    }

}
