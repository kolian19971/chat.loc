<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\User;

class Message extends Model
{
    protected $table = "messages";
    protected $guarded = [];

    private static function getNewMessCount($fromUserId, &$messToUserArray)
    {

        $count = 0;

        if (count($messToUserArray)) {

            foreach ($messToUserArray as $message)
                if ($message->from_id == $fromUserId && $message->is_new == 1)
                    $count++;
        }

        return $count;

    }


    public static function setNewMessCount($chatUsers, $currUserId)
    {

        $messToUserArray = self::where('to_id', $currUserId)->where('is_new', 1)->get();

        if (count($chatUsers)) {
            foreach ($chatUsers as $key => $chatUser) {
                $chatUsers[$key]->newCount = self::getNewMessCount($chatUser->id, $messToUserArray);
            }
        }

        return $chatUsers;
    }

    public static function getChatWithUser($chatUserId, $currUserId, $latestCount = 20)
    {

        $arrayIds = array($chatUserId, $currUserId);

        $messages = self::whereIn('from_id', $arrayIds)
            ->whereIn('to_id', $arrayIds)
            ->orderBy('created_at', 'DESC');

        if ($latestCount !== false) {
            $messages = $messages->take($latestCount);
        }

        $messages = $messages->get()
            ->reverse();

        self::setViewMess($messages, $currUserId);

        return [
            'toUser' => User::whereId($chatUserId)->first(),
            'messages' => $messages
        ];

    }


    private static function setViewMess(&$messages, $currUserId)
    {

        $messIds = [];

        if (count($messages)) {

            foreach ($messages as $message) {

                if ($message->to_id == $currUserId && $message->is_new == 1) {
                    $messIds[] = $message->id;
                }

            }

        }

        if (count($messIds))
            self::whereIn('id', $messIds)->update(['is_new' => 0]);


    }

    public static function getMessBeforeId($beforeMessId, $messages, $count = 20)
    {

        $beforeMessageKey = null;
        $messArray = [];

        if (count($messages)) {

            foreach ($messages as $key => $message) {

                if ($message->id == $beforeMessId) {
                    $beforeMessageKey = $key;
                    break;
                }

            }

        }

        if (isset($beforeMessageKey)) {

            $messages = $messages->reverse();

            $messages = $messages->slice(++$beforeMessageKey, $count);

            $messArray = $messages->reverse();

        }


        return $messArray;

    }


    public static function getMessFromId($lastShowMessId, $messages)
    {
        $fromMessKey = null;

        $messArray = [];

        if (count($messages)) {

            foreach ($messages as $key => $message) {

                if ($message->id == $lastShowMessId) {
                    $fromMessKey = $key;
                    break;
                }

            }

        }

        if (isset($fromMessKey)) {

            $fromMessKey--;

            for ($i = $fromMessKey; $i >= 0; $i--) {

                $messArray[] = $messages[$i];

            }

        }


        return $messArray;

    }


}