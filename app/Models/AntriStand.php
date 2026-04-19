<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AntriStand extends Model
{
    protected $table = 'tbl_antri_stand';
    protected $fillable = ['nama', 'email', 'tanggal_pesan', 'kd_stand', 'nomor_antri'];
}
