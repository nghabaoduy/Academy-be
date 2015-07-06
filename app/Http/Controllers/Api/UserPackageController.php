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
        if($request->get('needLoadPackage'))
            if($request->get('needLoadPackage') == "NO") {
            $query = UserPackage::where('user_id', $request->get('user_id'))->get();
        }
        $query = UserPackage::with('package.sets.asset', 'package.asset')->where('user_id', $request->get('user_id'))->get();
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
            $expiryDate = date('Y-m-d H:i:s');

            $packExpiration = $pack->expiry_time;
            if($packExpiration == 'FOREVER'){
                $expiryDate = date('Y-m-d H:i:s',strtotime($expiryDate . ' - 1 day'));
            }
            else{
                $expiryDate = date('Y-m-d H:i:s',strtotime($expiryDate . $packExpiration));
            }


            $package = UserPackage::create(['user_id' => $user_id,
                'package_id' => $package_id,
                'purchase_type' => 'buy',
                'expired_at' => $expiryDate
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
    public function renewPurchase(Request $request) {
        $user_package_id = $request->get('user_package_id');
        $package = UserPackage::find($user_package_id);
        if (!$package) {
            return response(json_encode(['message' => 'UserPackage not found']), 404);
        }

        $user_id = $package->user_id;
        $package_id = $package->package_id;

        $user = User::find($user_id);

        if (!$user) {
            return response(json_encode(['message' => 'User not found']), 404);
        }

        $pack = Package::find($package_id);

        if (!$pack) {
            return response(json_encode(['message' => 'Package not found']), 404);
        }


        if ($user->credit  < $pack->price) {
            return response(json_encode(['message' => 'Insufficient credit to purchase']), 400);
        }




        $expiryDate = date('Y-m-d H:i:s');

        $packExpiration = $pack->expiry_time;
        if($packExpiration == 'FOREVER'){
            $expiryDate = date('Y-m-d H:i:s',strtotime($package->created_at . ' - 1 day'));
        }
        else{
            $expiryDate = date('Y-m-d H:i:s',strtotime($expiryDate . $packExpiration));
        }


        $package->expired_at = $expiryDate;
        $package->save();


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
        if ($package->purchase_type == 'try') {
            return response(json_encode(['message' => 'UserPackage is a try record']), 404);
        }

        if (intval(!$package->score)  >= intval($score)) {
            return response(json_encode(['message' => 'Old score is high or equal']), 404);
        } else {
            $package->score = $score;
            $package->save();
        }
        return json_encode($package);
    }
    function getPackagesTryBuyStatus(Request $request) {

        $userPackageList = UserPackage::all();
        $packageList = Package::all();

        $returnArray = [];
        foreach ($packageList as $pack)
        {
            if (!$pack) {
                return response(json_encode(['message' => 'Package not found']), 404);
            }
            $tryCount = 0;
            $buyCount = 0;

            foreach($userPackageList as $userPack)
            {
                if($userPack->package_id == $pack->id)
                {
                    if($userPack->purchase_type == 'try')
                        $tryCount++;
                    if($userPack->purchase_type == 'buy')
                        $buyCount++;
                }

            }
            $status = [
                "package_id" => $pack->id,
                "try" => $pack->init_try_count + $tryCount,
                "buy" => $pack->init_buy_count + $buyCount,
            ];

            array_push($returnArray, $status);
        }
        return $returnArray;
    }

}
