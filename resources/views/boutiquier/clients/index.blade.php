@extends('layouts.boutiquier')
@section('title', 'Gestion des Clients')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="text-success fw-bold">
        <i class="bi bi-people-fill me-2"></i> Mes Clients
    </h1>
    {{-- Bouton principal Samacahier (Vert) --}}
    <a href="{{ route('boutiquier.clients.create') }}" class="btn btn-success btn-lg fw-bold shadow-sm">
        <i class="bi bi-person-add me-2"></i> Ajouter un Client
    </a>
</div>

{{-- Messages flash --}}
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="row">
    @forelse($clients as $client)
        {{-- Calcul des totaux (suppose que vous pouvez accéder aux relations ici) --}}
        @php
            // Ces calculs peuvent être lourds. Il est recommandé de les faire dans le contrôleur ou d'utiliser une relation sommée.
            $total_impaye = $client->credits()->where('statut', 'impaye')->sum('montant');
            $total_paye = $client->credits()->where('statut', 'paye')->sum('montant');
        @endphp

        <div class="col-12 col-md-6 col-lg-4 mb-4">
            <div class="card border-success shadow-sm h-100">
                <div class="card-body">
                    <h4 class="card-title text-success fw-bold mb-3">
                        <i class="bi bi-person me-1"></i> {{ $client->name }}
                    </h4>
                    
                    {{-- Informations rapides --}}
                    <p class="card-text small mb-2">
                        <strong><i class="bi bi-telephone me-1"></i> Tél :</strong> {{ $client->phone }}
                    </p>
                    <p class="card-text mb-4">
                        <strong><i class="bi bi-key me-1"></i> Code Client :</strong> 
                        <code class="bg-light border text-dark p-1 rounded">{{ $client->code_unique }}</code>
                    </p>

                    <hr>

                    {{-- Totaux financiers --}}
                    <div class="d-flex justify-content-between mb-3">
                        <div>
                            <span class="text-danger small fw-bold"><i class="bi bi-arrow-down-left-circle-fill me-1"></i> Impayé :</span><br>
                            <span class="text-danger fw-bold fs-5">{{ number_format($total_impaye, 0, ',', ' ') }} FCFA</span>
                        </div>
                        <div>
                            <span class="text-success small fw-bold text-end"><i class="bi bi-arrow-up-right-circle-fill me-1"></i> Payé :</span><br>
                            <span class="text-success fw-bold fs-5 text-end">{{ number_format($total_paye, 0, ',', ' ') }} FCFA</span>
                        </div>
                    </div>

                    {{-- Boutons d'action --}}
                    <div class="d-grid gap-2 mt-3">
                        <a href="{{ route('boutiquier.clients.credits', $client->id) }}" class="btn btn-info btn-sm fw-bold">
                            <i class="bi bi-eye-fill me-1"></i> Voir Fiche & Historique
                        </a>
                        <a href="{{ route('boutiquier.credits.create', $client->id) }}" class="btn btn-success btn-sm fw-bold">
                            <i class="bi bi-plus-circle-fill me-1"></i> Nouveau Crédit
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="alert alert-info text-center p-5">
            <h4 class="mb-3"><i class="bi bi-info-circle-fill me-2"></i> Aucun client trouvé.</h4>
            <a href="{{ route('boutiquier.clients.create') }}" class="btn btn-success btn-lg">
                <i class="bi bi-person-add me-2"></i> Créez le premier client !
            </a>
        </div>
    @endforelse
</div>
@endsection