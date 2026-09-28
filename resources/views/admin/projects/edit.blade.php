@extends('admin.layouts.app')

@section('title', 'Edit Studi Kasus')
@section('parent_label', 'Studi Kasus')
@section('parent_url', route('admin.projects.index'))

@section('content')
    @include('admin.projects._form', ['project' => $project])
@endsection
