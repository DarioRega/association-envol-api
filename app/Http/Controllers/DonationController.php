<?php

namespace App\Http\Controllers;

use App\Services\DonationsService;
use Illuminate\Http\Request;

class DonationController extends Controller
{

    protected $donationsService;

    public function __construct(DonationsService $donationsService)
    {
        $this->donationsService = $donationsService;
    }

    public function create_checkout_session(Request $request){
        $data = $request->only([
            'price',
            'client_session',
            'email',
        ]);
        $sessionId =  $this->donationsService->create_checkout_session($data) ;
     return response()->json(['id' => $sessionId]);
    }


    public function stripe_hooks(Request $request){
        $event = $request->getContent();
        // Parse the message body and check the signature
        $webhookSecret = config('env_variables.stripe_webhook_secret');
    if ($webhookSecret) {
        try {
            $event = \Stripe\Webhook::constructEvent(
                $request->getContent(),
                $request->header('stripe-signature'),
                $webhookSecret
            );
        } catch (\Exception $e) {
            return response()->json([ 'error' => $e->getMessage() ], 403);
        }
    } else {
        $event = $request->getContent();
    }
    $type = $event['type'];
    $object = $event['data']['object'];

    switch ($type) {
        // TODO LISTEN WHEN CUSTOMER UPDATE HIS PROFILE TO UPDATE OUR DB RECORD
        case 'checkout.session.completed':
            $donor = $this->donationsService->saveNewDonor($object);
            // TODO create account and NOTIFY
            break;

        case 'customer.subscription.deleted':
            $customer = $this->donationsService->cancelSubscription($object);
            if($customer){
                return response()->json($customer);
            } else {
                return response()->json(['message' => 'Customer subscription not found'], 404);
            }
            // TODO notify envol donators has stopped
            break;

        default:
            // Unhandled event type
    }

    return response()->json([ 'status' => 'success' ]);
    }

    public function thank_you(Request $request){
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

        $result = $this->donationsService->notify_new_donation($data);
        return response()->json($result);
    }
}
