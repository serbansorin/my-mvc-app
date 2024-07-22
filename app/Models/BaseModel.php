<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Cache\Cacheable;

class BaseModel extends Model
{
    use Cacheable;

    // Add any common functionality or custom methods for your models here
}