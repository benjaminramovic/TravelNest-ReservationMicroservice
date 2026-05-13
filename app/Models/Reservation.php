<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    //
    protected $fillable = [
        'user_id',
        'accommodation_id',
        'start_date',
        'end_date',
        'status',
        'total_price',
    ];

    public function payments(){
        return $this->hasMany(Payment::class);
    }

}
