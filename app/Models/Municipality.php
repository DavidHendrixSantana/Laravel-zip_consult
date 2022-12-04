<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Municipality extends Model
{
    use HasFactory;

    protected $table = 'municipality';
    protected $guarded = [];
    protected $hidden=[
        'created_at',
        'updated_at',
        'zip_code',
    ];
    public function ZipCode(){
        return $this->belongsTo(ZipCode::class, 'zip_code', 'zip_code');
    }


}
