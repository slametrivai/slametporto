@extends('admin.layouts.app')

@section('title', 'Tulis Artikel')
@section('parent_label', 'Artikel Blog')
@section('parent_url', route('admin.posts.index'))

@section('content')
    @include('admin.posts._form')
@endsection
