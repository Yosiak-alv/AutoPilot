<?php

namespace App\Exports;

use App\Models\Branch;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
class BranchesExport implements FromCollection, WithHeadings,  ShouldAutoSize , WithStyles
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
                'Nombre' => $item->name,
                'Telefono' => $item->telephone,
                'Es Centro' => $item->main == 1 ? 'Si' : 'No',
                'Departamento' => $item->state->name ?? null, // Relationship column
                'Municipio' => $item->town->name ?? null,
                'Distrito' => $item->district->name ?? null,
                'Eliminado el' => $item->deleted_at,
            ];
        });
    }
    public function headings(): array
    {
        // Define the header columns including relationship columns
        return [
            'ID',
            'Nombre',
            'Telefono',
            'Es Centro',
            'Departamento' , // Relationship column
            'Municipio' ,
            'Distrito' ,
            'Eliminado el' 
        ];
    }
    public function columnFormats(): array
    {
        // You can set specific column formats if needed
        return [
            'ID'=> '0.00', // Example format for column A
            'Nombre'=> '0.00', // Example format for column A
            'Telefono'=> '0.00', // Example format for column A
            'Es Centro'=> '0.00', // Example format for column A
            'Departamento' => '0.00', // Example format for column A
            'Municipio' => '0.00', // Example format for column A
            'Distrito' => '0.00', // Example format for column A
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
                $query->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('telephone', 'like', '%' . $this->search . '%')
                    ->orWhere(function ($query) {
                        $query->where(function ($query) {
                            if ($this->search === 'si') {
                                $query->where('main', '1');
                            } elseif ($this->search === 'no') {
                                $query->where('main', '0');
                            }
                        });
                    })
                    ->orWhereHas('district', function ($query){
                        $query->where('districts.name', 'like', '%' . $this->search . '%');
                    })->orWhereHas('town', function ($query) {
                        $query->where('towns.name', 'like', '%' . $this->search . '%');
                    })->orWhereHas('state', function ($query)  {
                        $query->where('states.name', 'like', '%' . $this->search . '%');
                    });
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
