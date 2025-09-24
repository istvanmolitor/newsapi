<?php

namespace App\Repositories;

use App\Models\Portal;

interface PortalRepositoryInterface
{
    public function create(string $name, string $domain): Portal;

    public function getByDomain(string $domain): Portal|null;

    public function getByName(string $name): Portal|null;
}
