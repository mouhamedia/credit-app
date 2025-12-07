@extends('layouts.admin')

@section('title', 'Gestion des Boutiquiers - CréditApp')

@section('content')
<div class="container py-5">

    <div class="d-flex justify-content-between align-items-center mb-5">
        <h1 class="display-5 fw-bold text-primary">Gestion des Boutiquiers</h1>
        <a href="{{ route('admin.boutiquiers.create') }}" class="btn btn-primary btn-lg shadow">
            + Ajouter un boutiquier
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow-lg border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-dark text-white">
                        <tr>
                            <th class="ps-4">ID</th>
                            <th>Nom</th>
                            <th>Email</th>
                            <th>Téléphone</th>
                            <th>Statut</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($boutiquiers as $b)
                            <tr>
                                <td class="ps-4">{{ $b->id }}</td>
                                <td class="fw-bold">{{ $b->name }}</td>
                                <td>{{ $b->email }}</td>
                                <td>{{ $b->phone ?? '-' }}</td>
                                <td>
                                    <span class="badge {{ $b->status ? 'bg-success' : 'bg-danger' }} fs-6 px-3 py-2">
                                        {{ $b->status ? 'ACTIF' : 'BLOQUÉ' }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('admin.boutiquiers.edit', $b->id) }}" class="btn btn-warning btn-sm">
                                        Modifier
                                    </a>
                                    <form action="{{ route('admin.boutiquiers.destroy', $b->id) }}" method="POST" class="d-inline"
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
                                <td colspan="6" class="text-center py-5 text-muted">
                                    <h4>Aucun boutiquier enregistré</h4>
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