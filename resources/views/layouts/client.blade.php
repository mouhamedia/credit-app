<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Samacahier</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    
    <style>
        /* Dégradé Samacahier pour l'espace client (plus attractif) */
        .bg-gradient-samacahier {
            background: linear-gradient(135deg, #28a745 0%, #1e7e34 100%); 
            min-height: 100vh; /* Assure que le dégradé couvre toute la hauteur */
        }
    </style>
</head>
{{-- Application du dégradé à l'arrière-plan --}}
<body class="bg-gradient-samacahier">
    
    <nav class="navbar navbar-expand navbar-dark bg-success shadow-sm">
        <div class="container-fluid">
            {{-- Nom de l'application mis à jour --}}
            <a class="navbar-brand fw-bold" href="#">
                <i class="bi bi-journal-check me-2"></i> Samacahier - Espace Client
            </a>
            
            {{-- Bouton de déconnexion stylé --}}
            <a href="{{ route('client.logout') }}" class="btn btn-outline-light btn-sm fw-bold">
                <i class="bi bi-box-arrow-right me-1"></i> Déconnexion
            </a>
        </div>
    </nav>
    
    {{-- CONTENU : Utilisez un fond blanc pour le contenu pour le faire ressortir sur le dégradé --}}
    <div class="container my-4">
        {{-- Ajout d'une carte ou d'un conteneur pour le contenu pour le faire ressortir du dégradé --}}
        <div class="card p-4 shadow-lg rounded-3">
            @yield('content')
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>