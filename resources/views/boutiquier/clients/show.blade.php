@extends('layouts.boutiquier')

@section('title', $client->name)

@section('content')
<div class="container mt-4">

    {{-- AFFICHER LE MESSAGE DE SUCCÈS (pour l'ajout rapide ou le marquage Payé) --}}
    @if(session('success'))
        <div class="alert alert-success text-center alert-dismissible fade show mb-4">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    
    @if ($errors->any())
        <div class="alert alert-danger">
            Le formulaire n'a pas pu être soumis. Veuillez vérifier les champs.
        </div>
    @endif

    {{-- NAVIGATION / TITRE --}}
    <div class="mb-4 d-flex justify-content-between align-items-center border-bottom pb-2">
        <h1 class="text-success fw-bold">
            <a href="{{ route('boutiquier.clients.index') }}" class="btn btn-outline-success me-3">
                <i class="bi bi-arrow-left"></i>
            </a>
            Fiche Client : {{ $client->name }}
        </h1>
        {{-- Bouton pour aller vers la liste générale --}}
        <a href="{{ route('boutiquier.clients.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-people-fill me-2"></i> Gérer les Clients
        </a>
    </div>

    {{-- INFORMATIONS CLIENT --}}
    <div class="card border-success shadow mb-4">
        <div class="card-body text-center bg-light">
            <h2 class="text-dark mb-3">
                <i class="bi bi-person-fill me-2 text-success"></i> **{{ $client->name }}**
            </h2>
            <p class="fs-5 text-muted">
                <strong><i class="bi bi-telephone-fill me-1"></i> Tél :</strong> {{ $client->phone }} 
                <span class="mx-3">|</span> 
                <strong><i class="bi bi-key-fill me-1"></i> Code :</strong> 
                <code class="bg-white border text-primary px-3 py-1 rounded fs-6">{{ $client->code_unique }}</code>
            </p>
        </div>
    </div>

    {{-- CARTES DE TOTALS --}}
    <div class="row mb-5 text-center g-3">
        <div class="col-md-4">
            <div class="card border-danger shadow-lg h-100">
                <div class="card-body">
                    <h5 class="text-danger fw-bold"><i class="bi bi-x-circle-fill me-1"></i> TOTAL IMPAYÉ</h5>
                    <h1 class="display-4 text-danger fw-bold">
                        {{ number_format($client->credits()->where('statut', 'impaye')->sum('montant'), 0, ',', ' ') }} FCFA
                    </h1>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-success shadow-lg h-100">
                <div class="card-body">
                    <h5 class="text-success fw-bold"><i class="bi bi-check-circle-fill me-1"></i> TOTAL PAYÉ</h5>
                    <h1 class="display-4 text-success fw-bold">
                        {{ number_format($client->credits()->where('statut', 'paye')->sum('montant'), 0, ',', ' ') }} FCFA
                    </h1>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-info shadow-lg h-100">
                <div class="card-body">
                    <h5 class="text-info fw-bold"><i class="bi bi-wallet2 me-1"></i> TOTAL CRÉDIT</h5>
                    <h1 class="display-4 text-info fw-bold">
                        {{ number_format($client->credits()->sum('montant'), 0, ',', ' ') }} FCFA
                    </h1>
                </div>
            </div>
        </div>
    </div>

    {{-- FORMULAIRE D'AJOUT RAPIDE --}}
    <div class="card shadow mb-5 border-success">
        <div class="card-header bg-success text-white">
            <h4 class="mb-0 fw-bold"><i class="bi bi-plus-circle-fill me-2"></i> Ajout Rapide de Crédit</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('boutiquier.credits.store') }}" method="POST" class="row g-3 align-items-center">
                @csrf
                <input type="hidden" name="client_id" value="{{ $client->id }}">
                <div class="col-md-5">
                    <label class="form-label small text-muted d-block">Article / Description</label>
                    <input type="text" name="article" class="form-control form-control-lg @error('article') is-invalid @enderror" placeholder="Ex: Sac de riz, T-shirt" value="{{ old('article') }}" required autofocus>
                    @error('article') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label small text-muted d-block">Montant (FCFA)</label>
                    <div class="input-group input-group-lg">
                        <input type="number" name="montant" class="form-control @error('montant') is-invalid @enderror" placeholder="Montant" value="{{ old('montant') }}" required min="1">
                        <span class="input-group-text">FCFA</span>
                        @error('montant') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
                <div class="col-md-3 pt-3">
                    <button type="submit" class="btn btn-success btn-lg w-100 fw-bold shadow">
                        <i class="bi bi-plus-lg"></i> Enregistrer
                    </button>
                </div>
            </form>
        </div>
    </div>

    <h3 class="mb-3 text-dark"><i class="bi bi-list-columns-reverse me-2"></i> Historique des transactions</h3>

    {{-- TABLEAU DES CRÉDITS (PLUS LISIBLE QUE LES CARTES) --}}
    @if($client->credits->count() > 0)
        <div class="card shadow">
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th><i class="bi bi-calendar"></i> Date</th>
                            <th><i class="bi bi-tag"></i> Article</th>
                            <th class="text-end"><i class="bi bi-currency-dollar"></i> Montant</th>
                            <th class="text-center">Statut</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($client->credits()->latest()->get() as $credit)
                            <tr @if($credit->statut === 'paye') class="table-success" @endif>
                                <td class="text-muted">{{ $credit->created_at->format('d/m/Y') }}</td>
                                <td><strong class="text-dark">{{ $credit->article }}</strong></td>
                                <td class="text-end fw-bold {{ $credit->statut === 'paye' ? 'text-success' : 'text-danger' }}">
                                    {{ number_format($credit->montant, 0, ',', ' ') }} FCFA
                                </td>
                                <td class="text-center">
                                    @if($credit->statut === 'paye')
                                        <span class="badge bg-success fs-6"><i class="bi bi-check-circle"></i> Payé</span>
                                    @else
                                        <span class="badge bg-danger fs-6"><i class="bi bi-x-circle"></i> Impayé</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    {{-- C'EST ICI QUE LE BOUTON D'ACTION EST CORRECTEMENT STYLISÉ EN VERT --}}
                                    @if($credit->statut !== 'paye')
                                        <form action="{{ route('boutiquier.credits.paye', $credit->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-success btn-sm fw-bold"
                                                    onclick="return confirm('Confirmez-vous que ce crédit est PAYÉ ?')">
                                                <i class="bi bi-cash"></i> Marquer Payé
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-success fw-bold small"><i class="bi bi-check-lg"></i> Traité</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @else
        <div class="alert alert-info text-center p-5 fs-4">
            <i class="bi bi-info-circle-fill me-2"></i> Aucun crédit enregistré pour ce client.
        </div>
    @endif

    <div class="text-center mt-5">
        <a href="{{ route('boutiquier.clients.index') }}" class="btn btn-outline-secondary btn-lg px-5">
            <i class="bi bi-arrow-left-circle-fill me-2"></i> Retour à la liste des clients
        </a>
    </div>
</div>
@endsection