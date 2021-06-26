<?php

namespace App\Http\Controllers;

use App\Mail\DonationNotificationToEnvol;
use App\Services\DonationsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Config;

class DonationController extends Controller
{

    protected $donationsService;

    public function __construct(DonationsService $donationsService)
    {
        $this->donationsService = $donationsService;
    }

    public function index(){
        return response()->json($this->donationsService->getAll());
    }

    public function session(Request $request){
        $data = $request->only([
            'price',
            'client_session',
            'email',
        ]);
        $sessionId =  $this->donationsService->createCheckoutSession($data) ;
     return response()->json(['id' => $sessionId]);
    }

    public function stripeHooks(Request $request){
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
        case 'checkout.session.completed':
            $this->donationsService->saveNewDonor($object);
            // Payment is successful and the subscription is created.
            // You should provision the subscription and save the customer ID to your database.

            break;
        case 'invoice.paid':
            // Continue to provision the subscription as payments continue to be made.
            // Store the status in your database and check when a user accesses your service.
            // This approach helps you avoid hitting rate limits.
            break;
        case 'invoice.payment_failed':
            // The payment failed or the customer does not have a valid payment method.
            // The subscription becomes past_due. Notify your customer and send them to the
            // customer portal to update their payment information.
            break;
        // ... handle other event types
        default:
            // Unhandled event type
    }

    return response()->json([ 'status' => 'success' ]);
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
            Mail::to(config('env_variables.contact_mail_to'))->send(new DonationNotificationToEnvol($data));
        } catch (\Swift_TransportException $e) {
            Log::warning($e->getMessage());
            $result = [
                'status' => 400,
                'message' => "Une erreur est survenue durant l'envoi du mail de confirmation. Cependant votre donation à été prise en compte."
            ];
        }
        return response()->json($result);
    }

    public function manage(){
        //
    }

}
