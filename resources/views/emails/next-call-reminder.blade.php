<x-mail::message>
# Recordatorio de Próxima Llamada

Hola, **{{ $call->user->name }}**{{ $call->user->parent ? ' y **' . $call->user->parent->name . '**' : '' }}.

Tienes una llamada de seguimiento pendiente para una interacción registrada por **{{ $call->user->name }}**:

**Cliente:** {{ $client->name }}  
**Empresa:** {{ $client->company }}  
**Programada para:** {{ $call->next_call_at->format('d/m/Y H:i') }} ({{ $call->next_call_at->diffForHumans() }})

<x-mail::button :url="route('clients.show', $client->id)">
Ver Cliente
</x-mail::button>

*Nota: Te enviamos este recordatorio con {{ $timeframe === '2 hours' ? '2 horas' : '5 minutos' }} de anticipación.*

Gracias,<br>
{{ config('app.name') }}
</x-mail::message>
