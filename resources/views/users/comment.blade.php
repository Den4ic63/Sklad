@extends('layouts.app')

@section('content')
    <div class="row mb-4">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>User Comments</h2>
            </div>
            <form action="/takecomment" method="POST">
                @csrf
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <strong>Comment</strong>
                            <input type="text" name="comment" class="form-control" placeholder="Leave a comment">
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group w-100 d-flex flex-column justify-content-center align-items-center">
                            <strong class="mt-2 mb-2">Rating</strong>
                            <div class="rating_area mb-4">
                                <input type="radio" id="star-5" name="rating" value="5">
                                <label for="star-5" title="Оценка «5»"></label>
                                <input type="radio" id="star-4" name="rating" value="4">
                                <label for="star-4" title="Оценка «4»"></label>
                                <input type="radio" id="star-3" name="rating" value="3">
                                <label for="star-3" title="Оценка «3»"></label>
                                <input type="radio" id="star-2" name="rating" value="2">
                                <label for="star-2" title="Оценка «2»"></label>
                                <input type="radio" id="star-1" name="rating" value="1">
                                <label for="star-1" title="Оценка «1»"></label>
                            </div>

                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                        <button type="submit" class="btn btn-primary">Оставить комент</button>
                    </div>
                </div>

            </form>
        </div>
    </div>

    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif

    <table class="table table-bordered">
        <tr>
            <th>№</th>
            <th>Email</th>
            <th>Comment</th>
            <th>Rating</th>
        </tr>
        @foreach ($data as $key => $comments)
            <tr>
                <td>{{ ++$i }}</td>
                <td>{{ $comments->email }}</td>
                <td>{{ $comments->comment }}</td>
                <td>{{$comments->rating}}</td>
            </tr>
        @endforeach
    </table>

    <style>
        .rating_area {
            overflow: hidden;
            width: auto;
            float:left;
        }

        .rating_area:not(:checked) > input {
            display: none;
        }
        .rating_area:not(:checked) > label {
            float: right;
            width: 35px;
            padding: 0;
            cursor: pointer;
            font-size: 32px;
            line-height: 32px;
            color: #F0F0F0;
        }
        .rating_area:not(:checked) > label:before {
            content: '★';
        }
        .rating_area > input:checked ~ label {
            color: gold;
        }
        .rating_area:not(:checked) > label:hover,
        .rating_area:not(:checked) > label:hover ~ label {
            color: gold;
        }
        .rating_area > input:checked + label:hover,
        .rating_area > input:checked + label:hover ~ label,
        .rating_area > input:checked ~ label:hover,
        .rating_area > input:checked ~ label:hover ~ label,
        .rating_area > label:hover ~ input:checked ~ label {
            color: gold;
        }
        .rating_area > label:active {
            position: relative;
        }

    </style>

@endsection
