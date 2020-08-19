<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Message;
use App\User;
use Auth;
use View;

class BaseController extends Controller
{

    private $user;

    public function __construct()
    {
        $this->middleware('auth');

        $this->middleware(function ($request, $next) {
            $this->user = Auth::user();

            View::share([
                'currUser' => $this->getUser(),
                'chatUsers' => Message::setNewMessCount($this->getChatUsers(), $this->getUser()->id)
            ]);

            return $next($request);
        });
    }

    public function getUser()
    {
        return $this->user;
    }

    public function getChatUsers()
    {
        return User::where('id', '<>', $this->getUser()->id)->get();
    }

}