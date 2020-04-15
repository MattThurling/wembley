<?php

namespace App\Actions;

use Auth;
use App\Chat;
use App\Tournament;
use App\Events\ChatEvent;

class SendSystemChatOnce
{
  public function do(Tournament $tournament, $message, $signature)
  {
    // Emit system message from the owner user so it only goes once
    if (Auth::id() == $tournament->owner_id) {

      if (!Chat::where('system_signature', $signature)->count()) {
        $chat = Auth::user()
                  ->messages()
                  ->create([
                    'message' => $message,
                    'tournament_id' => $tournament->id,
                    'system_signature' => $signature]);

        broadcast(new ChatEvent($chat->load('user')));
      }
    }
  }
}
