<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Bootstrap demo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

  </head>
  <body>
    <div class="container">
        <div class="row justify-content-center m-3">
            <div class="col-8">
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
            
            @foreach ($items as $item)
                <p>{{$item->qr}}</p>
                <img src="{{asset('storage/' . $item->qr)}} " alt="">
            @endforeach

                <form action="{{route('employee.store')}}" method="post">
                  @csrf
                    <div class="mb-3">
                        <label for="exampleInputEmail1" class="form-label">Email address</label>
                        <input type="email" name="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
                    </div>
                    <div class="mb-3">
                        <label for="exampleInputPassword1" class="form-label">Nama</label>
                        <input type="text" name="name" class="form-control" id="exampleInputPassword1">
                    </div>
                    <div class="mb-3">
                        <label for="exampleInputPassword1" class="form-label">No Hp</label>
                        <input type="text" name="no_hp" class="form-control" id="exampleInputPassword1">
                    </div>
                    <div class="mb-3">
                        <label for="exampleInputPassword1" class="form-label">Alamat</label>
                        <textarea class="form-control" name="address" placeholder="Leave a comment here" id="floatingTextarea" rows="5"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
  </body>

  <!-- <script>
    navigator.geolocation.getCurrentPosition(
        function(position) {
            let latitude = position.coords.latitude;
            let longitude = position.coords.longitude;
            let accuracy = position.coords.accuracy;

            console.log("Latitude: " + latitude);
            console.log("Longitude: " + longitude);
            console.log("Accuracy: " + accuracy + " meters");

            axios.post('{{route("address")}}', {
                latitude: latitude,
                longitude: longitude,
                accuracy: accuracy
            }, {
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(function (response) {
                console.log('Success:', response.data);
            })
            .catch(function (error) {
                console.error('Error:', error);
            });

            // // Send data to Laravel API using Axios
            // axios.post('{{route("address")}}', {
            //     latitude: latitude,
            //     longitude: longitude,
            //     accuracy: accuracy
            // })
            // .then(function (response) {
            //     console.log('Success:', response.data);
            // })
            // .catch(function (error) {
            //     console.error('Error:', error);
            // });
        },
        function(error) {
            console.error("Error occurred. Error code: " + error.code);
        },
        {
            enableHighAccuracy: true,
            timeout: 10000,
            maximumAge: 0
        }
    );
  </script> -->
</html>