<?php

declare(strict_types=1);

namespace App\Flow\Observers;

use App\Flow\Contracts\ObserverInterface;

final class EventSubject
{
    /** @var ObserverInterface[] */
    private array $observers = [];

    public function attach(ObserverInterface $observer): void
    {
        $this->observers[] = $observer;
    }

    public function notify(string $event, array $payload = []): void
    {
        foreach ($this->observers as $obs) {
            $obs->handle($event, $payload);
        }
    }
}
