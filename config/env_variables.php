<?php
return [
    'rapport_token' =>  env('RAPPORTS_ACCESS_TOKEN'),

    'stripe_key' =>  env('STRIPE_KEY'),
    'stripe_secret' =>  env('STRIPE_SECRET'),
    'stripe_main_donations_product_id' =>  env('STRIPE_MAIN_DONATIONS_PRODUCT_ID'),
    'stripe_custom_donations_product_id' =>  env('STRIPE_CUSTOM_DONATIONS_PRODUCT_ID'),
    'stripe_webhook_secret' =>  env('STRIPE_WEBHOOK_SECRET'),

    'front_url' => env('FRONT_URL'),
    'app_env' => env('APP_ENV'),
    'contact_mail_to'=> env('CONTACT_MAIL_TO'),
    'donation_mail_to'=> env('DONATION_MAIL_TO'),
    'scholarship_mail_to'=> env('SCHOLARSHIP_MAIL_TO')
];
