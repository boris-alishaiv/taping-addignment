<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Tapingo</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
</head>

<body>

    <div class="container">


        <div class="col-md-10 col-md-offset-1" style="border: 3px solid black;border-radius: 10px;margin-top: 15px;margin-bottom: 15px;">

            @foreach($twitterItems as $key => $value)
            <div class="media" style="padding-top: 10px">
                <div class="media-left">
                    <a href="#">
                        <img class="media-object" style="height: 100px; width:100px " src="{{ $value['userImage'] }}">
                    </a>
                </div>
                <div class="media-body">
                    <div class="row">
                        <div class="col-md-3">
                            <h4 class="media-heading">{{ $value['userName'] }}</h4>
                        </div>
                        <div class="col-md-3">
                            <h4 class="media-heading">{{ $value['userNickname'] }}</h4>
                            <h4 class="media-heading">{{ $value['id'] }}</h4>
                        </div>
                        <div class="col-md-6">
                            <h4 class="media-heading" style="float: right">{{ $value['createAt'] }}</h4>
                        </div>
                    </div>
                    <br>
                    <p>{{ $value['text'] }}</p>
                </div>
            </div>
            <hr>
            @endforeach
        </div>

        <div class="col-md-4 col-md-offset-5">
            <nav aria-label="Page navigation">
                <ul class="pagination pagination-lg">
                    <li class="page-item">
                        <a class="page-link" href="/pagination?page={{ $prevPage }}&currPageMaxId={{$previousPageMax}}&prevPageMaxId={{ $previousPreviousPageMax }}" aria-label="Previous">
                            <span aria-hidden="true">&laquo;</span>
                            <span class="sr-only">Previous</span>
                        </a>
                    </li>
                    <li class="page-item">
                        <a class="page-link" href="/pagination?page={{ $nextPage }}&currPageMaxId={{$nextPageMax}}&prevPageMaxId={{ $nextPreviousPageMax }}" aria-label="Next">
                            <span aria-hidden="true">&raquo;</span>
                            <span class="sr-only">Next</span>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>

    </div>

</body>
</html>
