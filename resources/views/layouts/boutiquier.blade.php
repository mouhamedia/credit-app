<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    {{-- Mise à jour du titre pour Samacahier --}}
    <title>@yield('title') - Samacahier</title> 
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

    <style>
        /* CSS pour positionner le footer en bas de page */
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        .main-content {
            flex-grow: 1;
        }
        .footer {
            flex-shrink: 0; 
        }
        /* Style pour la signature */
        .signature-text {
            line-height: 1.2;
        }
    </style>
</head>
{{-- Le corps reste en bg-light pour le contenu, la nav et le footer apportent la couleur --}}
<body class="bg-light d-flex flex-column min-vh-100">

    <nav class="navbar navbar-expand-lg navbar-dark bg-success shadow-sm">
        <div class="container-fluid">
            {{-- Lien de la marque mis à jour pour Samacahier --}}
            <a class="navbar-brand fw-bold" href="{{ route('boutiquier.dashboard') }}">
                <i class="bi bi-journal-check me-2"></i> Samacahier
            </a>
            <div class="navbar-nav ms-auto align-items-center">
                <span class="text-white me-3">
                    Bonjour <strong>{{ auth()->user()->name }}</strong>
                    <span class="badge bg-light text-success ms-1">Boutiquier</span>
                </span>

                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>

                <button type="button"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                        class="btn btn-outline-light btn-sm">
                    <i class="bi bi-box-arrow-right"></i> Déconnexion
                </button>
            </div>
        </div>
    </nav>

    <main class="container mt-4 mb-5 main-content">
        {{-- Messages Flash --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        
        @yield('content')
    </main>

    <footer class="bg-dark text-center py-3 footer">
        <div class="container">
            {{-- Ligne de copyright standard --}}
            <p class="mb-2 text-muted small">&copy; {{ date('Y') }} Samacahier - Tous droits réservés</p>

            {{-- Nouvelle section de signature --}}
            <div class="signature-text">
                <p class="mb-0 small text-white fw-bold">
                    Produit par Diamouhamed
                </p>
                <span class="text-danger fw-bolder fst-italic small">
                    Better Call Dia
                </span>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>