<?php namespace App\Http\Controllers\Api;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Package;
use Illuminate\Http\Request;
use App\UserPackage;
use App\User;
use App\OrderHistory;

class UserPackageController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index(Request $request)
	{
		//

        $query = UserPackage::with( 'package.sets.asset', 'package.asset');

        if ($request->has('user_id')) {
            $query = $query->where('user_id', $request->get('user_id'));
        }
        $query = $query->get();

        return response($query);
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

    public function purchasePackage(Request $request) {
        $user_id = $request->get('user_id');
        $package_id = $request->get('package_id');
        //Checking credit

        $user = User::find($user_id);

        if (!$user) {
            return response(json_encode(['message' => 'User not found']), 404);
        }

        $pack = Package::find($package_id);

        if (!$pack) {
            return response(json_encode(['message' => 'Package not found']), 404);
        }

        $package = UserPackage::where('user_id', $user_id)->where('package_id', $package_id)->first();

        if ($user->credit  < $pack->price) {
            return response(json_encode(['message' => 'Insufficient credit to purchase']), 400);
        }



        if (!$package) {
            $date = date('Y-m-d H:i:s');

            $package = UserPackage::create(['user_id' => $user_id,
                'package_id' => $package_id,
                'purchase_type' => 'buy',
                'expired_at' => $date
            ]);
        } else {
            if ($package->purchase_type == 'buy') {
                return response(json_encode(['message' => 'You have purchased this product']), 400);
            }

            $package->purchase_type = 'buy';
            $package->save();
        }

        $last_credit = $user->credit;
        $user->credit = $user->credit - $pack->price;
        $user->save();

        $data = [
            'user_id' => $user_id,
            'order_ref' => $this->generateRandomString('15'),
            'package_name' => $pack->name,
            'package_price' => $pack->price,
            'last_credit' => $last_credit,
            'after_buy_credit' => $user->credit
        ];

        OrderHistory::create($data);
        return json_encode($user);
    }

    public function tryPackage(Request $request) {
        $user_id = $request->get('user_id');
        $package_id = $request->get('package_id');

        $user = User::find($user_id);

        if (!$user) {
            return response(json_encode(['message' => 'User not found']), 404);
        }

        $pack = Package::find($package_id);

        if (!$pack) {
            return response(json_encode(['message' => 'Package not found']), 404);
        }

        $package = UserPackage::where('user_id', $user_id)->where('package_id', $package_id)->first();

        if (!$package) {
            $package = UserPackage::create(['user_id' => $user_id, 'package_id' => $package_id, 'purchase_type' => 'try']);
        } else {
            if ($package->purchase_type == 'try') {
                return response(json_encode(['message' => 'You have purchased this product']), 400);
            }
            $package->purchase_type = 'try';
            $package->save();
        }

        return json_encode($package);
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

    function setPackageScore(Request $request) {

        $user_id = $request->get('user_id');
        $package_id = $request->get('package_id');
        $score = $request->get('score');
        //Checking credit

        $user = User::find($user_id);

        if (!$user) {
            return response(json_encode(['message' => 'User not found']), 404);
        }

        $package = UserPackage::where('user_id', $user_id)->where('package_id', $package_id)->first();

        if (!$package) {
            return response(json_encode(['message' => 'UserPackage not found']), 404);
        }

        if (intval(!$package->score)  >= intval($score)) {
            return response(json_encode(['message' => 'Old score is high or equal']), 404);
        } else {
            $package->score = $score;
            $package->save();
        }
        return json_encode($package);
    }

}
