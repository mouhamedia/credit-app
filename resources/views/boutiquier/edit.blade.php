@extends('layouts.admin')

@section('title', 'Modifier Boutiquier')

@section('content')
<div class="container">

    <h2 class="mb-4">Modifier Boutiquier</h2>

    <form action="{{ route('admin.boutiquiers.update', $boutiquier->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label>Nom</label>
            <input type="text" name="name" class="form-control" value="{{ $boutiquier->name }}" required>
        </div>

        <div class="mb-3">
            <label>E-mail</label>
            <input type="email" name="email" class="form-control" value="{{ $boutiquier->email }}" required>
        </div>

        <div class="mb-3">
            <label>Téléphone</label>
            <input type="text" name="phone" class="form-control" value="{{ $boutiquier->phone }}">
        </div>

        <div class="mb-3">
            <label>Status</label>
            <select name="status" class="form-control">
                <option value="1" @if($boutiquier->status==1) selected @endif>Actif</option>
                <option value="0" @if($boutiquier->status==0) selected @endif>Bloqué</option>
            </select>
        </div>

        <button class="btn btn-warning">Mettre à jour</button>
    </form>

</div>
@endsection
