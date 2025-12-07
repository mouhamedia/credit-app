@extends('layouts.admin')

@section('title', 'Dashboard Admin - CréditApp')

@section('content')
<div class="container-fluid py-4">

    <!-- Titre + Bienvenue -->
    <div class="d-flex justify-content-between align-items-center mb-5">
        <div>
            <h1 class="display-5 fw-bold text-primary">
                Bonjour, <span class="text-dark">{{ auth()->user()->name }}</span>
            </h1>
            <p class="text-muted fs-5">Gestion complète des boutiquiers</p>
        </div>
        <div class="text-end">
            <span class="badge bg-success fs-3 px-4 py-3">
                {{ $boutiquiers->count() }} boutiquiers actifs
            </span>
        </div>
    </div>

    <!-- CARD : Créer un boutiquier -->
    <div class="card shadow-lg border-0 mb-5">
        <div class="card-header bg-gradient-primary text-white">
            <h3 class="mb-0">
                <i class="bi bi-person-plus-fill"></i> Ajouter un nouveau boutiquier
            </h3>
        </div>
        <div class="card-body p-5">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <form action="{{ route('admin.boutiquiers.store') }}" method="POST" class="row g-4">
                @csrf
                <div class="col-md-6">
                    <label class="form-label fw-bold">Nom complet</label>
                    <input type="text" name="name" class="form-control form-control-lg" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-bold">Email</label>
                    <input type="email" name="email" class="form-control form-control-lg" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-bold">Mot de passe</label>
                    <input type="password" name="password" class="form-control form-control-lg" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-bold">Téléphone</label>
                    <input type="text" name="phone" class="form-control form-control-lg">
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-primary btn-lg px-5">
                        Créer le boutiquier
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- LISTE DES BOUTIQUIERS -->
    <div class="card shadow-lg border-0">
        <div class="card-header bg-dark text-white">
            <h3 class="mb-0">
                <i class="bi bi-people-fill"></i> Liste des boutiquiers
            </h3>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4">Nom</th>
                            <th>Email</th>
                            <th>Téléphone</th>
                            <th>Statut</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($boutiquiers as $b)
                            <tr>
                                <td class="ps-4 fw-bold">{{ $b->name }}</td>
                                <td>{{ $b->email }}</td>
                                <td>{{ $b->phone ?? '-' }}</td>
                                <td>
                                    @if($b->status)
                                        <span class="badge bg-success fs-6">ACTIF</span>
                                    @else
                                        <span class="badge bg-danger fs-6">INACTIF</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('admin.boutiquiers.edit', $b->id) }}" 
                                       class="btn btn-warning btn-sm">
                                        Modifier
                                    </a>
                                    <form action="{{ route('admin.boutiquiers.destroy', $b->id) }}" 
                                          method="POST" class="d-inline"
                                          onsubmit="return confirm('Supprimer ce boutiquier ?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">
                                            Supprimer
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">
                                    <h4>Aucun boutiquier pour le moment</h4>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection