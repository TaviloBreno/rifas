<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class RaffleEntry extends Entity
{
    protected $dates = ['created_at', 'updated_at'];
    protected $casts = [
        'id'              => 'integer',
        'ticket_id'       => 'integer',
        'raffle_id'        => 'integer',
        'raffle_number_id'=> 'integer',
        'number'          => 'integer',
    ];
}
