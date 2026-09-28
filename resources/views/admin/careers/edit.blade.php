@extends('admin.layouts.app')

@section('title', 'Edit Riwayat Karier')
@section('parent_label', 'Riwayat Karier')
@section('parent_url', route('admin.careers.index'))

@section('content')
    @include('admin.careers._form', ['career' => $career])
@endsection
