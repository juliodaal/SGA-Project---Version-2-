@extends('layouts.app')

@section('title', 'Disciplinas a inscrever')

@section('content')
@isset($successfully)
    <div class="alert alert-success alert-dismissible fade show rounded border border-success" role="alert">
        <strong>{{ $successfully }}</strong>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endisset
@isset($error)
    <div class="alert alert-warning alert-dismissible fade show rounded border border-warning" role="alert">
        <strong>{{ $error }}</strong>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endisset
@if($disciplines)
<ul class="list-group list-group-flush">
    @foreach($disciplines as $discipline)
        <li class="list-group-item bg-transparent">{{ $discipline['discipline'] }}
            <a class="float-right" href="/home/inscriptions/create?discipline={{$discipline['discipline']}}&number_student={{$discipline['number_student']}}">Inscrever</a>
        </li>
    @endforeach
</ul>
@else
<div class="jumbotron text-center">
    <h1 class="display-4">Não há disciplinas para se inscrever...</h1>
</div>
@endif
@endsection