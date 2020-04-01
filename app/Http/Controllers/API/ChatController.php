<?php

namespace App\Http\Controllers\API;

use Auth;
use App\Http\Controllers\Controller;
use App\Chat;
use App\Tournament;
use App\Player;
use App\Events\ChatEvent;
use Illuminate\Http\Request;

class ChatController extends Controller
{

    public function fetchAllMessages(Tournament $tournament)
    {
      return Chat::where('tournament_id', $tournament->id)->with('user')->get();
    }

    public function sendMessage(Tournament $tournament, Request $request)
    {

      $chat = Auth::user()
                    ->messages()
                    ->create([
                      'message' => $request->message,
                      'tournament_id' => $tournament->id]);

      broadcast(new ChatEvent($chat->load('user')))->toOthers();

      return ['status' => 'success'];
    }
}
