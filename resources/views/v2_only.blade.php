@extends('layouts.app')

@section('content1')

    <ol class="item-steps ">
        @foreach ($v2_only as $v2)
            <li style="display: list-item;" class="my-1 list-group-item list-group-item-info">
                <h5>{{ $v2 }}</h5>
            </li>

        @endforeach
    </ol>
@endsection
