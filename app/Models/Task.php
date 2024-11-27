<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Task extends Model
{
    use HasFactory;

    protected $guarded = [
        "id"
    ];

    protected static function booted()
    {
        static::creating(function ($task) {
            if (auth()->check()) {
                $task->user_id = auth()->user()->id;
            }
        });

        static::addGlobalScope('user_id', function (Builder $builder) {
            $builder->where('user_id', auth()->user()->id);
        });
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
}
