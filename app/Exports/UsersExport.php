<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
class UsersExport implements FromCollection, WithHeadings,  ShouldAutoSize , WithStyles
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
                'Correo' => $item->email,
                'Roles' => $item->roles->pluck('name')->implode(', '),
                'Centro' => $item->branch->name ?? null, // Relationship column
                'Eliminado el' => $item->deleted_at,
            ];
        });
    }
    public function headings(): array
    {
        // Define the header columns including relationship columns
        return [
            'ID',
            'Nombre' ,
            'Correo' ,
            'Roles' ,
            'Centro' ,
            'Eliminado el' ,
        ];
    }
    public function columnFormats(): array
    {
        // You can set specific column formats if needed
        return [
            'ID'=> '0.00',
            'Nombre'=> '0.00',
            'Correo' => '0.00',
            'Roles'=> '0.00',
            'Centro' => '0.00',
            'Eliminado el' => '0.00',
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
                $query->where('id', 'like', '%' . $this->search . '%')
                    ->orWhere('name', 'like', '%' . $this->search . '%')
                    ->orWhere('email', 'like', '%' . $this->search . '%')
                    ->orWhereHas('roles',fn($query) =>
                        $query->where('roles.name','like','%'.$this->search.'%')
                    )->orWhereHas('branch',fn($query) =>
                        $query->where('branches.name','like','%'.$this->search.'%')
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
