<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex">
    <title>Panobooking API · v1</title>
    <link rel="icon" href="{{ asset('images/panaicon.png') }}" type="image/png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swagger-ui-dist@5.17.14/swagger-ui.css">
    <style>
        body { margin: 0; background: #fff; }
        .pb-bar { display: flex; align-items: center; gap: .75rem; padding: .75rem 1.25rem; background: #0f1b2d; color: #fff; font: 600 15px/1.4 system-ui, sans-serif; }
        .pb-bar img { height: 28px; background: #fff; border-radius: 6px; padding: 2px 6px; }
        .pb-bar a { color: #9ccfff; margin-left: auto; font-weight: 500; }
        .swagger-ui .topbar { display: none; }
    </style>
</head>
<body>
    <div class="pb-bar">
        <img src="{{ asset('images/panologo.png') }}" alt="Panobooking">
        <span>Mobile API v1</span>
        <a href="{{ route('api.spec') }}">openapi.yaml</a>
    </div>
    <div id="swagger"></div>
    <script src="https://cdn.jsdelivr.net/npm/swagger-ui-dist@5.17.14/swagger-ui-bundle.js" crossorigin></script>
    <script>
        window.ui = SwaggerUIBundle({
            url: @json(route('api.spec')),
            dom_id: '#swagger',
            deepLinking: true,
            persistAuthorization: true,
            tryItOutEnabled: true,
            displayRequestDuration: true,
        });
    </script>
</body>
</html>
