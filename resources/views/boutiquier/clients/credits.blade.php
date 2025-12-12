@extends('layouts.boutiquier')

@section('title', 'Crédits de ' . $client->name)

@section('content')
<div class="container mt-4">

    <!-- En-tête -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <a href="{{ route('boutiquier.clients.index') }}" class="btn btn-outline-secondary me-3">
                <i class="bi bi-arrow-left"></i>
            </a>
            <h2 class="mb-1 d-inline text-success fw-bold">
                <i class="bi bi-person-lines-fill me-2"></i> Crédits de <strong>{{ $client->name }}</strong>
            </h2>
            <p class="text-muted mt-2 mb-0 ms-5">
                <strong>Tél :</strong> {{ $client->phone }}
                <span class="mx-2">|</span>
                <strong>Code :</strong>
                <code class="bg-light border text-dark px-2 py-1 rounded">{{ $client->code_unique }}</code>
            </p>
        </div>
        <a href="{{ route('boutiquier.credits.create', $client->id) }}" 
           class="btn btn-success btn-lg shadow-sm fw-bold">
           <i class="bi bi-plus-circle-fill me-2"></i> Nouveau Crédit
        </a>
    </div>

    <!-- Cartes récap -->
    <div class="row mb-5">
        <div class="col-md-4 mb-3">
            <div class="card border-danger shadow-sm h-100">
                <div class="card-body text-center bg-light">
                    <h5 class="text-danger"><i class="bi bi-cash-stack me-1"></i> Total Impayé</h5>
                    <h2 class="text-danger fw-bold display-5">
                        {{ number_format($total_impaye, 0, ',', ' ') }} FCFA
                    </h2>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-3">
            <div class="card border-success shadow-sm h-100">
                <div class="card-body text-center bg-light">
                    <h5 class="text-success"><i class="bi bi-check-circle-fill me-1"></i> Total Payé</h5>
                    <h2 class="text-success fw-bold display-5">
                        {{ number_format($total_paye, 0, ',', ' ') }} FCFA
                    </h2>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-3">
            <div class="card border-info shadow-sm h-100">
                <div class="card-body text-center bg-light">
                    <h5 class="text-info"><i class="bi bi-journal-text me-1"></i> Total Crédit</h5>
                    <h2 class="text-info fw-bold display-5">
                        {{ number_format($total_credit, 0, ',', ' ') }} FCFA
                    </h2>
                </div>
            </div>
        </div>
    </div>

    <h3 class="mb-3 text-dark">Historique détaillé :</h3>

    @if($client->credits->count() > 0)
        <div class="card shadow">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0 fw-bold"><i class="bi bi-list-columns-reverse me-2"></i> Toutes les transactions</h5>
            </div>

            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Date</th>
                            <th>Article</th>
                            <th class="text-end">Montant</th>
                            <th class="text-center">Statut</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($client->credits as $credit)
                            <tr @if($credit->statut === 'paye') class="table-success" @endif>
                                <td>{{ $credit->created_at->format('d/m/Y') }}</td>
                                <td><strong>{{ $credit->article }}</strong></td>
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
                                    @if($credit->statut !== 'paye')
                                        <form action="{{ route('boutiquier.credits.paye', $credit->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button class="btn btn-success btn-sm fw-bold"
                                                onclick="return confirm('Confirmer que {{ $client->name }} a payé {{ number_format($credit->montant, 0, ',', ' ') }} FCFA ?')">
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
        <div class="alert alert-info text-center p-5">
            <h4><i class="bi bi-info-circle-fill me-2"></i> Aucun crédit trouvé</h4>
            <p class="fs-5">Ajoutez un crédit pour commencer.</p>
        </div>
    @endif

    <div class="mt-5 text-center">
        <a href="{{ route('boutiquier.clients.index') }}" class="btn btn-outline-secondary btn-lg px-5">
            <i class="bi bi-arrow-left-circle-fill me-2"></i> Retour à la liste
        </a>
    </div>
</div>
@endsection
