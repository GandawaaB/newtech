@extends('layouts.app')

@section('body')
<!-- Page Content -->
<div class="container">

    <div class="row">

        <!-- Post Content Column -->
        <div class="col-lg-8">

            <!-- Title -->
            <h1 class="mt-4">{{ $post->title }}</h1>

             <!-- Category -->
             <p>
                <a href="{{ $post->category->path() }}">
                    <span class="badge badge-pill badge-primary">{{ $post->category->name }}</span>
                </a>
            </p>

            <!-- Author -->
            {{-- <p class="lead">
                <a href="#">{{ $post->user->name }}</a>
            </p> --}}
            
            <hr>

            <!-- Date/Time -->
            <p>Нийтэлсэн {{ $post->created_at->toDayDateTimeString() }}</p>

            <hr>
            <img src ="{{asset('images/'. $post->image)}}" class="img-fluid" />

            <!-- Post Content -->
            <p class="lead">{{ $post->body }}</p>

            <hr>

            <!-- Comments Form -->
            {{-- @auth @include('partials.comment-form') @endauth --}}
            @include('partials.comment-form')
            <h3>Сэтгэгдлүүд</h3>

            <hr>

            @if($post->comments->isNotEmpty()) 

                @foreach ($post->comments as $comment)

                    @include('partials.comment')

                @endforeach

            @endif

        </div>

        @include('includes.sidebar')

    </div>
    <!-- /.row -->

</div>
<!-- /.container -->
@endsection
