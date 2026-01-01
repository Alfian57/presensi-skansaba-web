@extends('layouts.main')

@section('content')
    @include('components.breadcrumb')

    <div class="row justify-content-center">
        <div class="col-lg-6">
            <x-ui.card title="Tambah Mata Pelajaran" icon="fas fa-book">
                <form action="{{ route('dashboard.subjects.store') }}" method="POST">
                    @csrf

                    <x-forms.input name="code" label="Kode Mata Pelajaran" placeholder="Kode unik mata pelajaran" required />
                    <x-forms.input name="name" label="Nama Mata Pelajaran" placeholder="Nama mata pelajaran" required />
                    <x-forms.textarea name="description" label="Deskripsi (Opsional)" placeholder="Deskripsi singkat mata pelajaran" />

                    <x-forms.actions :backRoute="route('dashboard.subjects.index')" submitLabel="Simpan Data" />
                </form>
            </x-ui.card>
        </div>
    </div>
@endsection