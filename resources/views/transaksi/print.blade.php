<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Document</title>

    <!-- Custom styles for this template -->
    <link href="{{ url('assets/css/sb-admin-2.min.css') }}" rel="stylesheet">
</head>

<body>



    <div class="title m-b-md">
        <form>
            <div class="form-group">
                <label for="exampleInputEmail1">Email address</label>
                <input type="text" class="form-control email" name="email" id="exampleInputEmail1" placeholder="Email">
            </div>
            <div class="form-group">
                <label for="exampleInputPassword1">Username</label>
                <input type="text" class="form-control username" name="username" id="exampleUsername"
                    placeholder="Username">
            </div>
            <a href="#" class="btn btn-success btne">Print</a>
        </form>
    </div>
    <script src="{{ url('assets/vendor/jquery/jquery.min.js') }}"></script>


    <script>
        $(document).on('click', '.btne', function() {
            console.log('diklikk')


            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                url: "{{ URL('print') }}",

                type: 'post',
                data: {
                    _token: CSRF_TOKEN,
                    email: $('.email').val(),
                    username: $('.username').val(),
                },
                success: function(data) {
                    var responseOutput = JSON.parse(data);
                    console.log('xxxxx' + responseOutput.statusCode)

                    if (responseOutput.statusCode == "true") {
                        alert('Cetak Data Berhasil...');
                    } else {
                        alert('Cetak Data GAGAL...');
                    }
                },
                error: (error) => {
                    console.log(JSON.stringify(error));
                }
            })
        })
    </script>
</body>

</html>
