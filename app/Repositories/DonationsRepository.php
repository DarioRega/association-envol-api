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

    public function getSingleByCustomerId($id)
    {
        return Donor::where('customer_id', '=', $id)->get();
    }

    public function getAll()
    {
        return Donor::all();
    }

    public function create($data)
    {
        $donor = Donor::create($data);
        $donor->save();

        return $donor;
    }

    public function updateSubscription($data)
    {
        $donor = $this->getSingleByCustomerId($data['customer_id']);
        $donor->email = $data['email'];
        $donor->subscription_status = $data['subscription_status'];

        $donor->save();

        return $donor;
    }

    public function delete($donor){
        $donor->delete();
    }
}
