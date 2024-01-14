<?php 

namespace App\Traits;

trait RepairTrait {
    
    protected function resourceAbilityMap(): array
    {
        return array_merge(parent::resourceAbilityMap(), [
            // method in Controller => method in Policy
            'updateStatus' => 'updateStatus',
            'repairPDF' => 'repairPDF',
            'createFile' => 'createFile',
            'storeFile' => 'createFile',
            'downloadFile' => 'downloadFile',
            'destroyFile' => 'destroyFile',
        ]);
    }

    protected function resourceMethodsWithoutModels(): array
    {
        return array_merge(parent::resourceMethodsWithoutModels(), [
            // method in Controller
        ]);
    }
}
