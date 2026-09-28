@extends('admin.layouts.app')

@section('title', 'Tambah Kategori')
@section('parent_label', 'Kategori')
@section('parent_url', route('admin.categories.index'))

@section('content')
    @include('admin.categories._form')
@endsection
