<?php

namespace Validators;
class ItemValidator
{
    static function validate($data): bool
    {
        if(sizeof($data) != 4) return false;
        if(!$data["name"] ?? !$data['quantity'] ?? !$data['quality'] ?? !$data['price']) return false;
        if($data['quantity'] < 0) return false;
        if($data['price'] < 0.0) return false;
        return true;
    }
}