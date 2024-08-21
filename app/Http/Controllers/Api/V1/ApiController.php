<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

//  This controller checks the url for for include parameters. 
//  If the include parameter is present, it will return true.
class ApiController extends Controller
{
    public function include(string $relationship): bool
    {
        $param = request()->get('include');

        // checks to see if any include parameters are set
        if (!isset($param)) {
            return false;
        }

        // this creates an array and splits at the , and makes it lowercase
        $includeValues = explode(',', strtolower($param));

        return in_array(strtolower($relationship), $includeValues);
    }
}
