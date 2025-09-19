<?php

namespace App\Repositories;

use App\Models\Portal;

interface PortalRepositoryInterface
{
    public function create(string $name, string $domain, string $rss): Portal;

    public function getByDomain(string $domain): Portal;

    public function getByName(string $portal): ?Portal;
}
