<?php namespace App\Http\Controllers\Api;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\User;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\ApiRegisterRequest;

class UserController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		//
        /*
        User::create([
            'username' => 'nghabaoduy',
            'password' => bcrypt('000000'),
            'email' => 'nguyen.habaoduy@gmail.com',
            'first_name' => 'Duy',
            'last_name' => 'Nguyen'
        ]);*/

        return response(null);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		//
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}


    public function login(LoginRequest $request) {
        if (Auth::attempt(['username' => $request->get('username'), 'password' => $request->get('password')])) {
            $data = Auth::user();
            $data['auth'] = env('APP_KEY');
            return response(json_encode($data));
        }
        return response(json_encode(['message' => 'Invalid credentials']), 400);
    }

    public function register(ApiRegisterRequest $request) {
        $newUser = new User();
        $newUser->username = $request->get('username');
        $newUser->password = bcrypt($request->get('password'));
        $newUser->profile_name = $request->get('profile_name');
        $newUser->email = $request->get('username');
        $newUser->save();
        return response($newUser, 200);
    }
}
