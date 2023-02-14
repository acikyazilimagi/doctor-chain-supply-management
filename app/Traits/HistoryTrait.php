<?php

namespace App\Traits;

use App\Models\ModelHistory;

trait HistoryTrait
{
    public function createHistory(array $columns){
        $last_item = ModelHistory::select(['id', 'version'])->where(['model_class' => self::class, 'model_id' => $this->id])->orderBy('id', 'desc')->first();
        $version = $last_item ? $last_item->version + 1 : 1;

        $item = new ModelHistory();
        $item->fill([
            'model_class' => self::class,
            'model_id' => $this->id,
            'version' => $version,
            'created_at' => now(),
            'created_by' => auth()->user()->id,
            'data' => json_encode($columns),
        ]);
        $item->save();

        return $item;
    }
}
