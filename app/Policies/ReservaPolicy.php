<?php

namespace App\Policies;

use App\Models\Reserva;
use App\Models\User;

class ReservaPolicy
{
    public function view(User $user, Reserva $reserva): bool
    {
        return $user->hasRole(['admin', 'encargado']) || $user->id === $reserva->user_id;
    }

    public function cancel(User $user, Reserva $reserva): bool
    {
        return $user->hasRole(['admin', 'encargado']) || $user->id === $reserva->user_id;
    }
}
