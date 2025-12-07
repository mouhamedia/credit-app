@extends('layouts.admin')

@section('title', 'Créer un Boutiquier')

@section('content')
<div class="container mt-4">

    <div class="mb-4 d-flex justify-content-between align-items-center">
        <h1 class="text-primary fw-bold">
            <i class="bi bi-person-plus-fill me-2"></i> Ajouter un Boutiquier
        </h1>
        {{-- Bouton de retour à la liste (supposons qu'il existe) --}}
        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">
            <i class="bi bi-house-door me-1"></i> Tableau de bord
        </a>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card shadow border-primary">
                <div class="card-header bg-primary text-white text-center">
                    <h4 class="mb-0 fw-bold"><i class="bi bi-person-circle me-2"></i> Informations du Nouveau Compte</h4>
                </div>
                <div class="card-body">
                    {{-- Affichage des erreurs de validation --}}
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('admin.boutiquiers.store') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label fw-bold"><i class="bi bi-person me-1"></i> Nom complet</label>
                            <input type="text" name="name" class="form-control form-control-lg" value="{{ old('name') }}" required autofocus>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold"><i class="bi bi-envelope me-1"></i> E-mail (Identifiant)</label>
                            <input type="email" name="email" class="form-control form-control-lg" value="{{ old('email') }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold"><i class="bi bi-telephone me-1"></i> Téléphone</label>
                            <input type="text" name="phone" class="form-control form-control-lg" value="{{ old('phone') }}">
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold"><i class="bi bi-lock me-1"></i> Mot de passe initial</label>
                            <input type="password" name="password" class="form-control form-control-lg" required>
                        </div>

                        <button type="submit" class="btn btn-primary btn-lg w-100 fw-bold shadow">
                            <i class="bi bi-check-circle-fill me-2"></i> Créer le Boutiquier
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection