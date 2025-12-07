<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes Crédits - {{ $client->name }} | Samacahier</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    
    <style>
        /* Dégradé Samacahier pour l'espace client (plus attractif) */
        .bg-gradient-samacahier {
            /* Utilisation d'un vert légèrement différent pour l'arrière-plan */
            background: #28a745; 
            background: linear-gradient(135deg, #28a745 0%, #1e7e34 100%); 
            min-height: 100vh;
            color: #fff; /* Texte par défaut en blanc sur le dégradé */
            display: flex;
            flex-direction: column;
        }
        .main-content {
            flex-grow: 1;
        }
        /* Style pour la signature */
        .signature-text {
            line-height: 1.2;
        }
    </style>
</head>
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
    
    <main class="container my-4 main-content">
        
        <div class="card bg-white p-4 shadow-lg rounded-3">
            <div class="text-center mb-5">
                <h2 class="text-success fw-bold">
                    <i class="bi bi-person-circle me-2"></i> Bonjour <strong>{{ $client->name }}</strong>
                </h2>
                <p class="text-muted lead">Voici vos crédits en cours</p>
            </div>
    
            <div class="row justify-content-center mb-5">
                <div class="col-md-8 col-lg-6">
                    <div class="card border-danger shadow-lg">
                        <div class="card-body text-center bg-light">
                            <h3 class="text-danger fw-bold mb-3">
                                <i class="bi bi-exclamation-octagon-fill me-2"></i> Montant Total Impayé
                            </h3>
                            <h1 class="display-3 text-danger fw-bold">
                                {{ number_format($client->credits->where('statut', 'impaye')->sum('montant'), 0, ',', ' ') }} FCFA
                            </h1>
                        </div>
                    </div>
                </div>
            </div>
    
            <h4 class="mb-3 text-dark">Détails des transactions impayées :</h4>
            @forelse($client->credits->where('statut', 'impaye') as $credit)
                <div class="card mb-3 border-danger shadow-sm">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <h5 class="mb-1 text-dark fw-bold">{{ $credit->article }}</h5>
                                <small class="text-muted">
                                    <i class="bi bi-calendar me-1"></i> Date : {{ $credit->created_at->format('d/m/Y') }}
                                </small>
                            </div>
                            <div class="col-4 text-end">
                                <h4 class="text-danger fw-bold mb-1">
                                    {{ number_format($credit->montant, 0, ',', ' ') }} FCFA
                                </h4>
                                <span class="badge bg-danger fs-6 rounded-pill">
                                    <i class="bi bi-x-circle-fill me-1"></i> Impayé
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="alert alert-success text-center p-5 border-success">
                    <h3><i class="bi bi-check-circle-fill me-2"></i> Félicitations !</h3>
                    <p class="fs-4 mb-0">Vous n'avez aucun crédit impayé.</p>
                </div>
            @endforelse
        </div>
    </main>
    
    <footer class="text-center py-3 footer mt-auto">
        <div class="container">
            <p class="mb-2 small text-white-50">&copy; {{ date('Y') }} Samacahier - Tous droits réservés</p>
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