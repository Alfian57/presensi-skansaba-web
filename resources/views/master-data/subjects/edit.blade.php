@extends('layouts.main')

@section('content')
    @include('components.breadcrumb')

    <div class="row justify-content-center">
        <div class="col-lg-6">
            <x-ui.card title="Ubah Data Mata Pelajaran" icon="fas fa-book">
                <form action="{{ route('dashboard.subjects.update', $subject->slug) }}" method="POST">
                    @method('put')
                    @csrf

                    <x-forms.input name="code" label="Kode Mata Pelajaran" placeholder="Kode unik mata pelajaran" :value="$subject->code" required />
                    <x-forms.input name="name" label="Nama Mata Pelajaran" placeholder="Nama mata pelajaran" :value="$subject->name" required />
                    <x-forms.textarea name="description" label="Deskripsi (Opsional)" placeholder="Deskripsi singkat mata pelajaran" :value="$subject->description" />

                    <x-forms.actions :backRoute="route('dashboard.subjects.index')" submitLabel="Perbarui Data" />
                </form>
            </x-ui.card>
        </div>
    </div>
@endsection