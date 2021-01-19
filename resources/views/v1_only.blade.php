@extends('layouts.app')

@section('content1')
    <ol class="item-steps ">
        @foreach ($v1_only as $v1)
            <li style="display: list-item;" class="my-1 list-group-item list-group-item-info">
                <h5>{{ $v1 }}</h5>
            </li>
        @endforeach
    </ol>
@endsection
