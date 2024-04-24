@extends('layouts.app')

@section('content')
<div class="container py-5 px-5">
    <div class="w-85 mx-auto">
        {{-- question zone --}}
        <h1 class="dark-purple">{{$detail->title}}</h1>
        <div class="category-label w-15 ms-auto text-center mt-1 mb-3">
            <p class="">{{$detail->category->name}}</p>
        </div>
        {{-- user icon and name --}}
        <div class="row">
            <div class="col-1 text-center">
                <a href="{{route('profile')}}">
                    @if ($detail->avatar)
                    <img src="{{$detail->avatar}}" alt="" class="rounded-circle icon-sm">
                    @else
                    <i class="fa-solid fa-circle-user icon-sm text-secondary"></i>
                    @endif
                </a>
            </div>
            <div class="col ms-auto d-flex align-items-center">
                <a href="{{route('profile')}}" class="text-decoration-none">
                    <p class="fs-4 m-0 thick-gray">{{$detail->user->username}}</p>
                </a>
            </div>
            @if ($detail->user_id != Auth::user()->id)
            <div class="col text-end my-auto">
                <button class="btn btn-none purple-gray" type="button" data-bs-toggle="modal" data-bs-target="#report-question-modal"><i class="fa-regular fa-flag"></i> Report this post</button>
            </div>
            @endif
        </div>
        <div class="mt-4 px-2">
            <p class="fs-5">
                {{$detail->content}}
            </p>
        </div>
        {{-- image space --}}
        @if ($detail->image)
        <div class="mt-5 text-center">
            <img src="{{$detail->image}}" alt="" class="w-60">
        </div>
        @endif

        {{-- p.s. posted --}}
        @if ($detail->postscript)
        <div class="container w-85 mt-4">
            <h2 class="second-title">P.S.</h2>
            <div class="my-4 px-2">
                <p class="fs-5">
                    {{$detail->postscript->content}}
                </p>
            </div>
        </div>
        @endif

        {{-- heart and time --}}
        <div class="mt-3 text-end ">
            <form action="" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-none px-0">
                    <i class="fa-regular fa-heart purple-gray"></i>
                </button>
                <span class="purple-gray">1</span>
                <span class="ms-3 purple-gray">{{$detail->created_at->format('m/d/Y H:i:s')}}</span>
            </form>
            @if ($detail->user_id == Auth::user()->id)
            <button type="button" class="trash-btn ms-2" data-bs-toggle="modal" data-bs-target="#delete-question-{{$detail->id}}">
                <i class="fa-solid fa-trash red"></i>
            </button>
            @endif
        </div>

        @if (!$detail->postscript && $detail->user_id == Auth::user()->id)
        {{-- write p.s. --}}
        <form action="{{route('ps.store', $detail->id)}}" method="POST">
            @csrf
            <div class="container w-85 mt-5">
                <h2 class="second-title mb-2">Write Postscript</h2>
                <textarea name="ps_content" rows="10" class="w-100 big-textarea px-2 py-2" placeholder=" Write your postscript in here!"></textarea>
                @error('ps_content')
                    <div class="uni-invalid-feedback text-start" role="alert">
                        <strong>{{ $message }}</strong>
                    </div>
                @enderror
                <button type="button" class="post-btn w-100 py-1 mt-2" data-bs-toggle="modal" data-bs-target="#post-ps">Post</button>
            </div>

            <div class="modal fade" id="post-ps" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header w-100 mx-auto ">
                            <h3 class="modal-title dark-purple" id="exampleModalLongTitle">Post P.S.</h3>
                        </div>
                        <div class="modal-body text-start">
                            <p class="dark-purple">Are you sure you want to post this p.s.?</p>
                        </div>
                        <div class="modal-footer pb-3 border-0 pt-3">
                            <div class="w-100 mx-auto row">
                                <div class="col text-start">
                                    <button type="button" class="cancel py-1 w-50" data-bs-dismiss="modal">Cancel</button>
                                </div>
                                <div class="col text-end">
                                    <button type="submit" class="execute w-50 py-1">Post</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        @endif

    </div>

    {{-- answer area --}}
    @if ($posted_answers->count() > 0)
    <hr class="mt-4 w-85 mx-auto">
    <div class="mt-5" id="">
        <h2 class="second-title w-75 mx-auto">Answers</h2>
        @foreach ($posted_answers as $answer)
        <div class="w-75 mx-auto" id="">
            <div class="row mt-5">
                <div class="col-1 text-center">
                    <a href="{{route('profile')}}">
                        @if($answer->user->avatar)
                        <img src="{{$answer->user->avatar}}" alt="" class="rounded-circle icon-sm">
                        @else
                        <i class="fa-solid fa-circle-user icon-sm text-secondary"></i>
                        @endif
                    </a>
                </div>
                <div class="col ms-auto d-flex align-items-center">
                    <a href="{{route('profile')}}" class="text-decoration-none">
                        <p class="fs-4 m-0 thick-gray">{{$answer->user->username}}</p>
                    </a>
                </div>
                @if ($answer->user_id != Auth::user()->id)
                <div class="col text-end my-auto">
                    <button class="btn btn-none purple-gray" type="button" data-bs-toggle="modal" data-bs-target="#report-answer-{{$answer->id}}"><i class="fa-regular fa-flag"></i> Report this post</button>
                </div>
                @endif
            </div>
            <div class="mt-4 px-2">
                <p class="fs-5">
                    {{$answer->content}}
                </p>
            </div>

            @if ($answer->image)
            <div class="mt-5 text-center">
                <img src="{{$answer->image}}" alt="" class="w-60">
            </div>
            @endif

            <div class="mt-2 text-end ">
                <form action="" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-none px-0">
                        <i class="fa-regular fa-heart purple-gray"></i>
                    </button>
                    <span class="purple-gray">{{$answer->likes->count()}}</span>
                    <span class="ms-3 purple-gray">{{$answer->created_at->format('m/d/Y H:i:s')}}</span>
                </form>
                @if ($answer->user_id == Auth::user()->id)
                <button type="button" class="trash-btn ms-2" data-bs-toggle="modal" data-bs-target="#delete-answer-{{$answer->id}}">
                    <i class="fa-solid fa-trash red"></i>
                </button>
                @endif
            </div>
        </div>

        @if (!$loop->last)
        <hr class="mt-3 w-75 mx-auto">
        @endif

        {{-- report answer popup --}}
        <div class="modal fade" id="report-answer-{{$answer->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true" data-bs-backdrop="static">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header border-0 w-80 mx-auto pb-0 pt-3">
                        <h3 class="modal-title dark-purple" id="exampleModalLongTitle">Report Answer</h3>
                    </div>
                    <div class="modal-body">
                        <form action="" method="POST">
                            @csrf
                            <div class="text-center">
                                <select class="create-q-select px-1 mb-3">
                                    <option selected>Category of Problem</option>
                                    <option value="1">One</option>
                                    <option value="2">Two</option>
                                    <option value="3">Three</option>
                                </select>
                                <textarea name="" id="" rows="10" class="w-80 big-textarea px-3 py-2" placeholder="Please write the reason of reporting this question as detailed as possible."></textarea>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer border-0 pb-3">
                        <div class="w-80 mx-auto row">
                            <div class="col text-end">
                                <button type="button" class="create-q-cancel py-1 w-100" data-bs-dismiss="modal">Close</button>
                            </div>
                            <div class="col">
                                <button type="submit" class="create-q-post-btn w-100 py-1">Post</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- delete answer popup --}}
        <div class="modal fade" id="delete-answer-{{$answer->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header w-100 mx-auto ">
                        <h3 class="modal-title red" id="exampleModalLongTitle">Delete Answer</h3>
                    </div>
                    <form action="">
                        @csrf
                        <div class="modal-body text-start">
                            <p class="red">Are you sure you want to delete this answer?</p>
                        </div>
                        <div class="modal-footer pb-3 border-0 pt-3">
                            <div class="w-100 mx-auto row">
                                <div class="col text-start">
                                    <button type="button" class="delete-team-cancel py-1 w-50" data-bs-dismiss="modal">Cancel</button>
                                </div>
                                <div class="col text-end">
                                    <button type="submit" class="delete-team-post-btn w-50 py-1">Delete</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    <div class="w-100 mt-5">
        {{ $posted_answers->links() }}
    </div>
    @endif

    @if ($detail->user_id != Auth::user()->id)
    {{-- write an answer --}}
    <hr class="mt-4 w-85 mx-auto">
    <form action="{{route('answer.store', $detail->id)}}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="container w-85 mt-4">
            <h2 class="second-title">Write your answer</h2>
            <div class="mt-4">
                <textarea name="post_answer_content" id="" rows="10" class="w-100 big-textarea px-2 py-2" placeholder=" Write your answer in here!">{{ old('post_answer_content') }}</textarea>

                @error('post_answer_content')
                    <div class="uni-invalid-feedback text-start" role="alert">
                        <strong>{{ $message }}</strong>
                    </div>
                @enderror

                <input type="file" class="form-control mt-3" name="post_answer_image">
                <label for="" class="form-text purple-gray">Accepted file types: jpg, jpeg, png, gif, Max file size 1048kb.</label>

                @error('post_answer_image')
                    <div class="uni-invalid-feedback text-start" role="alert">
                        <strong>{{ $message }}</strong>
                    </div>
                @enderror

                <button type="button" class="post-btn w-100 py-1 mt-3" data-bs-toggle="modal" data-bs-target="#post-answer">Post</button>
            </div>
        </div>

        <div class="modal fade" id="post-answer" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header w-100 mx-auto">
                        <h3 class="modal-title dark-purple" id="exampleModalLongTitle">Post Answer</h3>
                    </div>
                    <form action="">
                        @csrf
                        <div class="modal-body text-start">
                            <p class="dark-purple">Are you sure you want to post this answer?</p>
                        </div>
                        <div class="modal-footer pb-3 border-0 pt-3">
                            <div class="w-100 mx-auto row">
                                <div class="col text-start">
                                    <button type="button" class="cancel py-1 w-50" data-bs-dismiss="modal">Cancel</button>
                                </div>
                                <div class="col text-end">
                                    <button type="submit" class="execute w-50 py-1">Post</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </form>
    @endif

    {{-- report question popup --}}
    <form action="{{route('question.report.store', $detail->id)}}" method="POST">
        @csrf
        <div class="modal fade" id="report-question-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true" data-bs-backdrop="static">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header border-0 w-80 mx-auto pb-0 pt-3">
                        <h3 class="modal-title dark-purple" id="exampleModalLongTitle">Report Question</h3>
                    </div>
                    <div class="modal-body">
                        <div class="text-center">
                            <div class="mb-3">
                                <select class="create-q-select px-1" name="q_report_category">
                                    <option disabled selected>Category of Problem</option>
                                    @foreach ($report_categories as $category)
                                    <option value="{{$category->id}}">{{$category->name}}</option>
                                    @endforeach
                                </select>
                                @error('q_report_category')
                                <div class="w-80 mx-auto uni-invalid-feedback text-start" role="alert">
                                    <strong>{{ $message }}</strong>
                                </div>
                                <script type="text/javascript">
                                    $( document ).ready(function() {
                                         $('#report-question-modal').modal('show');
                                    });
                                </script>
                                @enderror
                            </div>

                            <div class="">
                                <textarea name="q_report_content" id="" rows="10" class="w-80 big-textarea px-3 py-2" placeholder="Please write the reason of reporting this team as detailed as possible.">{{ old('report_question_content') }}</textarea>
                                @error('q_report_content')
                                <div class="w-80 mx-auto uni-invalid-feedback text-start" role="alert">
                                    <strong>{{ $message }}</strong>
                                </div>
                                <script type="text/javascript">
                                    $( document ).ready(function() {
                                         $('#report-question-modal').modal('show');
                                    });
                                </script>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0 pb-3">
                        <div class="w-80 mx-auto row">
                            <div class="col text-end">
                                <button type="button" class="create-q-cancel py-1 w-100" data-bs-dismiss="modal">Close</button>
                            </div>
                            <div class="col">
                                <button type="submit" class="create-q-post-btn w-100 py-1">Post</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>


    {{-- delete question modal --}}
    <div class="modal fade" id="delete-question-{{$detail->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header w-100 mx-auto ">
                    <h3 class="modal-title red" id="exampleModalLongTitle">Delete Question</h3>
                </div>
                <form action="">
                    @csrf
                    <div class="modal-body text-start">
                        <p class="red">Are you sure you want to delete this question?</p>
                    </div>
                    <div class="modal-footer pb-3 border-0 pt-3">
                        <div class="w-100 mx-auto row">
                            <div class="col text-start">
                                <button type="button" class="delete-team-cancel py-1 w-50" data-bs-dismiss="modal">Cancel</button>
                            </div>
                            <div class="col text-end">
                                <button type="submit" class="delete-team-post-btn w-50 py-1">Delete</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>
@endsection
