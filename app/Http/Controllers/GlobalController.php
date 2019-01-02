<?php

namespace App\Http\Controllers;

use App\Cache;
use App\TargetsAgencyBranch;
use App\PostcodeLookup\PostcodeLookup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GlobalController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth:admin');
    }

    public function findHousesByPostcode(PostcodeLookup $pcodeLookupService, Request $request)
    {
        // Validate request
        $response = (object)array(

            'errorCode' => 0,
            'feedback' => [],
            'results' => []

        );

        $postcode = $request->query('p', null);

        // In theory this should never happen because of the client side validation
        // which checks that the postcode field is not empty.
        if (empty($postcode)) {
            // No postcode submitted.
            $response->errorCode = 400;
            $response->feedback = 'No postcode was submitted';

            return response()->json([
                $response
            ]);
        }

        $postcodeResults = $pcodeLookupService->lookupByPostcode($request->p);

        if (isset($postcodeResults->code) && $postcodeResults->code !== '2000') {
            $response->errorCode = $postcodeResults['code'];
            $response->feedback = 'Error: ' . $postcodeResults['message'];

            return response()->json([
                $response
            ]);
        }

        $readableAddresses = json_decode(json_encode($postcodeResults), false);

        $addresses = [];
        $i = 0;
        foreach ($readableAddresses as $readableAddress) {
            $addresses[$i]['building_name'] = $readableAddress->building_name;
            $addresses[$i]['building_number'] = $readableAddress->building_number;
            $addresses[$i]['thoroughfare'] = $readableAddress->thoroughfare;
            $addresses[$i]['line_1'] = $readableAddress->line_1;
            $addresses[$i]['line_2'] = $readableAddress->line_2;
            $addresses[$i]['line_3'] = $readableAddress->line_3;
            $addresses[$i]['postcode'] = $readableAddress->postcode;
            $addresses[$i]['county'] = $readableAddress->county;
            $addresses[$i]['longitude'] = $readableAddress->longitude;
            $addresses[$i]['latitude'] = $readableAddress->latitude;
            $addresses[$i]['district'] = $readableAddress->district;
            $i++;
        }

        if (count($addresses > 0)) {
            $response->resultcount = count($addresses);
            $response->results = $addresses;
            return response()->json($response);
        }

        return false;
    }

    public function update(Request $request)
    {
        $value = $request['value'];
        $table = getModel($request['table']);
        $field = $request['field'];
        $id = $request['id'];

        $update = $table::find($id);
        $update->$field = $value;
        if ($update->save()) {
            return response()->json([
                'success' => true,
                'data' => [
                    'value' => $value
                ]
            ]);
        }

        return false;
    }

    public function create(Request $request)
    {
        $value = $request['value'];
        $table = getModel($request['table']);
        $field = $request['field'];
        $id = $request['id'];

        $update = $table::find($id);
        $update->$field = $value;
        if ($update->save()) {
            return response()->json([
                'success' => true,
                'data' => [
                    'value' => $value
                ]
            ]);
        }

        return false;
    }
}
