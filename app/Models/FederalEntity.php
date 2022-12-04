<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FederalEntity extends Model
{
    use HasFactory;

    protected $table = 'federal_entity';
    protected $guarded = [];
    protected $hidden=[
        'created_at',
        'updated_at'
    ];

    public function ZipCode(){
        return $this->belongsTo(ZipCode::class, 'federal_entity', 'key');
    }
    
}
