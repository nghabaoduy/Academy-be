<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Contracts\Filesystem\Cloud;
use Illuminate\Http\Request;
use App\Asset;
use Illuminate\Support\Facades\File;

class AssetController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		//
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//
        return view('AssetCreate');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(Request $request, Cloud $cloud)
	{
		//
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

        return 'id = '.$asset->id;
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

}
