@extends('layouts.admin')

@section('title', 'Créer un Boutiquier - CréditApp')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-header bg-primary text-white text-center py-4 rounded-top-4">
                    <h2 class="mb-0 fw-bold">
                        Ajouter un nouveau boutiquier
                    </h2>
                </div>
                <div class="card-body p-5">

                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form action="{{ route('admin.boutiquiers.store') }}" method="POST">
                        @csrf

                        <div class="row g-4">
                            <div class="col-md-6">
                                <label class="form-label fw-bold fs-5">Nom complet</label>
                                <input type="text" name="name" class="form-control form-control-lg" required autofocus>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold fs-5">Email</label>
                                <input type="email" name="email" class="form-control form-control-lg" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold fs-5">Mot de passe</label>
                                <input type="password" name="password" class="form-control form-control-lg" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold fs-5">Téléphone</label>
                                <input type="text" name="phone" class="form-control form-control-lg">
                            </div>
                        </div>

                        <div class="text-center mt-5">
                            <button type="submit" class="btn btn-primary btn-lg px-5 fw-bold">
                                Créer le boutiquier
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