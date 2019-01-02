<?php

namespace App;

use Ramsey\Uuid\Uuid;

trait GeneratesSlug
{
    public static function bootGeneratesSlug()
    {
        self::creating(function (Model $model) {
            if (empty($model->slug)) {
                $model->slug = Uuid::uuid4()->toString();
            }
        });
    }
}
