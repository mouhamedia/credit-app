<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion Client - Samacahier</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

    <style>
        /* S'assurer que le contenu est parfaitement centré */
        .min-vh-100 {
            min-height: 100vh;
        }
    </style>
</head>
{{-- Utilisation de la couleur de thème Samacahier (vert) pour le fond --}}
<body class="bg-success">
    <div class="container">
        <div class="row justify-content-center align-items-center min-vh-100">
            {{-- Optimisation pour mobile (col-12) et desktop (col-md-5) --}}
            <div class="col-12 col-sm-8 col-md-6 col-lg-5">
                <div class="card border-0 shadow-lg rounded-4">
                    <div class="card-body p-4 p-md-5 text-center">

                        {{-- Logo et Nom de l'application --}}
                        <h1 class="mb-2 fw-bold text-success">
                            <i class="bi bi-journal-check me-2"></i> Samacahier
                        </h1>
                        <h4 class="mb-4 text-muted">Espace Client</h4>
                        <p class="lead mb-4">Entrez votre code unique</p>

                        @if(session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <form action="{{ route('client.login.submit') }}" method="POST">
                            @csrf
                            
                            {{-- Champ Code Unique avec Input Group pour le design --}}
                            <div class="mb-4">
                                <div class="input-group input-group-lg">
                                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-key-fill text-muted"></i></span>
                                    <input type="text" name="code_unique" 
                                           class="form-control text-center" 
                                           placeholder="Ex: CLI-2024-XXXX" 
                                           maxlength="15" 
                                           required 
                                           autofocus>
                                </div>
                            </div>

                            <button class="btn btn-success btn-lg w-100 fw-bold shadow-sm">
                                <i class="bi bi-lock-fill me-2"></i> Accéder à mon compte
                            </button>
                        </form>

                        {{-- Lien de retour --}}
                        <div class="mt-4 pt-3 border-top">
                            <a href="{{ route('login') }}" class="text-muted small">
                                <i class="bi bi-arrow-left-circle-fill me-1"></i> Retour à la connexion Admin/Boutiquier
                            </a>
                        </div>
                    </div>
                </div>

                <div class="text-center mt-4">
                    <p class="mb-0 small text-white fw-bold">
                        Produit par Diamouhamed
                    </p>
                    <span class="text-danger fw-bolder fst-italic small">
                        Better Call Dia
                    </span>
                    <p class="mb-0 mt-1 text-white-50 small">&copy; {{ date('Y') }} Samacahier</p>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>