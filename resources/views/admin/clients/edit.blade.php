@extends('admin.layouts.app')

@section('title', 'Edit Klien')
@section('parent_label', 'Klien')
@section('parent_url', route('admin.clients.index'))

@section('content')
    @include('admin.clients._form', ['client' => $client])
@endsection
