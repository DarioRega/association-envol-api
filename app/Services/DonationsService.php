<?php

namespace App\Services;

use App\Mail\DonationNotificationToDonatorMail;
use App\Mail\DonationNotificationToEnvol;
use App\Repositories\DonationsRepository;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Stripe\StripeClient;
use Config;
\Stripe\Stripe::setApiKey(config('env_variables.stripe_secret'));

class DonationsService
{
    protected $stripe;
    protected $donationsRepository;
    public $FRONT_URL;
    public $STRIPE_MAIN_DONATIONS_PRODUCT_ID;
    public $STRIPE_CUSTOM_DONATIONS_PRODUCT_ID;

    public function __construct(DonationsRepository $donationsRepository)
    {
        $this->donationsRepository = $donationsRepository;

        $stripe_secret = config('env_variables.stripe_secret');

        $this->stripe = new StripeClient($stripe_secret);
        $this->FRONT_URL = config('env_variables.front_url');
        $this->STRIPE_MAIN_DONATIONS_PRODUCT_ID = config('env_variables.stripe_main_donations_product_id');
        $this->STRIPE_CUSTOM_DONATIONS_PRODUCT_ID = config('env_variables.stripe_custom_donations_product_id');

    }

    public function get_mains_prices(){
        return $this->stripe->prices->all(['product' => $this->STRIPE_MAIN_DONATIONS_PRODUCT_ID]);
    }

    public function get_all_prices(){
        $request = $this->stripe->prices->all();
        return $request['data'];
    }

    public function find_or_create_price($selected_amount_object, $selected_interval_object){
        $allPrices = $this->get_all_prices();
        $price = null;
        foreach ($allPrices as $stripe_price){
            if($stripe_price['unit_amount'] == $selected_amount_object['amount']){
                if($stripe_price['type'] == 'one_time' && $selected_interval_object['ref'] == 'one_time'){
                    $price = $stripe_price;
                }
                if($stripe_price['type'] === 'recurring') {
                    if($stripe_price['recurring']['interval'] == $selected_interval_object['ref']){
                        $price = $stripe_price;
                    }
                }
            }
        }
        if($price){
            return $price;
        }
        $price = $this->create_stripe_custom_price($selected_amount_object['amount'], $selected_interval_object['ref']);
        return $price;
    }

    public function create_stripe_custom_price($amount, $interval){
        $createdPrice = null;
        if($interval === 'one_time'){
            $createdPrice = $this->stripe->prices->create([
                'unit_amount' => $amount,
                'currency' => 'chf',
                'product' => $this->STRIPE_CUSTOM_DONATIONS_PRODUCT_ID,
            ]);
        } else {
            $createdPrice = $this->stripe->prices->create([
                'unit_amount' => $amount,
                'currency' => 'chf',
                'recurring' => ['interval' => $interval],
                'product' => $this->STRIPE_CUSTOM_DONATIONS_PRODUCT_ID,
            ]);
        }
        return $createdPrice;
    }

    public function create_checkout_session($data){
        $checkout_session = $this->stripe->checkout->sessions->create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price' => $data['price']['id'],
                'quantity' => 1,
            ]],
            'customer_email' => $data['email'],
            'mode' => $data['price']['type'] == 'one_time' ? 'payment' : 'subscription',
            'success_url' => $this->FRONT_URL . '/soutenir-envol?session='.$data['client_session'].'&success=true&paymentMethod=stripe',
            'cancel_url' => $this->FRONT_URL . '/soutenir-envol?session='.$data['client_session'].'&canceled=true',
        ]);
        return $checkout_session->id;
    }

    public function create_portal_session($data){
        // TODO only logged users, change url according to that
    $url = config('env_variables.front_url'). '/donations?cancel=true';
    try {
        $session = \Stripe\BillingPortal\Session::create([
                'customer' => $data['customer_id'],
                'return_url' => $url,
            ]);
            return $session->url;
        } catch(\Exception $e){
            return '';
        }
    }

    public function notify_new_donation($data){
        try {
            Mail::to(config('env_variables.contact_mail_to'))->send(new DonationNotificationToEnvol($data));
            Mail::to($data['email'])->send(new DonationNotificationToDonatorMail($data));
            $result = [
                'status' => 200,
                'message' => 'Donation prise en compte avec succès'
            ];
        } catch (\Swift_TransportException $e) {
            Log::warning($e->getMessage());
            $result = [
                'status' => 400,
                'message' => "Une erreur est survenue durant l'envoi du mail de confirmation. Cependant votre donation à été prise en compte. Au besoin vous pouvez contacter notre comptabilité."
            ];
        }
        return $result;
    }

    public function retrieve_stripe_customer($customer_id){
        try {
         $customer = $this->stripe->customers->retrieve(
            $customer_id,
            []
        );
         return $customer;
        } catch(\Exception $e){
            return false;
        }
    }


}
