@extends('layouts.main')

@section('content')
    @include('components.breadcrumb')

    <x-ui.card title="Tambah Mata Pelajaran" icon="fas fa-book" class="mt-3">
        <form action="{{ route('dashboard.subjects.store') }}" method="POST">
            @csrf

            <x-forms.input name="code" label="Kode Mata Pelajaran" placeholder="Kode" required />
            <x-forms.input name="name" label="Nama Mata Pelajaran" placeholder="Mata Pelajaran" required />
            <x-forms.textarea name="description" label="Deskripsi (Opsional)" placeholder="Deskripsi" />

            <div class="text-end">
                <a href="{{ route('dashboard.subjects.index') }}" class="btn btn-danger btn-sm">Kembali</a>
                <button type="submit" class="btn btn-primary btn-sm">Submit</button>
            </div>
        </form>
    </x-ui.card>
@endsection