@extends('layouts.boutiquier')

@section('title', 'Ajouter un Client')

@section('content')
<div class="container mt-4">
    
    <div class="mb-4 d-flex justify-content-between align-items-center">
        <h1 class="text-success fw-bold">
            <i class="bi bi-person-add me-2"></i> Ajouter un Nouveau Client
        </h1>
        {{-- Boutons de navigation --}}
        <div>
            <a href="{{ route('boutiquier.clients.index') }}" class="btn btn-outline-secondary me-2">
                <i class="bi bi-arrow-left"></i> Liste des clients
            </a>
            <a href="{{ route('boutiquier.dashboard') }}" class="btn btn-outline-success">
                <i class="bi bi-house-door"></i> Tableau de bord
            </a>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card shadow border-success">
                <div class="card-header bg-success text-white text-center">
                    <h4 class="mb-0 fw-bold"><i class="bi bi-file-earmark-person me-2"></i> Enregistrer le Client</h4>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success text-center alert-dismissible fade show">
                            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    {{-- Gestion des erreurs de validation (optionnel, mais recommandé) --}}
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('boutiquier.clients.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-bold"><i class="bi bi-person me-1"></i> Nom complet</label>
                            <input type="text" name="name" class="form-control form-control-lg @error('name') is-invalid @enderror" value="{{ old('name') }}" required autofocus>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold"><i class="bi bi-telephone me-1"></i> Téléphone</label>
                            <input type="text" name="phone" class="form-control form-control-lg @error('phone') is-invalid @enderror" value="{{ old('phone') }}" placeholder="Ex: 771234567" required>
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-bold"><i class="bi bi-house-door me-1"></i> Adresse <small class="text-muted">(optionnel)</small></label>
                            <input type="text" name="address" class="form-control form-control-lg" value="{{ old('address') }}" placeholder="Ex: Marché Central, boutique n°12">
                        </div>
                        <button type="submit" class="btn btn-success btn-lg w-100 fw-bold shadow">
                            <i class="bi bi-check-circle-fill me-2"></i> Créer le client
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection