<?php

namespace Brace\UniDbBridge;

use Brace\Command\CliValueArgument;
use Brace\Core\BraceApp;
use Brace\Core\BraceModule;
use Phore\Di\Container\Producer\DiService;
use Phore\UniDb\UniDb;

class UniDbModule implements BraceModule
{
    public function __construct(
        /**
         * @var array<string, UniDb>
         */
        private array $connections
    )
    {


    }


    public function register(BraceApp $app)
    {
        foreach($this->connections as $key => $con) {
            $app->define($key, new DiService($con));
        }

        $app->command->addCommand("create_schema", function () use ($app) {
            foreach ($this->connections as $key => $connection) {
                $con = $app->get($key, UniDb::class);
                $con->createSchema();
            }
        }, "Write the UniDb schema to the database", [new CliValueArgument("--name", "The name of the database id")]);
    }
}
