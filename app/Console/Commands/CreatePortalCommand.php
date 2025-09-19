<?php

namespace App\Console\Commands;

use App\Repositories\PortalRepositoryInterface;
use App\Services\ArticleService;
use Illuminate\Console\Command;

class CreatePortalCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'portal:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create portal';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = trim((string) $this->ask('Add meg a portál nevét'));
        if ($name === '') {
            $this->error('A név nem lehet üres.');
            return self::FAILURE;
        }

        $portalRepository = app(PortalRepositoryInterface::class);
        $portal = $portalRepository->getByName($name);
        if($portal) {
            $this->error('A portál név már létezik');
            return self::FAILURE;
        }

        $domain = trim((string) $this->ask('Add meg a domain-t (pl. https://example.com)'));
        if($domain === '') {
            $this->error('A domain nem lehet üres.');
            return self::FAILURE;
        }

        $rss = trim((string) $this->ask('Add meg a rss csatornát (pl. https://example.com/rss.xml)'));
        if($rss === '') {
            $this->error('Az rss nem lehet üres.');
            return self::FAILURE;
        }

        $portalRepository->create($name, $domain, $rss);
        $this->info('A domain regisztrálva lett.');
        return self::SUCCESS;
    }
}
