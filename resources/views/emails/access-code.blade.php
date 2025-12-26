<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Código de Acesso</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 500px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f5f5f5;
        }

        .container {
            background-color: #ffffff;
            border-radius: 6px;
            padding: 40px 30px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #1f2937;
            margin-bottom: 30px;
        }

        .footer {
            text-align: center;
            margin-top: 30px;
            color: #9ca3af;
            font-size: 13px;
        }

        p {
            margin: 15px 0;
            color: #4b5563;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="logo">Numera</div>

        @if($userName)
        <p>Olá, {{ $userName }}!</p>
        @else
        <p>Olá!</p>
        @endif

        <p>Use o código abaixo para acessar sua conta: {{ $code }}</p>

        <p>Não compartilhe este código com ninguém.</p>

        <div class="footer">
            <p>© {{ date('Y') }} Numera</p>
        </div>
    </div>
</body>

</html>