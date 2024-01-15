<?php

namespace App\Exports;

use App\Models\Car;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithHeadings;

class CarsExport implements FromCollection, WithHeadings
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
}
