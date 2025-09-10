<?php 

namespace POS\AbsPos\App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $connection = 'tenant';  // 👈 tells Laravel to use tenant DB
    protected $fillable = ['sku', 'name'];
}
