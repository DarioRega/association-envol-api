<?php

namespace App\Services;

use Stripe\StripeClient;
use Config;
\Stripe\Stripe::setApiKey('sk_test_4eC39HqLyjWDarjtT1zdp7dc');

class StripeService
{
    protected $stripe;
    public $DOMAIN_URL = 'http://localhost:3000';
    public $MAIN_DONATIONS_PRODUCT_ID = 'prod_J93CK4sl4pYpFe';
    public $CUSTOM_DONATIONS_PRODUCT_ID = 'prod_JExXJvpqQEau0Q';

// TODO CREATE METHOD TO GET A SINGLE PRICE, IF NOT PRESENT, CREATE A METHOD TO CREATE PRICE ON STRIPE
    public function __construct()
    {
        $stripe_secret = config('stripe-keys.stripe_secret');

        $this->stripe = new StripeClient($stripe_secret);
    }

    public function getInstance(){
        return $this->stripe;
    }

    public function getMainsPrices(){
        return $this->stripe->prices->all(['product' => $this->MAIN_DONATIONS_PRODUCT_ID]);
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
                'product' => $this->CUSTOM_DONATIONS_PRODUCT_ID,
            ]);
        } else {
            $createdPrice = $this->stripe->prices->create([
                'unit_amount' => $amount,
                'currency' => 'chf',
                'recurring' => ['interval' => $interval],
                'product' => $this->CUSTOM_DONATIONS_PRODUCT_ID,
            ]);
        }
        return $createdPrice;
    }

    public function createSession($data){
        $checkout_session = $this->stripe->checkout->sessions->create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price' => $data['price']['id'],
                'quantity' => 1,
            ]],
            'customer_email' => $data['email'],
            'mode' => $data['price']['type'] == 'one_time' ? 'payment' : 'subscription',
            'success_url' => $this->DOMAIN_URL . '/soutenir-envol?session='.$data['client_session'].'&success=true&paymentMethod=stripe',
            'cancel_url' => $this->DOMAIN_URL . '/soutenir-envol?session='.$data['client_session'].'&canceled=true',
        ]);

        return $checkout_session->id;
    }
}
