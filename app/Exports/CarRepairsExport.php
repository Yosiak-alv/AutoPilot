<?php

namespace App\Exports;

use App\Models\Repair;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class CarRepairsExport implements FromCollection, WithHeadings,  ShouldAutoSize , WithStyles
{
    protected $query;
    protected $search;
    protected $start_date;
    protected $end_date;
    public function __construct($query, $search = null , $start_date = null, $end_date = null)
    {
        $this->query = $query;
        $this->search = $search;
        $this->start_date = $start_date;
        $this->end_date = $end_date;
    }
    
    public function headings(): array
    {
        // Define the header columns including relationship columns
        return [
            'ID' ,
            'Taller' ,
            'Estado' ,
            'Detalle Nombre' ,
            'Detalle Descripcion',
            'Detalle Precio' ,
        ];
    }
    public function columnFormats(): array
    {
        // You can set specific column formats if needed
        return [
            'ID' => '0.00', // Example format for column A
            'Taller' => '0.00', // Example format for column A
            'Estado' => '0.00', // Example format for column A
            'Detalle Nombre' => '0.00', // Example format for column A
            'Detalle Descripcion' => '0.00', // Example format for column A
            'Marca' => '0.00', // Example format for column A,
            'Detalle Precio' => '0.00', // Example format for column A
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
        // Apply filters to the query
        $this->applyFilters();
        $data = $this->query->get();
        
        return $data->flatMap(function ($repair) {
            return $repair->details->map(function ($detail) use ($repair) {
                return [
                    'ID' => $repair->id,
                    'Taller' => $repair->work_shop->name ?? null,
                    'Estado' => $repair->status->name ?? null,
                    'Detalle Nombre' => $detail['name'],
                    'Detalle Descripcion' => $detail['description'],
                    'Detalle Precio' => $detail['price'],
                ];
            });
        });
    }
    protected function applyFilters()
    {
        if ($this->search) {
            // Apply search filter
            $this->query->where(function ($query) {
                $query->where('total', 'like', '%' . $this->search . '%')
                    ->orWhereHas('status',fn($query) =>
                        $query->where('name','like','%'.$this->search.'%')
                    )->orWhereHas('work_shop',fn($query) =>
                        $query->where('name','like','%'.$this->search.'%')
                    );
            });
        }

        if ($this->start_date) {
            $this->query->where('created_date','>=',$this->start_date);
        }elseif($this->end_date){
            $this->query->where('created_date','<=',$this->end_date);
        }
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
    public function styles(Worksheet $sheet)
    {
        // Apply styles to the Excel sheet
        return [
            // Style the header row
            1 => [
                'font' => [
                    'bold' => true,
                    'size' => 14,
                ],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    ],
                ],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_GRADIENT_LINEAR,
                    'rotation' => 90,
                    'startColor' => [
                        'argb' => 'FFA0A0A0',
                    ],
                    'endColor' => [
                        'argb' => 'FFFFFFFF',
                    ],
                ],
            ],
        ];
    }
}
