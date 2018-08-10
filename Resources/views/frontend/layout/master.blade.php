@extends('layouts.master')

@section('meta')
    <meta name="description" content="@if(!empty($category->description)){!!$category->description!!}@endif">
    <!-- Schema.org para Google+ -->
    <meta itemprop="name" content="{{$category->title}}">
    <meta itemprop="description" content="@if(! empty($category->description)){!!$category->description!!} @endif">
    <meta itemprop="image"
          content=" @if(! empty($category->options->mainimage)){{url($category->options->mainimage)}} @endif">

@stop
@section('title')
    {{$category->title}} | @parent
@stop
@section('content')

@stop