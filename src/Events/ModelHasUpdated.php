<?php

namespace Suitmedia\Cloudflare\Events;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Queue\SerializesModels;

class ModelHasUpdated
{
    use SerializesModels;

    /**
     * Eloquent model's data
     * after getting serialized into array.
     *
     * @var array
     */
    protected $data;

    /**
     * Eloquent model object.
     *
     * @var mixed
     */
    protected $model;

    /**
     * Eloquent model class name.
     *
     * @var string
     */
    protected $modelClass;

    /**
     * Event constructor.
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     */
    public function __construct(Model $model)
    {
        $this->data = $model->toArray();
        $this->modelClass = get_class($model);
    }

    /**
     * Create dirty eloquent model object
     * based on the last saved model data.
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    protected function createDirtyModel(): Model
    {
        $this->model = app($this->modelClass);
        $this->model->fill($this->data);

        $key = $this->model->getKeyName();
        $this->model->setAttribute($key, data_get($this->data, $key));

        return $this->model;
    }

    /**
     * Get eloquent query builder for
     * the given eloquent model.
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function getQuery(Model $model): Builder
    {
        $query = $model->newQuery();

        $traits = (array) class_uses($model);

        if (in_array(SoftDeletes::class, $traits, true)) {
            $query->withTrashed();
        }

        return $query;
    }

    /**
     * Model accessor.
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function model(): Model
    {
        return $this->retrieveModel() ?? $this->createDirtyModel();
    }

    /**
     * Retrieve fresh eloquent model from
     * run-time cache or the database.
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    protected function retrieveModel(): ?Model
    {
        if ($this->model !== null) {
            return $this->model;
        }

        $model = app($this->modelClass);

        $this->model = $this->getQuery($model)->find(data_get($this->data, $model->getKeyName()));

        return ($this->model instanceof Model) ? $this->model : null;
    }
}
