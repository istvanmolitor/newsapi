<?php

namespace App\Repositories;

use App\Models\Portal;

class PortalRepository implements PortalRepositoryInterface
{
    private Portal $portal;

    private array $domainCache = [];

    public function __construct()
    {
        $this->portal = new Portal();
    }

    public function create(string $name, string $domain): Portal
    {
        return $this->portal->create([
            'name' => $name,
            'domain' => $domain
        ]);
    }

    public function getByDomain(string $domain): Portal|null
    {
        if(!array_key_exists($domain, $this->domainCache)) {
            $this->domainCache[$domain] = $this->portal->where('domain', $domain)->first();
        };
        return $this->domainCache[$domain];
    }

    public function getByName(string $name): Portal|null
    {
        return $this->portal->where('name', $name)->first();
    }
}
