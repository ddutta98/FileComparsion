@extends('layouts.app')

@section('content1')
    <ol class="item-steps ">
        @foreach ($common_and_same as $same)

            <li style="display: list-item;" class="my-1 list-group-item list-group-item-info">
                <h5>{{ $same }}</h5>
            </li>


        @endforeach
    </ol>
@endsection
