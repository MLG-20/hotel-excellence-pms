<?php

namespace App\Enums;

enum Role: string
{
    case Directeur      = 'directeur';
    case Receptionniste = 'receptionniste';
    case Gouvernante    = 'gouvernante';
    case Client         = 'client';
}
