<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SettlementType extends Model
{
    use HasFactory;
    protected $table = 'settlement_type';
    protected $guarded = [];
    protected $hidden=[
        'id',
        'created_at',
        'updated_at'
    ];


    public function Settlement(){
        return $this->belongsTo(ZipCode::class , 'settlement_type', 'id');
    }
}
