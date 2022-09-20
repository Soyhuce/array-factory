<?php declare(strict_types=1);

namespace Soyhuce\ArrayFactory\Commands;

use Illuminate\Console\Command;

class ArrayFactoryCommand extends Command
{
    public $signature = 'array-factory';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
