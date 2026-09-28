@extends('admin.layouts.app')

@section('title', 'Tambah Klien')
@section('parent_label', 'Klien')
@section('parent_url', route('admin.clients.index'))

@section('content')
    @include('admin.clients._form')
@endsection
