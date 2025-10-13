<?php
declare(strict_types=1);

namespace App\Flow\Contracts;

interface ObserverInterface
{
    public function handle(string $event, array $payload = []): void;
}