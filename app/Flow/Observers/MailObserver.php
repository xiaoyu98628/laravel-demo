<?php
declare(strict_types=1);

namespace App\Flow\Observers;

use App\Flow\Contracts\ObserverInterface;
use Illuminate\Support\Facades\Log;

final class MailObserver implements ObserverInterface
{
    public function handle(string $event, array $payload = []): void
    {
        Log::info("[Mail] event=$event", $payload);
    }
}