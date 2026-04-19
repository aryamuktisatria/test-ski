<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuotaStand extends Model
{
    protected $table = 'tbl_quota_stand';
    protected $fillable = ['kd_stand', 'nama_stand', 'quota'];
}
