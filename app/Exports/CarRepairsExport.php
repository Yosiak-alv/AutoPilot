<?php

namespace App\Exports;

use App\Models\Repair;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class CarRepairsExport implements FromCollection, WithHeadings
{
    protected $query;
    public function __construct($query)
    {
        $this->query = $query;
    }
    public function headings(): array
    {
        // Define the header columns including relationship columns
        return [
            'ID' ,
            'Taller' ,
            'Estado' ,
            'Total',
            'Detalle Nombre' ,
            'Detalle Descripcion',
            'Detalle Precio' ,
        ];
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
       /*  return $this->query->get()->map(function ($repair) {
            return [
                'ID' => $repair->id,
                'Car ID' => $repair->car_id,
                'Workshop Name' => $repair->work_shop->name ?? null,
                'Status Name' => $repair->status->id ?? null,
                'Total' => $repair->total,
                'Created At' => $repair->created_at,
                ...$this->flattenDetails($repair->details),
            ];
        }); */
        return $this->query->get()->flatMap(function ($repair) {
            return $repair->details->map(function ($detail) use ($repair) {
                return [
                    'ID' => $repair->id,
                    'Taller' => $repair->work_shop->name ?? null,
                    'Estado' => $repair->status->name ?? null,
                    'Total' => $repair->total,
                    'Detalle Nombre' => $detail['name'],
                    'Detalle Descripcion' => $detail['description'],
                    'Detalle Precio' => $detail['price'],
                ];
            });
        });
    }
     /**
     * Flatten details array for export
     *
     * @param array $details
     * @return array
     */
    protected function flattenDetails($details)
    {
        $flattenedDetails = [];

        foreach ($details as $detail) {
            $flattenedDetails[] = $detail['name'];
            $flattenedDetails[] = $detail['description'];
            $flattenedDetails[] = $detail['price'];
            // Add more detail attributes as needed
        }

        return $flattenedDetails;
    }
}
