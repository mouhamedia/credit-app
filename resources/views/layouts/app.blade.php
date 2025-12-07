<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    {{-- Mise à jour du nom de l'application dans le titre (Nom en dur pour la cohérence) --}}
    <title>@yield('title', 'Samacahier')</title> 

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

    {{-- Styles personnalisés pour un look plus moderne --}}
    <style>
        /* S'assurer que le contenu principal est au-dessus du footer collé */
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        .footer {
            /* Maintient le footer en bas même si le contenu est court */
            flex-shrink: 0; 
        }
        .main-content {
            flex-grow: 1;
            padding-bottom: 20px;
        }
        .navbar-brand {
            display: flex;
            align-items: center;
        }
        /* Style pour la signature */
        .signature-text {
            line-height: 1.2;
        }
    </style>
</head>
<body class="bg-light d-flex flex-column min-vh-100">

    {{-- Barre de navigation : Couleur Samacahier (vert) --}}
    <nav class="navbar navbar-expand-lg navbar-dark bg-success shadow-sm">
        <div class="container-fluid">
            
            {{-- Lien de la marque mis à jour avec la navigation conditionnelle --}}
            <a class="navbar-brand fw-bold" href="{{ auth()->check() ? (auth()->user()->role === 'admin' ? route('admin.dashboard') : route('boutiquier.dashboard')) : route('login') }}">
                <i class="bi bi-journal-check me-2"></i> Samacahier
            </a>

            @if(Auth::check())
                {{-- Bouton pour le menu hamburger sur mobile --}}
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarNav">
                    <div class="navbar-nav ms-auto align-items-lg-center">
                        
                        {{-- Information de l'utilisateur --}}
                        <span class="text-white me-lg-4 my-2 my-lg-0 border-end border-lg-0 pe-lg-4">
                            Bonjour <strong>{{ Auth::user()->name }}</strong>
                            @if(Auth::user()->role === 'admin')
                                {{-- Badge Admin --}}
                                <span class="badge bg-danger text-white ms-2">Admin</span>
                            @else
                                {{-- Badge Boutiquier --}}
                                <span class="badge bg-light text-success ms-2">Boutiquier</span>
                            @endif
                        </span>

                        {{-- Bouton de déconnexion --}}
                        <button type="button"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                                class="btn btn-outline-light btn-sm mt-2 mt-lg-0">
                            <i class="bi bi-box-arrow-right"></i> Déconnexion
                        </button>
                    </div>
                </div>
            @endif
        </div>
    </nav>

    {{-- FORMULAIRE CACHÉ DE DÉCONNEXION --}}
    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
        @csrf
    </form>

    <main class="container my-5 flex-grow-1 main-content">
        
        {{-- Messages flash --}}
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

    {{-- FOOTER MIS À JOUR AVEC LA SIGNATURE --}}
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