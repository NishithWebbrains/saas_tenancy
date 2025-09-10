<?php 

namespace POS\SwiftPos\App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $connection = 'tenant';  // 👈 tells Laravel to use tenant DB
    protected $fillable = ['sku', 'name'];
}
