<?php

use Modules\HumanResources\Models\Vehicle;
use App\Models\User;

$user = User::first(); // Assuming the first user is the one logged in (superuser)
$vehicle = Vehicle::find(1);

echo "User ID: " . $user->id . "\n";
echo "User Company ID: " . $user->company_id . " (Type: " . gettype($user->company_id) . ")\n";

if ($vehicle) {
    echo "Vehicle ID: " . $vehicle->id . "\n";
    echo "Vehicle Company ID: " . $vehicle->company_id . " (Type: " . gettype($vehicle->company_id) . ")\n";
    
    if ($user->company_id !== $vehicle->company_id) {
        echo "MISMATCH DETECTED!\n";
    } else {
        echo "IDs match.\n";
    }
} else {
    echo "Vehicle not found.\n";
}
