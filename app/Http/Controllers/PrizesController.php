<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use App\Models\Prize;
use App\Http\Requests\PrizeRequest;
use Illuminate\Http\Request;



class PrizesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        $prizes = Prize::all();

        return view('prizes.index', ['prizes' => $prizes]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function create()
    {
        return view('prizes.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  PrizeRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(PrizeRequest $request)
    {
        $remainingProbability = 100 - Prize::sum('probability');
        $probability = floatval($request->input('probability'));
    
        if ($probability > $remainingProbability) {
            return redirect()->back()->withInput()->with('probability_error', 'The probability field must not be greater than the remaining probability.');
        }
    
        $prize = new Prize;
        $prize->title = $request->input('title');
        $prize->probability = $probability;
        $prize->save();
    
        return to_route('prizes.index');
    }
    


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\View\View
     */
    public function edit($id)
    {
        $prize = Prize::findOrFail($id);
        return view('prizes.edit', ['prize' => $prize]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  PrizeRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(PrizeRequest $request, $id)
    {
        $prize = Prize::findOrFail($id);
        $remainingProbability = 100 - Prize::sum('probability');
        $newPprobability = floatval($request->input('probability'));
       

        if ($newPprobability > $remainingProbability) {
            return redirect()->back()->withInput()->with('probability_error', 'The probability field must not be greater than the remaining probability.');
        }
        $prize->title = $request->input('title');
        $prize->probability = $newProbability;
        $prize->save();
     
        return to_route('prizes.index');
    }
    

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $prize = Prize::findOrFail($id);
        $prize->delete();

        return to_route('prizes.index');
    }
    public function simulate(Request $request)
    {
        $numberOfPrizes = $request->input('number_of_prizes');
        for ($i = 0; $i < $numberOfPrizes; $i++) {
            Prize::nextPrize();
        }
        return redirect()->route('prizes.index');
    }
    

    public function reset()
    {
        Prize::query()->update([
            'awarded_count' => 0,
        ]);
        return to_route('prizes.index');
    }
}
