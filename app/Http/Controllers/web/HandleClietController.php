<?php

namespace App\Http\Controllers\web;

use Illuminate\Http\Request;
use Laravel\Passport\Passport;
use App\Http\Controllers\Controller;
use Laravel\Passport\ClientRepository;
use Laravel\Passport\Http\Rules\RedirectRule;
use Illuminate\Contracts\Validation\Factory as ValidationFactory;

class HandleClietController extends Controller
{
    protected $clients;
    protected $validation;
    protected $redirectRule;

    public function __construct(
        ClientRepository $clients,
        ValidationFactory $validation,
        RedirectRule $redirectRule
    ) {
        $this->clients = $clients;
        $this->validation = $validation;
        $this->redirectRule = $redirectRule;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $clients = Passport::client()->get();

        $datas = $clients->makeVisible('secret');

        return view('app.client.index', [
            'clients' => $datas,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        return view('app.client.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validation->make($request->all(), [
            'name' => 'required|max:191',
            'redirect' => ['required', $this->redirectRule],
        ])->validate();

        $client = $this->clients->create(
            $request->user()->getAuthIdentifier(), $request->name, $request->redirect,
            null, false, false, (bool) $request->input('confidential', true)
        );

        return redirect()->route('oauth.client.index')->with('success', 'Oauth client berhasil ditambahkan');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $clientId
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $clientId) 
    {
        $client = $this->clients->find($clientId);

        if (!$client) {
            return redirect()->route('oauth.client.index')->with('error', 'Oauth client tidak ditemukan');
        }
        
        return view('app.client.edit', compact('client'));
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $clientId
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $clientId)
    {
        $client = $this->clients->find($clientId);

        if (!$client) {
            return redirect()->route('oauth.client.index')->with('error', 'Oauth client tidak ditemukan');
        }

        $this->validation->make($request->all(), [
            'name' => 'required|max:191',
            'redirect' => ['required', $this->redirectRule],
        ])->validate();

        $this->clients->update(
            $client, $request->name, $request->redirect
        );

        return redirect()->route('oauth.client.index')->with('success', 'Oauth client berhasil diubah');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $clientId
     * @return \Illuminate\Http\Response
     */
    public function destroy($clientId)
    {
        $client = $this->clients->find($clientId);
        
        if (!$client) {
            return redirect()->route('oauth.client.index')->with('error', 'Oauth client tidak ditemukan');
        }

        $delete = $this->clients->delete($client);

        $deleteData = \Illuminate\Support\Facades\DB::table('oauth_clients')->where('id', $clientId)->delete();

        if ($deleteData) {
            return redirect()->route('oauth.client.index')->with('success', 'Oauth client berhasil dihapus');
        }

    }
}
