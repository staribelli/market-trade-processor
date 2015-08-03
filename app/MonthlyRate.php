<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MonthlyRate extends Model
{
    /**
     * Sets the average rate as
     * sum_rate / tot_messages.
     * Doesn't allow to set avg_rate from outside the class.
     *
     * @return void
     */
    public function setAvgRateAttribute()
    {
        $this->attributes['avg_rate'] = $this->sum_rate / $this->tot_messages;
    }

    /**
     * Sets the attribute avg_rate which is not
     * settable from outside the class and save
     * the model to the database.
     *
     * @override Illuminate\Database\Eloquent\Model::save()
     * @param  array  $options
     * @return bool
     */
    public function save(array $options = [])
    {
        $this->setAvgRateAttribute();
        parent::save();
    }
}