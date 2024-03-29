<?php 

namespace App\Traits;

trait WorkShopTrait {
    
    protected function resourceAbilityMap(): array
    {
        return array_merge(parent::resourceAbilityMap(), [
            // method in Controller => method in Policy
            'restore' => 'restore',
            'excelIndexExport' => 'excelExport',
        ]);
    }

    protected function resourceMethodsWithoutModels(): array
    {
        return array_merge(parent::resourceMethodsWithoutModels(), [
            // method in Controller
            'excelIndexExport'
        ]);
    }
}
