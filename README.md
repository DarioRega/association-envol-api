# API [Association Envol](https://association-envol.info)

## About Envol
Envol is a non-profit association founded in 1997, it is managed by a committee of volunteers,
it supports people who are studying and can't carry it out because of lack of financial resources.


## About the API
This API handle various process as:
- Getting public records from the association
- Contacting them via the front end contact form
- Uploading files and dispatching emails to all the involved parties on a scholarship request.
- Payment process and redirection when paying via Stripe
- Record Paypal payments process


## Technologies and libraries used in this project
- [Laravel 8](https://laravel.com/docs/8.x) 
- [Stripe sdk](https://github.com/stripe/stripe-php)
- [Gitlab ci](https://docs.gitlab.com/ee/ci/)


## Installation
To use the stripe features, you must to create an account and generate api keys
[Stripe.com](https://support.stripe.com/questions/locate-api-keys-in-the-dashboard)

You must aswell create two products on your account and populate the values for the .env variables
- STRIPE_MAIN_DONATIONS_PRODUCT_ID
- STRIPE_CUSTOM_DONATIONS_PRODUCT_ID

To access rapports endpoint, you must generate a key and populate the .env variable RAPPORTS_ACCESS_TOKEN

```bash
# install dependencies
$ cp .env.example.local .env

$ composer install

$ php artisan migrate

$ php artisan serve
```

### Frontend Repository
#### [Association Envol Frontend repository](https://gitlab.com/seelo/association-envol/frontend)

## Contact
Creator and maintainer:

Dario Regazzoni, [dario.regazzoni@seelo.ch]()
