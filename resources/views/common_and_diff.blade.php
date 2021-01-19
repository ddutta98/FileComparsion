@extends('layouts.app')

@section('content1')

    <ol class="item-steps ">
        @foreach ($common_and_different as $diff)
            <details>
                <summary style="display: list-item;" class="my-1 list-group-item list-group-item-info">
                    <h5>{{ $diff['path'] }}</h5>
                </summary>
                <div id='{{ $diff['id'] }}'>
                    <div class="table-responsive card card-body">
                        <table class="table" width=80%>
                            <tr>
                                <th></th>
                                <th>v1</th>
                                <th>v2</th>
                            </tr>

                            {!! $diff['html'] !!}
                            <tr>
                            </tr>
                        </table>
                    </div>
                </div>
            </details>
        @endforeach
    </ol>
@endsection
