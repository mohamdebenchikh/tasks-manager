<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $table = 'tasks';
    protected $fillable = ['user_id','title','slug','notes','status','due_date','tags'];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function todos(){
        return $this->hasMany(Todo::class);
    }
}
