@extends('admin.layouts.app')

@section('title', 'Tambah Sertifikasi')
@section('parent_label', 'Sertifikasi')
@section('parent_url', route('admin.certifications.index'))

@section('content')
    @include('admin.certifications._form')
@endsection
