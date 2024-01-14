<?php 

namespace App\Traits;

trait CarTrait {
    
    protected function resourceAbilityMap(): array
    {
        return array_merge(parent::resourceAbilityMap(), [
            // method in Controller => method in Policy
            'storeImage' => 'storeImage',
            'createFile' => 'createFile',
            'storeUpdateFile' => 'createFile',
            'downloadFile' => 'downloadFile',
            'destroyFile' => 'destroyFile',
            'restore' => 'restore',
        ]);
    }

    protected function resourceMethodsWithoutModels(): array
    {
        return array_merge(parent::resourceMethodsWithoutModels(), [
            // method in Controller
           /*  'downloadFile',
            'destroyFile' */
        ]);
    }
}
