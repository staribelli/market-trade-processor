<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CurrencyMessage extends Model
{
    /**
     * Set the time_placed attribute
     * as a php \DateTime object.
     *
     * @param string $value
     */
    public function setTimePlacedAttribute($value)
    {
        $this->attributes['time_placed'] = new \DateTime($value);
    }
}
