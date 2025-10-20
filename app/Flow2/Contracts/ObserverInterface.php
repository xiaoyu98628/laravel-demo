<?php
declare(strict_types=1);

namespace App\Flow2\Contracts;

interface ObserverInterface
{
    public function handle(string $event, array $payload = []): void;
}
