<?php declare(strict_types=1);

namespace App\Dashboard;

use App\User;

interface Dashboard
{
    public function getData(User $forUser): array;
}
