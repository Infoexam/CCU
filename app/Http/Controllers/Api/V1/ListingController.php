<?php

namespace App\Http\Controllers\Api\V1;

use App\Exams\Listing;
use App\Http\Controllers\Controller;

class ListingController extends Controller
{
    public function index()
    {
        return Listing::with(['paper'])
            ->latest('began_at')
            ->orderBy('room')
            ->paginate();
    }

    public function store()
    {
        //
    }

    public function show()
    {
        //
    }

    public function update()
    {
        //
    }

    public function destroy()
    {
        //
    }
}
