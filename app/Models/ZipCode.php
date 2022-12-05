<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ZipCode extends Model
{
    use HasFactory;
    protected $table = 'zip_code';
    protected $guarded = [];
    protected $hidden=[
        'created_at',
        'updated_at'
    ];
    public function FederalEntity(){
        return $this->hasOne(FederalEntity::class, 'key', 'federal_entity');
    }

    public function Municipality(){
        return $this->belongsTo(Municipality::class, 'zip_code', 'zip_code');
    }
    public function Settlements(){
        return $this->hasMany(Settlement::class, 'zip_code', 'zip_code');
    }

}
