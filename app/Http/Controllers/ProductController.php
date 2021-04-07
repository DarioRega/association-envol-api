<?php

namespace App\Http\Controllers;

use App\Mail\DonationNotificationToEnvol;
use App\Mail\ScholarshipRequestMail;
use App\Models\Interval;
use App\Models\MainAmount;
use App\Services\StripeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

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
        $data = $request->only([
            'price',
            'client_session',
            'email',
        ]);
        $sessionId =  $this->stripeService->createSession($data) ;
     return response()->json(['id' => $sessionId]);
    }

    public function thankYou(Request $request){
        $data = $request->only([
            'amount',
            'interval',
            'payment_method',
            'full_name',
            'company_name',
            'email',
            'commentary',
            'created_at',
            'selectedInterval'
        ]);
        $result = [
            'status' => 204,
        ];

        try {
            Mail::to('dario.regazzoni@outlook.fr')->send(new DonationNotificationToEnvol($data));
        } catch (\Swift_TransportException $e) {
//            Log::warning($e->getMessage());
            $result = [
                'status' => 400,
                'message' => "Une erreur est survenue durant l'envoi du mail de confirmation. Cependant votre donation à été prise en compte."
            ];
        }
        return response()->json($result);
    }
}
