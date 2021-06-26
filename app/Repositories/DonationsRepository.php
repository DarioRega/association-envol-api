<?php


namespace App\Repositories;

use App\Models\Donor;

class DonationsRepository
{

    public function getSingleById($id)
    {
        return Donor::findOrFail($id);
    }

    public function getSingleByEmail($email)
    {
        return Donor::where('email', '=', $email)->get();
    }
    public function getAll()
    {
        return Donor::all();
    }

    public function create($data)
    {
        return response()->json($data);
        $donor = Donor::create([
            'customer_id' => $data['customer_id'],
            'email' => $data['email'],
            'subscription_status' => $data['subscription_status']
        ]);

        $donor->save();
        return $donor;
    }

    public function delete($donor){
        $donor->delete();
    }
}
