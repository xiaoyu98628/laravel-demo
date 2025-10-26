<?php
declare(strict_types=1);

namespace other\Flow2\Observers;

use Illuminate\Support\Facades\Log;
use other\Flow2\Contracts\ObserverInterface;

final class MailObserver implements ObserverInterface
{
    public function handle(string $event, array $payload = []): void
    {
        Log::info("[Mail] event=$event", $payload);
    }
}
