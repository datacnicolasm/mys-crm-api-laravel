<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f0f0f0;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 100%;
            max-width: 600px;
            margin: 10px auto;
            background-color: #f3f3f3;
            padding: 0;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
            overflow: hidden;
        }

        .header {
            text-align: left;
            padding: 100px 0 10px 20px;
            border-bottom: 1px solid #dddddd;
            background: #353535;
            /* Color base */
            background: linear-gradient(to bottom,
                #505050, /* Tono más claro */
                #484848, /* Tono más claro */
                #404040, /* Tono medio */
                #353535, /* Color base */
                #2c2c2c /* Tono más oscuro */
            );
            color: #ffffff;
        }

        .content {
            padding: 20px;
        }

        .footer {
            text-align: center;
            padding: 20px 0;
            border-top: 1px solid #dddddd;
            font-size: 12px;
            color: #888888;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h2><strong>CRM</strong> Motos & Servitecas</h2>
        </div>
        <div class="content">
            <h1>Hola {{ $details['name-user'] }},</h1>
            <p>Te queremos informar que el usuario {{ $details['name-user-creator'] }} ha creado el Ticket #{{ $details['id-ticket'] }} el {{ $details['date-create'] }}</p>
            <p><strong>
                <a href="{{ url('http://localhost/mys/wp-admin/admin.php?page=mys_crm_hub&sub-page=page-ticket&id-ticket=' . $details['id-ticket']) }}" style="color: #0066cc; text-decoration: none;">Ver ticket</a>
            </strong></p>
        </div>
        <div class="footer">
            <p>&copy; 2024 Motos & Servitecas de Colombia SAS. Todos los derechos reservados.</p>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>