<?php

namespace App;

use Illuminate\Database\Eloquent\Model as Eloquent;

class ScheduledEvent extends Eloquent
{
    public $timestamps = false;

    public function log($command, $output)
    {
        $this->command = $command;
        $this->output = $output;
        $this->save();
    }
}
