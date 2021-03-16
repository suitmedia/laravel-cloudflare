<?php

namespace Suitmedia\Cloudflare;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Suitmedia\Cloudflare\Events\ModelHasUpdated;

class CloudflareObserver
{
    /**
     * Cloudflare Service Object.
     *
     * @var \Suitmedia\Cloudflare\CloudflareService
     */
    protected $cloudflare;

    /**
     * Cloudflare Observer constructor.
     */
    public function __construct()
    {
        $this->cloudflare = app(CloudflareService::class);
    }

    /**
     * Listening to any saved events.
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     *
     * @return void
     */
    public function deleted(Model $model): void
    {
        $this->handleModelUpdates($model);
    }

    /**
     * Handle any retrieved and wakeup events on
     * the observed models.
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     *
     * @throws Exception
     *
     * @return void
     */
    protected function handleModelInitialization(Model $model): void
    {
        $updatedAt = $model->getAttribute('updated_at');
    }

    /**
     * Handle any update events on the observed models.
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     *
     * @return void
     */
    protected function handleModelUpdates(Model $model): void
    {
        event(new ModelHasUpdated($model));
    }

    /**
     * Listening to any saved events.
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     *
     * @return void
     */
    public function restored(Model $model): void
    {
        $this->handleModelUpdates($model);
    }

    /**
     * Listening to any retrieved events.
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     *
     * @return void
     */
    public function retrieved(Model $model): void
    {
        $this->handleModelInitialization($model);
    }

    /**
     * Listening to any saved events.
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     *
     * @return void
     */
    public function saved(Model $model): void
    {
        $this->handleModelUpdates($model);
    }

    /**
     * Listening to any wakeup events.
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     *
     * @return void
     */
    public function wakeup(Model $model): void
    {
        $this->handleModelInitialization($model);
    }
}
