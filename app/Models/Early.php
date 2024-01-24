<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Early extends Model
{
    protected $primaryKey = "id";
    protected $keyType = "int";
    protected $table = "earlies";
    public $incrementing = true;
    public $timestamps = true;

    protected $fillable = [
        'email',
        'status'
    ];
}
