<?php

namespace App\Services;

use App\Repositories\DonationsRepository;
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

    public function getInstance(){
        return $this->stripe;
    }

    public function getMainsPrices(){
        return $this->stripe->prices->all(['product' => $this->STRIPE_MAIN_DONATIONS_PRODUCT_ID]);
    }

    public function getAllPrices(){
        $request = $this->stripe->prices->all();
        return $request['data'];
    }

    public function findOrCreatePrice($selected_amount_object, $selected_interval_object){
        $allPrices = self::getAllPrices();
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
        $price = self::createCustomPrice($selected_amount_object['amount'], $selected_interval_object['ref']);
        return $price;
    }

    public function createCustomPrice($amount, $interval){
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

    public function createCheckoutSession($data){
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

    public function getAll(){
        return $this->donationsRepository->getAll();
    }

    public function saveNewDonor($data){
        $this->donationsRepository->create([
            'customer_id' => $data['customer'],
            'email' => $data['customer_details']['email'],
            'subscription_status' =>'test',
        ]);
    }


}
