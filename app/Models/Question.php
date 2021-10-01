<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'question',
        'user_id',
        'category_id',
        'is_active'
    ];

    /**
     * Get the user that owns the question.
     */
    public function user() {
        return $this->belongsTo(User::class);
    }
}
