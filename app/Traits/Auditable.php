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
            if (!empty($model->auditableRelationships)) {
                foreach ($model->auditableRelationships as $relationships) {
                    $old_value[$relationships] = $model->relationships;
                }
            }

            self::audit('updated', $model, $old_value);
        });
    }

    protected static function audit($action, $model, $old_value = null)
    {
        $data = [
            'action' => $action,
            'model_id' => $model->id ?? null,
            'model_name' => get_class($model) ?? null,
            'user_id' => auth()->id() ?? null,
            'model_resources' => $model ?? null,
            'old_value' => $old_value
        ];
        AuditLog::create($data);
    }
}
