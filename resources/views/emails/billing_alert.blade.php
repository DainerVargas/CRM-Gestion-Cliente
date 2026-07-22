<x-mail::message>
# Alerta de Próximo Cobro

Hola,

El sistema ha detectado que hoy es la fecha de próximo cobro para el siguiente cliente:

- **Cliente:** {{ $client->name }}
- **Teléfono:** {{ $client->phone }}
@if($client->email)
- **Email:** {{ $client->email }}
@endif
@if($client->company)
- **Empresa:** {{ $client->company }}
@endif

Por favor, ponte en contacto con el cliente para gestionar el cobro.

<x-mail::button :url="url('/clients')">
Ver Clientes
</x-mail::button>

Gracias,<br>
{{ config('app.name') }}
</x-mail::message>
