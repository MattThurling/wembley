<?php

namespace App\Http\Controllers;

use Auth;
use App\Chat;
use App\Tournament;
use App\Player;
use App\Events\ChatEvent;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function fetchAllMessages(Tournament $tournament)
    {
      return Chat::with('player.user')->get();
    }

    public function sendMessage(Tournament $tournament, Request $request)
    {

      $chat = Player::where('user_id', Auth::id())
                      ->where('tournament_id', $tournament->id)
                      ->first()
                      ->messages()
                      ->create([
                        'message' => $request->message]);

      broadcast(new ChatEvent($chat->load(['player', 'player.user'])))->toOthers();

      return ['status' => 'success'];
    }
}
