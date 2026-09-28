@extends('admin.layouts.app')

@section('title', 'Tambah Studi Kasus')
@section('parent_label', 'Studi Kasus')
@section('parent_url', route('admin.projects.index'))

@section('content')
    @include('admin.projects._form')
@endsection
