<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>SolidRecordRH</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                background-color: #f4f4f4;
                margin: 0;
                padding: 0;
            }

            .container {
                max-width: 600px;
                margin: 0 auto;
                background-color: #ffffff;
                padding: 20px;
                border-radius: 5px;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            }

            header {
                text-align: center;
            }

            header h1 {
                color: #333333;
            }

            .content {
                margin-top: 20px;
            }

            p {
                color: #555555;
                line-height: 1.6;
            }

            footer {
                margin-top: 20px;
                text-align: center;
                color: #888888;
            }
        </style>
    </head>
    <body>
    <div class="container">
            <header>
                <h1>SolidRecordRH | Fe y Alegria </h1>
            </header>

            <div class="content">
                <p>Hola , {{ $name }}</p>
                <p>{{ $email }}</p>
                <p>Usted a sido registrado en el sistema de Gestion de Autos de Fe y Alegria el {{$fecha}}.</p>
                <p>Se ha creado lo Siguente:</p>
                <ul>
                    <li>Usuario: {{$email}}</li>
                    <li>Contraseña Temporal: {{$password}}</li>
                </ul>
                <p>Recomendamos cambiar la contraseña en su primer Inicio de Sesion !</p>
            </div>

            <footer>
                <p>Este correo es automatico, *No Responder* , SolidRecordRH 2024 © </p>
            </footer>
        </div>
    </body>
</html>