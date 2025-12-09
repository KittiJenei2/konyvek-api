<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;

class ApiService
{
    protected $baseUrl;

    public function __construct()
    {
        $this->baseUrl = 'http://localhost:8000/api'; 
    }

    protected function getClient()
    {
        $client = Http::withHeaders(['Accept' => 'application/json']);
        
        if (Session::has('api_token')) {
            $client->withToken(Session::get('api_token'));
        }

        return $client;
    }

    public function get($endpoint)
    {
        return $this->getClient()->get($this->baseUrl . $endpoint);
    }

    public function post($endpoint, $data, $files = [])
    {
        $client = $this->getClient();

        if (!empty($files)) {
            foreach ($files as $key => $file) {
                $client->attach($key, file_get_contents($file), $file->getClientOriginalName());
            }
        }

        return $client->post($this->baseUrl . $endpoint, $data);
    }

    public function patch($endpoint, $data)
    {
        return $this->getClient()->patch($this->baseUrl . $endpoint, $data);
    }

    public function delete($endpoint)
    {
        return $this->getClient()->delete($this->baseUrl . $endpoint);
    }

        public function login(Request $request, ApiService $api)
    {
        $response = $api->post('/users/login', [
            'email' => $request->email,
            'password' => $request->password,
        ]);

        if ($response->successful()) {
            // A kapott tokent elmentjük a Session-be
            $token = $response->json('token'); // Vagy ahogy az API visszaadja
            Session::put('api_token', $token);
            return redirect()->route('writers.index');
        }

        return back()->withErrors(['message' => 'Hibás adatok']);
    }

    public function logout()
    {
        Session::forget('api_token');
        return redirect()->route('login');
    }
}