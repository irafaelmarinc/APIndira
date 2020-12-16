<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
    use HasFactory;

    protected $connection = 'indira';

    protected $primaryKey = 'id';
    protected $table      = 'staff';

    protected $fillable = [];

    protected $hidden = ['created_at', 'updated_at'];

    protected $casts = [];

    public $timestamps = false;

    /* Relationships */
}
