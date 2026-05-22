<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #043d8a;
            color: white;
            padding: 30px;
            text-align: center;
            border-radius: 10px 10px 0 0;
        }
        .content {
            background-color: #f9f9f9;
            padding: 30px;
            border: 1px solid #eee;
            border-radius: 0 0 10px 10px;
        }
        .footer {
            text-align: center;
            font-size: 12px;
            color: #999;
            margin-top: 20px;
        }
        .button {
            display: inline-block;
            padding: 12px 24px;
            background-color: #043d8a;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>¡Hola, {{ $clientName }}!</h1>
    </div>
    <div class="content">
        <p>Te informamos que tu suscripción de TV ha finalizado el día de hoy.</p>
        <p>Para seguir disfrutando de nuestro servicio sin interrupciones, te invitamos a renovar tu cuenta o extender tu suscripción actual.</p>
        <p>Detalles del servicio:</p>
        <ul>
            <li><strong>Cuenta:</strong> {{ $accountName }}</li>
            <li><strong>Fecha de Vencimiento:</strong> {{ $expiryDate }}</li>
        </ul>
        <p>Si ya realizaste el pago, por favor ignora este mensaje.</p>
        <center>
            <a href="{{ config('app.url') }}" class="button">Renovar Ahora</a>
        </center>
    </div>
    <div class="footer">
        <p>Gracias por confiar en nosotros.</p>
        <p>&copy; {{ date('Y') }} {{ config('app.name') }}</p>
    </div>
</body>
</html>
