<?php namespace App\Http\Controllers\Api;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\User;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\ApiRegisterRequest;
use App\Http\Requests\ApiChangePasswordRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Http\Requests\ApiForgotPasswordRequest;
use Illuminate\Contracts\Filesystem\Cloud;
use App\Asset;
use Illuminate\Support\Facades\File;

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
    public function loginWithFBId(Request $request) {
        $user = User::with('asset')->where('username', $request->get('username'))->where('facebook_id', $request->get('facebook_id'))->first();
        if(!$user)
        {
            return response(json_encode(['message' => 'Invalid credentials']), 400);
        }else {
            return response($user);
        }
    }
    public function loginWithGGPId(Request $request) {
        $user = User::with('asset')->where('username', $request->get('username'))->where('ggp_id', $request->get('ggp_id'))->first();
        if(!$user)
        {
            return response(json_encode(['message' => 'Invalid credentials']), 400);
        }else {
            return response($user);
        }
    }


    public function register(ApiRegisterRequest $request) {
        $newUser = new User();
        $newUser->username = $request->get('username');
        $newUser->password = bcrypt($request->get('password'));
        $newUser->profile_name = $request->get('profile_name');
        $newUser->email = $request->get('username');
        $newUser->facebook_id = $request->get('facebook_id');
        $newUser->ggp_id = $request->get('ggp_id');
        $newUser->save();
        return response($newUser, 200);
    }

    public function changePassword(ApiChangePasswordRequest $request) {
        $newUser = User::with('asset')->where('username', $request->get('username'))->first();
        if (!$newUser){
            return response(json_encode(['message' => 'User not found']), 404);
        }
        if (!Hash::check($request->get('current_password'), $newUser->password)){
            return response(json_encode(['message' => 'Invalid current password']), 400);
        }
        $newUser->password = bcrypt($request->get('password'));
        $newUser->save();
        return response($newUser, 200);
    }

    public function forgotPassword(ApiForgotPasswordRequest $request) {
        $user = User::where('username', $request->get('username'))->first();
        if (!$user) {
            //error
            return response(json_encode(['message' => 'user not found']), 404);
        }

        $newPassword = $this->generateRandomString(6);

        $user->password = bcrypt($newPassword);
        $user->update();

        Mail::send('emails.forgotPassword', ['password' => $newPassword, 'phone' => $user->username], function($message) use ($user)
        {
            $message->to($user->email)->subject('Forgot Password!');
        });
        return response(json_encode(['message' => 'Sent email to '.$user->email]));
    }

    function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
    function changeProfileName(Request $request)
    {
        $user = User::with('asset')->where('username', $request->get('username'))->first();
        if (!$user) {
            //error
            return response(json_encode(['message' => 'user not found']), 404);
        }
        $user->profile_name = $request->get('profile_name');
        $user->save();

        return response($user, 200);
    }
    function uploadUserAvatar(Request $request, Cloud $cloud)
    {
        $user = User::where('username', $request->get('username'))->first();
        if (!$user) {
            //error
            return response(json_encode(['message' => 'user not found']), 404);
        }

        //get image
        if (!$request->hasFile('image')) {
            dd('error file not found');
        }

        $file = $request->file('image');

        if (strtolower($file->getClientOriginalExtension()) != 'jpg' && strtolower($file->getClientOriginalExtension()) != 'png') {
            dd('error file extension');
        }

        $fileName = $this->generateRandomString(100).'.'.$file->getClientOriginalExtension();

        $cloud->put(env('S3_FOLDER').'/'.$fileName, File::get($file));

        $asset = new Asset();
        $asset->index = env('S3_URL').env('S3_FOLDER').'/'.$fileName;
        $asset->type = $file->getClientOriginalExtension();
        $asset->save();

        $user->asset_id = $asset->id;

        $user->save();
        $user = User::with('asset')->where('username', $request->get('username'))->first();
        if (!$user) {
            //error
            return response(json_encode(['message' => 'user not found']), 404);
        }
        return response($user, 200);
    }
    function getUserWithUsername(Request $request)
    {
        $user = User::with('asset')->where('username', $request->get('username'))->first();
        if (!$user) {
            //error
            return response(json_encode(['message' => 'user not found']), 404);
        }
        return response($user, 200);
    }
}
