<?php

namespace Model\Enums;
enum ItemQuality: string
{
    case VeryGood = "VeryGood";
    case Good = "Good";
    case Mediocre = "Mediocre";
    case Bad = "Bad";
    case Rotten = "Rotten";
    case UNKNOWN = "UNKNOWN";
}