<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoanFormDocument extends Model
{
    protected $fillable = [
        'path',
        'original_name',
    ];
}
