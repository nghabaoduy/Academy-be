<?php namespace App\Http\Controllers\Api;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Set;
use App\SetScore;

class SetScoreController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index($set_id, Request $request)
	{
		//
        $query = SetScore::where('user_id', $request->get('user_id'))->where('set_id', $set_id)->orderBy('score', 'DESC')->get();
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
	public function store($set_id,Request $request)
	{

        $set = Set::where('id', $set_id)->first();
        $score = $request->get('score');

        if (!$set) {
            dd('err');
        }

        $request->merge(['set_id' => $set_id]);
        $setScore = SetScore::where('set_id', $set_id)->first();
        if(!$setScore)
        {
            $setScore = SetScore::create($request->all());
        }
        else{
            if(intval($setScore->score) < intval($score))
            {
                $setScore->score = $score;
                $setScore->save();
            }
        }
        return response($setScore);
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
