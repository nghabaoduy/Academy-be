<?php namespace App\Http\Controllers\Api;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Word;
use App\WordLearned;
use App\User;
use Illuminate\Http\Request;

class WordLearnedController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index(Request $request)
    {
        //
        $query = WordLearned::where('user_id', $request->get('user_id'))->get();
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
	public function store(Request $request)
    {
        $user_id = $request->get('user_id');
        $word_id = $request->get('word_id');
        $user = User::find($user_id);
        if (!$user) {
            return response(json_encode(['message' => 'User not found with Id '.$user_id]), 404);
        }

        $word = Word::find($word_id);
        if (!$word) {
            return response(json_encode(['message' => 'Word not found']), 404);
        }

        $oldWordLearned = WordLearned::where('user_id', $user_id)->where('word_id', $word_id)->first();

        if ($oldWordLearned) {
            return response(json_encode(['message' => 'Word is already learned']), 400);
        }

        $wordLearned = WordLearned::create($request->all());

        return response($wordLearned);
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
    public function uploadWordLearnedList(Request $request)
    {
        $wordIdListStr = $request->get('wordIdListStr');
        $wordIdArray = explode('|', $wordIdListStr);

        $user_id = $request->get('user_id');
        $user = User::find($user_id);
        if (!$user) {
            return response(json_encode(['message' => 'User not found with Id '.$user_id]), 404);
        }
        $wordLearnedArray = [];
        foreach ($wordIdArray as $wordId) {
            $word = Word::find($wordId);
            if (!$word) {
                return response(json_encode(['message' => 'Word not found']), 404);
            }
            $oldWordLearned = WordLearned::where('user_id', $user_id)->where('word_id', $wordId)->first();
            if(!$oldWordLearned) {
                $wordLearnedArray[] = WordLearned::create(['word_id' => $wordId, 'user_id' => $user_id]);
            }
        }
        return $wordLearnedArray;
    }

}
