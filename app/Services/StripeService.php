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
        return $this->stripe->prices->all();
    }

    public function createSessionOneTimePayment(){
        $checkout_session = $this->stripe->checkout->sessions->create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'chf',
                    'unit_amount' => 2000,
                    'product_data' => [
                        'name' => 'Stubborn Attachments',
                        'images' => ["https://i.imgur.com/EHyR2nP.png"],
                    ],
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => $this->DOMAIN_URL . '/donation?success=true',
            'cancel_url' => $this->DOMAIN_URL . '/donation?canceled=true',
        ]);
        return $checkout_session->id;

    }

    public function createSessionSubscription(){
        $checkout_session = $this->stripe->checkout->sessions->create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price' => 'price_1IWl69DAjI7tmzlNKFERMyq6',
                'quantity' => 1,
            ]],
            'mode' => 'subscription',
            'success_url' => $this->DOMAIN_URL . '?success=true',
            'cancel_url' => $this->DOMAIN_URL . '?canceled=true',
        ]);
        return $checkout_session->id;

    }

}
