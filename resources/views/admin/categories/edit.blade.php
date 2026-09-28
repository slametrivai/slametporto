@extends('admin.layouts.app')

@section('title', 'Edit Kategori')
@section('parent_label', 'Kategori')
@section('parent_url', route('admin.categories.index', ['type' => $category->type]))

@section('content')
    @include('admin.categories._form', ['category' => $category])
@endsection
