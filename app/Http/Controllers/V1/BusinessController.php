<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Models\Business;
use Illuminate\Http\Request;

class BusinessController extends Controller
{
    // Create new business
    public static function newBusiness($businessData)
    {
        Business::create($businessData);

        return true;
    }
}
