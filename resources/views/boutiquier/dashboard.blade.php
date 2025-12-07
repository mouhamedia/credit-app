@extends('layouts.boutiquier')

@section('title', 'Tableau de Bord - Samacahier')

@section('content')

{{-- TITRE --}}
<h1 class="mb-5 text-success fw-bolder border-bottom pb-2">
    <i class="bi bi-speedometer2 me-2"></i> Tableau de Bord Samacahier
</h1>

## 📊 Performance Financière

<div class="row mb-5 g-4">
    
    {{-- Calcul des totaux (Suppositions pour le contexte de la vue) --}}
    @php
        $total_impaye = $credits->where('statut', 'impaye')->sum('montant');
        $total_paye = $credits->where('statut', 'paye')->sum('montant');
        $total_credits = $credits->sum('montant');
    @endphp

    {{-- Carte 1 : Total Impayé (Le plus critique) --}}
    <div class="col-12 col-lg-4 col-md-6">
        <div class="card border-danger shadow-lg h-100">
            <div class="card-body bg-light text-center">
                <h5 class="card-title text-danger fw-bold mb-3"><i class="bi bi-exclamation-triangle-fill me-2"></i> TOTAL IMPAYÉ</h5>
                <h2 class="display-5 fw-bolder text-danger">
                    {{ number_format($total_impaye, 0, ',', ' ') }} FCFA
                </h2>
                <p class="small text-muted mb-0">À recouvrer</p>
            </div>
        </div>
    </div>

    {{-- Carte 2 : Total Payé (Succès) --}}
    <div class="col-12 col-lg-4 col-md-6">
        <div class="card border-success shadow-lg h-100">
            <div class="card-body bg-light text-center">
                <h5 class="card-title text-success fw-bold mb-3"><i class="bi bi-check-circle-fill me-2"></i> TOTAL PAYÉ</h5>
                <h2 class="display-5 fw-bolder text-success">
                    {{ number_format($total_paye, 0, ',', ' ') }} FCFA
                </h2>
                <p class="small text-muted mb-0">Total des remboursements</p>
            </div>
        </div>
    </div>
    
    {{-- Carte 3 : Total Global (Information Générale) --}}
    <div class="col-12 col-lg-4 col-md-6">
        <div class="card border-info shadow-lg h-100">
            <div class="card-body bg-light text-center">
                <h5 class="card-title text-info fw-bold mb-3"><i class="bi bi-journal-text me-2"></i> TOTAL CRÉDIT</h5>
                <h2 class="display-5 fw-bolder text-info">
                    {{ number_format($total_credits, 0, ',', ' ') }} FCFA
                </h2>
                <p class="small text-muted mb-0">Montant total de toutes les transactions</p>
            </div>
        </div>
    </div>
    
    {{-- Carte 4 : Nombre de Clients --}}
    <div class="col-12 col-lg-4 col-md-6">
        <div class="card text-white bg-success shadow-sm h-100">
            <div class="card-body d-flex flex-column justify-content-between">
                <h5 class="card-title fw-bold"><i class="bi bi-people-fill me-2"></i> Nombre de Clients</h5>
                <h2 class="display-4 fw-bold">{{ $clients->count() }}</h2>
                <a href="{{ route('boutiquier.clients.index') }}" class="btn btn-sm btn-outline-light fw-bold">
                    Gérer les clients <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>

    {{-- Carte 5 : Nombre de Transactions --}}
    <div class="col-12 col-lg-4 col-md-6">
        <div class="card text-white bg-secondary shadow-sm h-100">
            <div class="card-body d-flex flex-column justify-content-between">
                <h5 class="card-title fw-bold"><i class="bi bi-list-check me-2"></i> Total Transactions</h5>
                <h2 class="display-4 fw-bold">{{ $credits->count() }}</h2>
                <a href="{{ route('boutiquier.clients.index') }}" class="btn btn-sm btn-outline-light fw-bold">
                    Voir les historiques <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>
    
</div>

---

## 👥 Gestion Rapide des Clients

<div class="d-flex justify-content-between align-items-center mb-3 mt-4">
    <h4><i class="bi bi-person-lines-fill me-2"></i> 5 Derniers Clients Enregistrés</h4>
    <a href="{{ route('boutiquier.clients.index') }}" class="btn btn-sm btn-outline-success">
        <i class="bi bi-person-plus"></i> Voir tout & Ajouter
    </a>
</div>

@if($clients->count() > 0)
    <div class="list-group mb-5 shadow-sm">
        @foreach($clients->take(5) as $c)
            @php
                $client_impaye = $c->credits()->where('statut', 'impaye')->sum('montant');
            @endphp
            <li class="list-group-item d-flex justify-content-between align-items-center list-group-item-action py-3">
                <div class="me-3">
                    <strong class="text-success fs-5">{{ $c->name }}</strong><br>
                    <small class="text-muted"><i class="bi bi-key me-1"></i> Code: <code class="text-info">{{ $c->code_unique }}</code></small>
                </div>
                
                <div class="text-end me-3">
                    @if ($client_impaye > 0)
                        <span class="badge bg-danger fs-6 p-2 shadow-sm">{{ number_format($client_impaye, 0, ',', ' ') }} FCFA Impayé</span>
                    @else
                        <span class="badge bg-success fs-6 p-2 shadow-sm">À jour</span>
                    @endif
                </div>

                <div>
                    <a href="{{ route('boutiquier.clients.credits', $c->id) }}" class="btn btn-sm btn-outline-info me-2">
                        <i class="bi bi-eye"></i> Voir Fiche
                    </a>
                    <a href="{{ route('boutiquier.credits.create', $c->id) }}" class="btn btn-sm btn-success">
                        <i class="bi bi-plus-circle"></i> Crédit
                    </a>
                </div>
            </li>
        @endforeach
    </div>
@else
    <div class="alert alert-info p-3">
        <i class="bi bi-info-circle me-2"></i> Aucun client pour le moment. 
        <a href="{{ route('boutiquier.clients.create') }}" class="alert-link fw-bold">Créez le premier !</a>
    </div>
@endif

---

## 📝 Derniers Crédits Enregistrés

<h4 class="mt-5 mb-3"><i class="bi bi-clock-history me-2"></i> 10 Dernières Transactions</h4>
@if($credits->count() > 0)
    <div class="list-group shadow-sm">
        @foreach($credits->sortByDesc('created_at')->take(10) as $credit)
            @php
                $is_impaye = $credit->statut !== 'paye';
            @endphp
            <li class="list-group-item d-flex justify-content-between align-items-center list-group-item-action {{ $is_impaye ? 'border-start border-danger border-4' : 'border-start border-success border-4' }}">
                <div class="d-flex align-items-center">
                    <i class="bi bi-person-fill fs-4 me-3 {{ $is_impaye ? 'text-danger' : 'text-success' }}"></i>
                    <div>
                        <strong class="text-dark d-block">{{ $credit->client->name }}</strong>
                        <span class="text-muted small">{{ $credit->article }}</span>
                    </div>
                </div>
                <div>
                    <span class="{{ $is_impaye ? 'text-danger' : 'text-success' }} fw-bolder me-3 fs-5">
                        {{ number_format($credit->montant, 0, ',', ' ') }} FCFA
                    </span>
                    <small class="text-muted"><i class="bi bi-calendar"></i> {{ $credit->created_at->format('d/m/Y') }}</small>
                </div>
            </li>
        @endforeach
    </div>
@else
    <p class="text-muted">Aucun crédit créé pour le moment.</p>
@endif

@endsection