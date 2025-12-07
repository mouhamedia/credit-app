@extends('layouts.admin')

@section('title', 'Gestion des Boutiquiers')

@section('content')
<div class="container">

    <h2 class="mb-4">Liste des Boutiquiers</h2>

    <a href="{{ route('admin.boutiquiers.create') }}" class="btn btn-primary mb-3">
        + Ajouter un Boutiquier
    </a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Email</th>
                <th>Téléphone</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>

        <tbody>
            @foreach($boutiquiers as $b)
                <tr>
                    <td>{{ $b->id }}</td>
                    <td>{{ $b->name }}</td>
                    <td>{{ $b->email }}</td>
                    <td>{{ $b->phone }}</td>

                    <td>
                        @if($b->status == 1)
                            <span class="badge bg-success">Actif</span>
                        @else
                            <span class="badge bg-danger">Bloqué</span>
                        @endif
                    </td>

                    <td>
                        <a href="{{ route('admin.boutiquiers.edit', $b->id) }}" class="btn btn-warning btn-sm">
                            Modifier
                        </a>

                        <form action="{{ route('admin.boutiquiers.destroy', $b->id) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm" onclick="return confirm('Supprimer ce boutiquier ?')">
                                Supprimer
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>

    </table>
</div>
@endsection
