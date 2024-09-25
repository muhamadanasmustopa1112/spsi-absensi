<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <title>Absensi</title>
  </head>
  <body>

    <div class="container text-center">
        <div class="d-flex justify-content-center align-items-center vh-100">
            <h3>Status: {{ $status }}</h3>
            <div class="col m-auto">
                <form action="{{ route('check.time') }}" method="POST">
                    @csrf
                    <input type="hidden" name="employee_id" value="{{ old('employee_id', $employee->id ?? '') }}">
                    <div class="form-group">
                        <label for="reason">Reason for being late</label>
                        <textarea id="reason" name="reason" class="form-control" required>{{ old('reason') }}</textarea>
                    </div>
                    <button type="submit" class="btn btn-danger">Submit Late Attendance</button>
                </form>
            </div>
        </div>
    </div>


    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>


  </body>

  
</html>