<?php

namespace App\Http\Controllers;

use Google\Client;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class GoogleAuthController extends Controller
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client();
        $this->client->setAuthConfig(storage_path('app/google-calendar/client_secret.json'));
        $this->client->addScope('https://www.googleapis.com/auth/calendar');
        $this->client->setAccessType('offline'); // Para obter refresh token
        $this->client->setPrompt('consent');

        $this->client->setHttpClient(new \GuzzleHttp\Client([
            'verify' => false,
        ]));
    }

    public function redirectToGoogle()
    {
        $authUrl = $this->client->createAuthUrl();
        return redirect()->to($authUrl);
    }

    public function handleGoogleCallback(Request $request)
    {
        $this->client->authenticate($request->get('code'));
        $token = $this->client->getAccessToken();

        // Armazenar o token no banco de dados
        $user = Auth::user();
        $user->google_access_token = $token['access_token'];
        $user->google_refresh_token = $token['refresh_token'] ?? null;
        $user->save();

        return redirect()->route('home')->with('message', 'Conta Google conectada com sucesso!');
    }
}
