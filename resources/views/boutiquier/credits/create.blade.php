@extends('layouts.boutiquier')

@section('title', 'Nouveau Crédit - ' . $client->name)

@section('content')
<div class="container mt-4">

    {{-- En-tête avec navigation --}}
    <div class="mb-4 d-flex justify-content-between align-items-center">
        <div>
            <a href="{{ route('boutiquier.clients.credits', $client->id) }}" class="btn btn-outline-secondary me-2">
                <i class="bi bi-arrow-left"></i> Fiche Client
            </a>
            <a href="{{ route('boutiquier.clients.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-people"></i> Liste des clients
            </a>
        </div>
        <h1 class="text-success fw-bold">
            <i class="bi bi-plus-circle-fill me-2"></i> Nouveau Crédit
        </h1>
    </div>

    {{-- INFOS CLIENT --}}
    <div class="card bg-light border-success shadow-sm mb-4">
        <div class="card-body">
            <h3 class="text-dark mb-1">Client : <strong>{{ $client->name }}</strong></h3>
            <p class="text-muted mb-0">
                <i class="bi bi-telephone me-1"></i> {{ $client->phone }} 
                <span class="mx-2">|</span> 
                <i class="bi bi-key me-1"></i> Code : <code class="bg-white border text-dark px-2 py-1 rounded">{{ $client->code_unique }}</code>
            </p>
        </div>
    </div>

    <div class="card shadow border-success">
        <div class="card-header bg-success text-white text-center">
            <h4 class="mb-0 fw-bold"><i class="bi bi-journal-plus me-2"></i> Détails de la transaction</h4>
        </div>
        <div class="card-body p-4">
            
            {{-- Affichage des erreurs de validation (si nécessaire) --}}
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('boutiquier.credits.store') }}" method="POST">
                @csrf
                <input type="hidden" name="client_id" value="{{ $client->id }}">

                <div class="row g-4">
                    <div class="col-md-7">
                        <div class="mb-3">
                            <label class="form-label fw-bold"><i class="bi bi-card-text me-1"></i> Article / Description</label>
                            <input type="text" name="article" class="form-control form-control-lg @error('article') is-invalid @enderror" placeholder="Ex: Sac de riz, Rechargement de 500" value="{{ old('article') }}" required autofocus>
                            @error('article')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="mb-3">
                            <label class="form-label fw-bold"><i class="bi bi-cash me-1"></i> Montant (FCFA)</label>
                            <div class="input-group input-group-lg">
                                <input type="number" name="montant" class="form-control @error('montant') is-invalid @enderror" placeholder="Ex: 5000" value="{{ old('montant') }}" required min="1">
                                <span class="input-group-text">FCFA</span>
                                @error('montant')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end mt-4 pt-3 border-top">
                    {{-- Bouton Annuler/Retour --}}
                    <a href="{{ route('boutiquier.clients.credits', $client->id) }}" class="btn btn-outline-secondary btn-lg me-3">
                        <i class="bi bi-x-lg me-1"></i> Annuler
                    </a>
                    {{-- Bouton d'action principal Samacahier (Vert) --}}
                    <button type="submit" class="btn btn-success btn-lg fw-bold shadow">
                        <i class="bi bi-check-lg me-2"></i> Enregistrer le Crédit
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection