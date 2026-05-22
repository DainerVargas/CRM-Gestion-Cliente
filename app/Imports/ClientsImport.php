<?php

namespace App\Imports;

use App\Models\Client;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Row;

class ClientsImport implements OnEachRow, WithHeadingRow
{
    public $createdCount = 0;
    public $updatedCount = 0;
    public $errorCount = 0;
    public $errors = [];

    public function onRow(Row $row)
    {
        $data = $row->toArray();

        try {
            // Basic validation
            if (empty($data['nombre']) || (empty($data['telefono']) && empty($data['email']))) {
                $this->errorCount++;
                $this->errors[] = "Fila {$row->getIndex()}: Nombre y al menos un contacto (teléfono o email) son obligatorios.";
                return;
            }

            $client = null;
            
            // 1. Buscar por email
            if (!empty($data['email'])) {
                $client = Client::where('email', $data['email'])->first();
            }

            // 2. Si no se encontró por email, buscar por teléfono (normalizado)
            if (!$client && !empty($data['telefono'])) {
                $cleanPhone = preg_replace('/[^0-9]/', '', $data['telefono']);
                if ($cleanPhone) {
                    $client = Client::where('phone', $cleanPhone)->first();
                }
            }

            // 3. Si aún no se encontró, buscar por nombre exacto
            if (!$client && !empty($data['nombre'])) {
                $client = Client::where('name', trim($data['nombre']))->first();
            }

            if ($client) {
                $client->update([
                    'name'    => $data['nombre'],
                    'email'   => $data['email'] ?? $client->email,
                    'phone'   => $data['telefono'] ?? $client->phone,
                    'company' => $data['empresa'] ?? $client->company,
                    'rubro'   => $data['rubro'] ?? $client->rubro,
                    'status'  => $data['estado'] ?? $client->status,
                ]);
                $this->updatedCount++;
            } else {
                Client::create([
                    'name'    => $data['nombre'],
                    'email'   => $data['email'] ?? null,
                    'phone'   => $data['telefono'] ?? '',
                    'company' => $data['empresa'] ?? null,
                    'rubro'   => $data['rubro'] ?? null,
                    'status'  => $data['estado'] ?? 'prospect',
                    'user_id' => auth()->id(),
                ]);
                $this->createdCount++;
            }
        } catch (\Exception $e) {
            $this->errorCount++;
            $this->errors[] = "Fila {$row->getIndex()}: " . $e->getMessage();
        }
    }
}
