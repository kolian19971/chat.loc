<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use App\Models\Message;
use App\User;
use Auth;
use Lang;

class HomeController extends BaseController
{

    public function index()
    {

        $title = Lang::get('chat.homeTitle');

        return view('frontend.index', compact([
            'title'
        ]));
    }

    public function getChat($user_id)
    {

        $chatObj = Message::getChatWithUser($user_id, $this->getUser()->id);

        $title = Lang::get('chat.chatWith') . ' ' . $chatObj['toUser']->email;

        return view('frontend.index', compact([
            'title',
            'chatObj'
        ]));

    }


    public function sendMessage(Request $request)
    {

        $createArray = $request->except(['_token']);
        $createArray['from_id'] = $this->getUser()->id;

        $toUser = User::whereId($createArray['to_id'])->first();

        if ($toUser)
            Message::create($createArray);

        return back();
    }


    public function getMessUpdate(Request $request)
    {

        $chatUserId = $request->get('toUserId');
        $lastShowMessId = $request->get('lastShowMessageId');
        $messageHtml = '';
        $chatObj = false;

        if ($chatUserId > 0) {

            $chatObj = Message::getChatWithUser($chatUserId, $this->getUser()->id);

            if ($lastShowMessId > 0 || count($chatObj['messages']) == 1) {

                if ($lastShowMessId > 0) {
                    $chatObj['messages'] = Message::getMessFromId($lastShowMessId, $chatObj['messages']);
                }

                $messageHtml = view('frontend._part.messages', compact([
                    'chatObj'
                ]))->render();
            }

        }

        $chatUsers = Message::setNewMessCount($this->getChatUsers(), $this->getUser()->id);

        return [
            'messageHtml' => $messageHtml,
            'chatUsersHtml' => view('frontend._part.chat_users', compact([
                'chatObj',
                'chatUsers'
            ]))->render()
        ];

    }


    public function loadMessages(Request $request)
    {

        $messageHtml = '';

        $chatObj = Message::getChatWithUser($request->get('toUserId'), $this->getUser()->id, false);

        $chatObj['messages'] = Message::getMessBeforeId($request->get('toMessageId'), $chatObj['messages'], $request->get('count'));

        if (count($chatObj['messages'])) {

            $messageHtml = view('frontend._part.messages', compact([
                'chatObj'
            ]))->render();

        }

        return [
            'messageHtml' => $messageHtml
        ];

    }


}