@extends('layouts.admin')

@section('title', 'Modifier Boutiquier - CréditApp')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-header bg-warning text-dark text-center py-4 rounded-top-4">
                    <h2 class="mb-0 fw-bold">
                        Modifier {{ $boutiquier->name }}
                    </h2>
                </div>
                <div class="card-body p-5">

                    <form action="{{ route('admin.boutiquiers.update', $boutiquier->id) }}" method="POST">
                        @csrf @method('PUT')

                        <div class="row g-4">
                            <div class="col-md-6">
                                <label class="form-label fw-bold fs-5">Nom complet</label>
                                <input type="text" name="name" value="{{ $boutiquier->name }}" class="form-control form-control-lg" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold fs-5">Email</label>
                                <input type="email" name="email" value="{{ $boutiquier->email }}" class="form-control form-control-lg" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold fs-5">Téléphone</label>
                                <input type="text" name="phone" value="{{ $boutiquier->phone }}" class="form-control form-control-lg">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold fs-5">Statut</label>
                                <select name="status" class="form-select form-select-lg">
                                    <option value="1" {{ $boutiquier->status == 1 ? 'selected' : '' }}>Actif</option>
                                    <option value="0" {{ $boutiquier->status == 0 ? 'selected' : '' }}>Bloqué</option>
                                </select>
                            </div>
                        </div>

                        <div class="text-center mt-5">
                            <button type="submit" class="btn btn-warning btn-lg px-5 fw-bold">
                                Mettre à jour
                            </button>
                            <a href="{{ route('admin.boutiquiers.index') }}" class="btn btn-secondary btn-lg px-5 ms-3">
                                Retour
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection