<?php

namespace App\Http\Controllers;

use App\Models\Interval;
use App\Models\MainAmount;
use App\Services\StripeService;
use Illuminate\Http\Request;

class ProductController extends Controller
{

    protected $stripeService;

    public function __construct(StripeService $stripeService)
    {
        $this->stripeService = $stripeService;
    }

    public function index(){
        $allPrices = $this->stripeService->getAllPrices();
        return response()->json($allPrices);

        $one_time = [];
        $month = [];
        $year = [];

        foreach ($allPrices as $price){
            if($price['type'] == 'one_time'){
                array_push($one_time, $price);
            } else {
                if($price['recurring']['interval'] == 'year'){
                    array_push($year, $price);
                }
                if($price['recurring']['interval'] == 'month'){
                    array_push($month, $price);
                }
            }
        }
        return response()->json(['one_time' => $one_time, 'month' => $month, 'year' => $year]);
    }

    public function metadata(){
        $intervals = Interval::all();
        $amounts = MainAmount::all();
        return response()->json(['intervals' => $intervals, 'amounts' => $amounts]);
    }

    public function priceCreate(Request $request) {
        $amount = $request->input('amount');
        $interval = $request->input('interval');
    }
    public function findOrCreate(Request $request){
        $selected_amount = $request->input('selected_amount');
        $selected_interval = $request->input('selected_interval');

        $price = $this->stripeService->findOrCreatePrice($selected_amount, $selected_interval);
        return response()->json($price);
    }

    public function session(Request $request){
        $sessionId =  $this->stripeService->createSession($request->input('price'), $request->input('sessionId')) ;
     return response()->json(['id' => $sessionId]);
    }

    //TODO ADD METHOD TO GET ONLY THE MAIN FOR THE FRONT
}
