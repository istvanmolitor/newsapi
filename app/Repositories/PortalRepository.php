<?php

namespace App\Repositories;

use App\Models\Portal;

class PortalRepository implements PortalRepositoryInterface
{
    private Portal $portal;

    public function __construct()
    {
        $this->portal = new Portal();
    }

    public function create(string $name, string $domain): Portal
    {
        return $this->portal->create([
            'name' => $name,
            'domain' => $domain,
        ]);
    }

    public function getByDomain(string $domain): Portal
    {
        $portal = $this->portal->where('domain', $domain)->first();
         if (!$portal) {
             $portal = $this->create($domain, $domain);
         }
         return $portal;
    }
}
