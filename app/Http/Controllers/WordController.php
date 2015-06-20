<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Contracts\Filesystem\Cloud;
use Illuminate\Http\Request;
use App\Word;
use App\Meaning;
use App\WordRegister;
use App\Asset;
use Illuminate\Support\Facades\File;

class WordController extends Controller {

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
        return view('WordCreate');
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


        $wordData = [
            'name' => $request->get('name'),
            'phonentic' => $request->get('phonentic'),
            'word_type' => $request->get('word_type'),
            'asset_id' => $asset->id
        ];

        $newWord = Word::create($wordData);

        $meaningData = $request->get('meaning')[0];
        $meaningData['word_id'] = $newWord->id;
        $newMeaning = Meaning::create($meaningData);

        return redirect()->back();
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

    public function checkForDuplication(Request $request)
    {
        $wordListStr = $request->get('wordList');
        $wordListStr= preg_replace('/\s+/', '', $wordListStr);
        $wordListStr = strtolower($wordListStr);
        $wordArray = explode(',', $wordListStr);
        $duplicatedArray = array();
        foreach ($wordArray as $wordName) {
            $word = Word::where('name', $wordName)->first();
            if($word){
                array_push($duplicatedArray, $word->name);
            }
        }
        foreach ($wordArray as $wordName) {
            $wordRegister = WordRegister::where('name', $wordName)->first();
            if($wordRegister){
                array_push($duplicatedArray, $wordRegister->name);
            }
        }
        $returnStr = "";
        foreach ($duplicatedArray as $wordStr) {
           if($returnStr == "")
               $returnStr = $wordStr;
            else
                $returnStr = $returnStr.', '.$wordStr;
        }
        return $returnStr;
    }
    public function registerWords(Request $request)
    {
        $wordListStr = $request->get('wordList');
        $wordListStr= preg_replace('/\s+/', '', $wordListStr);
        $wordListStr = strtolower($wordListStr);
        $wordArray = explode(',', $wordListStr);

        $freelancerName = $request->get('freelancerName');
        $duplicatedArray = array();
        foreach ($wordArray as $wordName) {
            $word = Word::where('name', $wordName)->first();
            $wordRegister = WordRegister::where('name', $wordName)->first();
            if($word || $wordRegister){
                array_push($duplicatedArray, $word->name);
            }
            else{
                $newWordRegister = WordRegister::create(['name' => $wordName, 'freelancer_name'=> $freelancerName]);
            }
        }
        $returnStr = "";
        foreach ($duplicatedArray as $wordStr) {
            if($returnStr == "")
                $returnStr = $wordStr;
            else
                $returnStr = $returnStr.', '.$wordStr;
        }
        return $returnStr;

    }

}
