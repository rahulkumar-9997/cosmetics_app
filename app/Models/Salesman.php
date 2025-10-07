<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Salesman extends Model
{
    use HasFactory;
    protected $table = 'salesmen';
    protected $fillable = [
        'user_id',
        'name',
        'email',
        'phone',
        'profile_photo',
        'address',
        'status',
    ];
    protected $casts = [
        'status' => 'boolean',
    ];
    protected $attributes = [
        'status' => 1,
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
