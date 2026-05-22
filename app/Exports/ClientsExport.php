<?php

namespace App\Exports;

use App\Models\Client;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ClientsExport implements FromCollection, WithHeadings, WithMapping
{
    protected $userIds;

    public function __construct($userIds = null)
    {
        $this->userIds = $userIds;
    }

    public function collection()
    {
        return Client::query()
            ->when($this->userIds, function($q) {
                $q->whereIn('user_id', $this->userIds);
            })
            ->latest()
            ->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Nombre',
            'Email',
            'Teléfono',
            'Empresa',
            'Rubro',
            'Estado',
            'Fecha de Creación',
        ];
    }

    public function map($client): array
    {
        return [
            $client->id,
            $client->name,
            $client->email,
            $client->phone,
            $client->company,
            $client->rubro,
            $client->status,
            $client->created_at->format('d/m/Y H:i'),
        ];
    }
}
