<?php

namespace Suitmedia\Cloudflare\Model\Concerns;

use Suitmedia\Cloudflare\CloudflareObserver;

trait Cloudflare
{
    /**
     * Boot the Cloudflare trait by attaching
     * a new observer to the current model.
     *
     * @return void
     */
    public static function bootCloudflare(): void
    {
        static::observe(app(CloudflareObserver::class));
    }

    /**
     * When a model is being unserialized, fire eloquent wakeup event.
     *
     * @return void
     */
    public function __wakeup(): void
    {
        parent::__wakeup();

        event('eloquent.wakeup: '.get_class($this), $this);
    }
}
