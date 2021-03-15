<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function donate(Request $request){
        $user = User::firstOrCreate(
            [
                'email' => $request->input('email')
            ],
            [
                'password' => Hash::make(Str::random(12)),
                'name' => $request->input('first_name') . ' ' . $request->input('last_name'),
                'address' => $request->input('address'),
                'city'  => $request->input('city'),
                'state' => $request->input('state'),
                'zip_code' => $request->input('zip_code')
            ]
        );

        try {
            $user->createOrGetStripeCustomer();

            $payment = $user->charge(
                $request->input('amount'),
                $request->input('payment_method_id')
            );
            $payment = $payment->asStripePaymentIntent();

            $order = $user->orders()
                ->create([
                    'transaction_id' => $payment->charges->data[0]->id,
                    'total' => $payment->charges->data[0]->amount
                ]);
 // TODO WHEN MAKING WITH SUBSCRIPTION WE HAVE TO MODIFY THE WAY TO DO IT, WE NEED TO CREATE PRODUCT ALWAYS CANT USE ANOTHER ONE WITH ANOTHER KIND OF SUBSCRIPTIOn
                $product = Product::firstOrCreate(
                    [
                        'id' => $request->input('product_id')
                    ],
                    [
                        'amount' => $request->input('amount')
                    ]
                );
                $order->products()
                    ->attach($product);

            $order->load('products');

            return $order;
        } catch (\Exception $e){
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
