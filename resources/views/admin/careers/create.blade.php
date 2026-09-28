@extends('admin.layouts.app')

@section('title', 'Tambah Riwayat Karier')
@section('parent_label', 'Riwayat Karier')
@section('parent_url', route('admin.careers.index'))

@section('content')
    @include('admin.careers._form')
@endsection
