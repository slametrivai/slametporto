@extends('admin.layouts.app')

@section('title', 'Edit Artikel')
@section('parent_label', 'Artikel Blog')
@section('parent_url', route('admin.posts.index'))

@section('content')
    @include('admin.posts._form', ['post' => $post])
@endsection
