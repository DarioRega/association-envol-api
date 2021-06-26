<?php

namespace App\Http\Controllers;

use App\Mail\DonationNotificationToEnvol;
use App\Models\Interval;
use App\Models\MainAmount;
use App\Models\PaypalPlan;
use App\Services\DonationsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ProductController extends Controller
{

    protected $donationService;

    public function __construct(DonationsService $donationService)
    {
        $this->donationsService = $donationService;
    }

    public function index(){
        $allPrices = $this->donationsService->getAllPrices();
        // TODO CHECK WHY
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

        $price = $this->donationsService->findOrCreatePrice($selected_amount, $selected_interval);
        return response()->json($price);
    }

    public function paypal_plans($name){
        return PaypalPlan::where('name', $name)->first();
    }

    public function create_paypal_plan(Request $request){
        request()->validate([
            'plan_id' => ['required'],
            'name' => ['required'],
        ]);
       $plan = PaypalPlan::create([
            'plan_id' => $request->plan_id,
            'name' => $request->name,
        ]);

       return response()->json($plan);
    }
}
