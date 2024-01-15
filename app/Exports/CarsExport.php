<?php

namespace App\Exports;

use App\Models\Car;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
class CarsExport implements FromCollection, WithHeadings,  ShouldAutoSize , WithStyles
{
    protected $query;
    protected $search;
    protected $trashed;
    public function __construct($query, $search = null, $trashed = null)
    {
        $this->query = $query;
        $this->search = $search;
        $this->trashed = $trashed;
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        // Apply filters to the query
        $this->applyFilters();

        $data = $this->query->get();
        //dd($data); // Check the retrieved data before exporting
        return $data->map(function ($item) {
            return [
                'ID' => $item->id,
                'Placas' => $item->plates,
                'Año' => $item->year,
                'Centro' => $item->branch->name ?? null, // Relationship column
                'Modelo' => $item->model->name ?? null,
                'Marca' => $item->model->brand->name ?? null,
                'Eliminado el' => $item->deleted_at,
            ];
        });
    }
    public function headings(): array
    {
        // Define the header columns including relationship columns
        return [
            'ID' ,
            'Placas' ,
            'Año' ,
            'Centro' ,
            'Modelo',
            'Marca',
            'Eliminado el',
        ];
    }
    public function columnFormats(): array
    {
        // You can set specific column formats if needed
        return [
            'ID' => '0.00', // Example format for column A
            'Placas' => '0.00', // Example format for column A
            'Año' => '0.00', // Example format for column A
            'Centro' => '0.00', // Example format for column A
            'Modelo' => '0.00', // Example format for column A
            'Marca' => '0.00', // Example format for column A,
            'Eliminado el' => '0.00', // Example format for column A
        ];
    }
    /**
     * Apply filters to the query
     */
    protected function applyFilters()
    {
        if ($this->search) {
            // Apply search filter
            $this->query->where(function ($query) {
                $query->where('plates', 'like', '%' . $this->search . '%')
                    ->orWhere('year', 'like', '%' . $this->search . '%')
                    ->orWhereHas('model',fn($query) =>
                        $query->where('name','like','%'.$this->search.'%')
                            ->orWhereHas('brand',fn($query) =>
                                $query->where('name','like','%'.$this->search.'%')
                            )
                    )->orWhereHas('branch',fn($query) =>
                        $query->where('name','like','%'.$this->search.'%')
                    );
            });
        }

        if ($this->trashed === 'only') {
            // Include trashed records
            $this->query->onlyTrashed();
        }elseif($this->trashed === 'with'){
            // Include trashed records
            $this->query->withTrashed();
        }
    }
    /**
     * @return array
     */
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
