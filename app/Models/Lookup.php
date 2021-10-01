<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lookup extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'parent_id',
        'lookup_type',
        'label',
        'slug',
        'sort_order',
        'is_default',
        'is_public',
        'is_active',
    ];
}
