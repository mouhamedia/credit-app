@extends('layouts.app') {{-- Assurez-vous que 'layouts.app' pointe vers le layout Samacahier --}}

@section('title', 'Connexion - Samacahier')

@section('content')
<div class="row justify-content-center">
    {{-- La colonne prend toute la largeur sur mobile, et une taille réduite sur desktop --}}
    <div class="col-12 col-sm-8 col-md-6 col-lg-5">

        <div class="card shadow-lg border-0 rounded-4">
            
            {{-- Entête mis à jour : couleur 'bg-success' et icône 'Samacahier' --}}
            <div class="card-header bg-success text-white text-center py-4 rounded-top-4">
                <h2 class="mb-0 fw-bold">
                    <i class="bi bi-journal-check me-2"></i> Samacahier
                </h2>
                <small class="text-white-50">Connexion administrateur / boutiquier</small>
            </div>

            <div class="card-body p-4 p-md-5">

                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i> 
                        <strong>Erreur de connexion :</strong>
                        <ul class="mb-0 mt-2">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    {{-- Champ Email - Utilisation des Input Groups pour un design plus pro --}}
                    <div class="mb-4">
                        <label for="email" class="form-label fw-bold">Email</label>
                        <div class="input-group input-group-lg">
                            <span class="input-group-text bg-light border-end-0"><i class="bi bi-envelope-fill text-muted"></i></span>
                            <input type="email" name="email" id="email" 
                                class="form-control" 
                                value="{{ old('email') }}" 
                                required 
                                autofocus 
                                placeholder="votre@email.com">
                        </div>
                    </div>

                    {{-- Champ Mot de passe --}}
                    <div class="mb-4">
                        <label for="password" class="form-label fw-bold">Mot de passe</label>
                        <div class="input-group input-group-lg">
                            <span class="input-group-text bg-light border-end-0"><i class="bi bi-lock-fill text-muted"></i></span>
                            <input type="password" name="password" id="password" 
                                class="form-control" 
                                required 
                                placeholder="••••••••">
                        </div>
                    </div>

                    {{-- Bouton de connexion --}}
                    <div class="d-grid mt-4">
                        <button type="submit" class="btn btn-success btn-lg fw-bold shadow-sm">
                            <i class="bi bi-person-circle me-2"></i> Se connecter
                        </button>
                    </div>
                </form>

                <div class="text-center mt-5 pt-3 border-top">
                    {{-- Lien client --}}
                    <p class="text-muted mb-3">
                        Vous êtes un client ? 
                        <a href="{{ route('client.login') }}" class="text-success fw-bold">
                            Accéder avec votre code
                        </a>
                    </p>
                    
                    {{-- Signature personnelle --}}
                    <p class="mb-0 small text-dark fw-bold">
                        Produit par Diamouhamed
                    </p>
                    <span class="text-danger fw-bolder fst-italic">
                        Better Call Dia
                    </span>
                </div>

            </div>
        </div>

        <div class="text-center mt-4 text-muted">
            <small>© {{ date('Y') }} Samacahier - Gestion simplifiée</small>
        </div>
    </div>
</div>
@endsection