<?php
declare(strict_types=1);

namespace App\Flow2\Observers;

use App\Flow2\Contracts\ObserverInterface;
use Illuminate\Support\Facades\Log;

final class MailObserver implements ObserverInterface
{
    public function handle(string $event, array $payload = []): void
    {
        Log::info("[Mail] event=$event", $payload);
    }
}
