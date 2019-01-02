<?php

namespace App;

use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class Address extends Model implements AuditableContract
{
    use Auditable;

    public function getAddress()
    {
        $address = [];

        $address[] = $this->building_number;

        $address['building_name'] = $this->building_name;

        if (!empty($this->building_number) && !empty($this->building_name) &&
            $this->building_number === $this->building_name
        ) {
            unset($address['building_name']);
        }

        $address[] = $this->address_line_1;
        $address[] = $this->address_line_2;
        $address[] = $this->address_line_3;
        $address[] = $this->town;
        $address[] = $this->county;
        $address[] = $this->postcode;

        return implode(', ', array_filter($address));
    }

    public function saveAddress($request, $targetType = false)
    {
        if (!empty($targetType)) {
            $this->target_type = $targetType;
        }
        $this->building_name = !empty($request->building_name) ? $request->building_name : null;
        $this->building_number = !empty($request->building_number) ? $request->building_number : null;
        $this->address_line_1 = $request->address_line_1;
        $this->address_line_2 = $request->address_line_2;
        $this->town = ucfirst(strtolower($request->town));
        $this->county = $request->county;
        $this->postcode = strtoupper($request->postcode);
        if ($this->save()) {
            return $this;
        }

        return false;
    }
}
