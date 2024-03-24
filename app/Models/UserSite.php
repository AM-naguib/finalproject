<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserSite extends Model
{
    use HasFactory;
    protected $table = "user_sites";
    protected $fillable = [
        'site_name',
        "site_link" ,
        "post_link_selector",
        "post_title_selector",
        "user_id"
    ];
}
