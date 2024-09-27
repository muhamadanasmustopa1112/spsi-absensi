<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <title>Absensi</title>
  </head>
  <body>

    <div class="container text-center">
        <div class="d-flex justify-content-center align-items-center vh-100">
            <div class="col m-auto">
                <h3>Hallo {{$items->name}}</h3>
                <p>Silahkan klik absensi</p>
                <a href="#" id="absensiBtn" class="btn btn-primary">Absensi</a>
                <button type="submit" id="scan-fingerprint" class="btn btn-success">Fingerprint</button>

            </div>
        </div>
    </div>


    <!-- Modal HTML -->
    <div class="modal fade" id="terlambatModal" tabindex="-1" aria-labelledby="terlambatModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="terlambatModalLabel">Status: Terlambat</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="col m-auto">
                  <div class="form-group">
                      <label for="reason">Alasan:</label>
                      <textarea id="reason" name="reason" class="form-control" required>{{ old('reason') }}</textarea>
                  </div>
                  <br>
                  <button type="submit" id="terlambatBtn" class="btn btn-danger">Submit Late Attendance</button>
            </div>
          </div>
        </div>
      </div>
    </div>

    @if(session('status'))
        <script>
      
        </script>
    @endif

    <script>

      document.cookie = "screen_resolution=" + window.screen.width + "x" + window.screen.height;

      $(document).ready(function() {
            
          $('#absensiBtn').on('click', function(event) {
              event.preventDefault();

              const employeeId = "{{$items->id}}"; 
    
              if (navigator.geolocation) {
                  navigator.geolocation.getCurrentPosition(function(position) {
                      const latitude = position.coords.latitude;
                      const longitude = position.coords.longitude;

                      // AJAX request to the Laravel function
                      $.ajax({
                          url: '{{route ('insert-absensi')}}',
                          type: 'post',
                          data: {
                              _token: '{{ csrf_token() }}', 
                              employee_id: employeeId,
                              lat: latitude,
                              lng: longitude,
                              alasan: $('#reason').val()
                          },
                          success: function(response) {
                              
                              if(response == 'Terlambat'){
                                $('#terlambatModal').modal('show');
                              }else {
                                console.log(response)
                              }

                          },
                          error: function(xhr, status, error) {
                              console.error('Error:', error);
                              alert('Gagal melakukan absensi.');
                          }
                      });
                  }, function(error) {
                      console.error("Error obtaining location: ", error);
                      alert('Lokasi tidak dapat diambil, harap izinkan akses lokasi.');
                  });
              } else {
                  alert('Geolocation tidak didukung oleh browser Anda.');
              }
          });

          $('#terlambatBtn').on('click', function(event) {
              event.preventDefault();

              const employeeId = "{{$items->id}}"; 
              var alasan = $('#reason').val();

              if (navigator.geolocation) {
                  navigator.geolocation.getCurrentPosition(function(position) {
                      const latitude = position.coords.latitude;
                      const longitude = position.coords.longitude;

                      
                      //AJAX request to the Laravel function

                      $.ajax({
                          url: '{{route ('late-absensi')}}',
                          type: 'post',
                          data: {
                              _token: '{{ csrf_token() }}', 
                              employee_id: employeeId,
                              lat: latitude,
                              lng: longitude,
                              alasan: alasan
                          },
                          success: function(response) {

                            if(response == 'absen-2x'){
                                Swal.fire({
                                    title: "Data Error.!",
                                    text: "Tidak bisa absen 2x",
                                    icon: "error",
                                    confirmButtonText: 'OK'
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        location.reload();
                                    }
                                });
                                
                            }

                            if(response == 'success') {
                                
                                Swal.fire({
                                    title: "Data Saved.!",
                                    text: "Selamat anda berhasil melakukan absen",
                                    icon: "success",
                                    confirmButtonText: 'OK'
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        location.reload();
                                    }
                                });

                            }

                          },
                          error: function(xhr, status, error) {
                              console.error('Error:', error);
                              alert('Gagal melakukan absensi.');
                          }
                      });
                  }, function(error) {
                      console.error("Error obtaining location: ", error);
                      alert('Lokasi tidak dapat diambil, harap izinkan akses lokasi.');
                  });
              } else {
                  alert('Geolocation tidak didukung oleh browser Anda.');
              }
          });



          $('#scan-fingerprint').click(function () {

            const employeeId = "{{$items->id}}"; 

                if (window.PublicKeyCredential) {
                    // Menggunakan WebAuthn API untuk menangkap fingerprint
                    navigator.credentials.create({
                        publicKey: {
                            challenge: new Uint8Array([/* random challenge */]),
                            rp: { name: "Laravel App" }, // Relaying Party info
                            user: {
                                id: new Uint8Array(16), // User ID
                                name: "{{$items->email}}", // Email atau identifier user
                                displayName: "{{$items->name}}"
                            },
                            pubKeyCredParams: [{ alg: -7, type: "public-key" }],
                            authenticatorSelection: {
                                authenticatorAttachment: "platform", // Menggunakan device authenticator
                                userVerification: "required"
                            },
                            timeout: 60000,
                            attestation: "direct"
                        }
                    }).then(function (credential) {
                        // Jika fingerprint berhasil di-scan, kirim ke server Laravel
                        let rawId = new Uint8Array(credential.rawId).toString();
                        let clientDataJSON = new Uint8Array(credential.response.clientDataJSON).toString();
                        let attestationObject = new Uint8Array(credential.response.attestationObject).toString();

     
                        $.ajax({
                            url: '{{route ('fingerprint')}}',
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            data: JSON.stringify({
                                employee_id: employeeId,
                                fingerprint_data: {
                                    rawId: rawId,
                                    clientDataJSON: clientDataJSON,
                                    attestationObject: attestationObject
                                }
                            }),
                            contentType: 'application/json',
                            success: function (response) {
                                if (response.status === 'success') {
                                    alert('Fingerprint matched!');
                                } else {
                                    alert('Fingerprint not recognized.');
                                }
                            },
                            error: function () {
                                alert('Error processing fingerprint.');
                            }
                        });
                    }).catch(function (error) {
                        console.error('Error scanning fingerprint:', error);
                    });
                } else {
                    alert('Your browser does not support WebAuthn.');
                }
            });


      });
      
  </script>


    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>


  </body>

  
</html>