<?php

namespace App\Enums;

enum InteractionTypes: string
{
    case EMAIL = "Email";
    case LETTER = "Courrier";
    case INTERVIEW = "Entretien physique";
    case PHONE_INTERVIEW = "Entretien téléphonique";

}
