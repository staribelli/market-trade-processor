<?php

namespace App\Http\Controllers;

use App\Jobs\DeliverMessage;
use App\User;
use Illuminate\Http\Request;
use App\Jobs\SendReminderEmail;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    /**
     * Send a reminder e-mail to a given user.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function sendReminderEmail(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $this->dispatch(new DeliverMessage($user));
    }
}
