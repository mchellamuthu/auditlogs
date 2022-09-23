<?php

namespace App\Traits;

use App\Models\AuditLog;
use Illuminate\Database\Eloquent\Model;

trait Auditable
{
    public static function bootAuditable()
    {
        static::created(function (Model $model) {
            self::audit('created', $model);
        });
        static::updating(function (Model $model) {
            $old_value = [];
            if (!empty($model->allowedAudits)) {
                foreach ($model->allowedAudits as $attribute) {
                    $old_value[$attribute] = $model->getRawOriginal($attribute);
                }
            } else {
                $old_value = $model->getRawOriginal();
            }
            /**
             * Check model has auditable relationships
             */
            if (!empty($model->auditableRelationships)) {
                foreach ($model->auditableRelationships as $relationship) {
                    /**
                     * get relationship type
                     */
                    $relationType = self::getRelationType($model, $relationship['name']);
                    /**
                     * Check $relationship has fields to audit
                     * if its empty get all attributes of the model
                     */
                    if (!empty($relationship['fields'])) {
                        $old_value[$relationship['name']] =
                            strpos($relationType, 'BelongsTo') ?
                            $model->{$relationship['name']}->only($relationship['fields']) :
                            $model->{$relationship['name']}()->get($relationship['fields'])->toArray();
                    } else {
                        $old_value[$relationship['name']] = $model->$relationship['name']->toArray();
                    }
                }
            }
            self::audit('updated', $model, $old_value);
        });
        /**
         * Audit while deleting a data
         */
        static::deleting(function (Model $model) {
            self::audit('deleted', $model);
        });
    }
    protected static function getRelationType($model, $method)
    {
        $type = get_class($model->{$method}());
        return $type;
    }
    protected static function audit($action, $model, $old_value = null)
    {
        $data = [
            'action' => $action,
            'model_id' => $model->id ?? null,
            'model_name' => get_class($model) ?? null,
            'user_id' => auth()->id() ?? null,
            'model_resources' => $model->attributesToArray() ?? null,
            'old_value' => $old_value
        ];
        AuditLog::create($data);
    }
}
