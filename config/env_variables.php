<?php
return [
    'rapport_token' =>  env('RAPPORTS_ACCESS_TOKEN'),

    'stripe_key' =>  env('STRIPE_KEY'),
    'stripe_secret' =>  env('STRIPE_SECRET'),
    'stripe_main_donations_product_id' =>  env('STRIPE_MAIN_DONATIONS_PRODUCT_ID'),
    'stripe_custom_donations_product_id' =>  env('STRIPE_CUSTOM_DONATIONS_PRODUCT_ID'),

    'front_url': env('FRONT_URL'),
    'current': env('APP_ENV'),
    'contact_mail_to'=> env('CONTACT_MAIL_TO'),
    'scholarship_mail_to'=> env('SCHOLARSHIP_MAIL_TO')
];
