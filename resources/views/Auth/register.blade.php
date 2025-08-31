<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laravel 11 Multi Auth - Register</title>
    <link rel="stylesheet" href="https://unpkg.com/bootstrap@5.3.2/dist/css/bootstrap.min.css">
  </head>
  <body class="bg-light">
    <section class="p-3 p-md-4 p-xl-5">
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-12 col-md-9 col-lg-7 col-xl-6 col-xxl-5">
            <div class="card border border-light-subtle rounded-4">
              <div class="card-body p-3 p-md-4 p-xl-5">
                <h4 class="text-center mb-4">Register Here</h4>

               
                @if ($errors->any())
                  <div class="alert alert-danger">
                    <ul class="mb-0">
                      @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                      @endforeach
                    </ul>
                  </div>
                @endif

                <form method="POST" action="{{ route('admin.store') }}">
                  @csrf
                  <div class="row gy-3 overflow-hidden">

                    <!-- Username -->
                    <div class="col-12">
                      <div class="form-floating mb-3">
                        <input type="text" class="form-control @error('username') is-invalid @enderror"
                          name="username" id="username" placeholder="Username"
                          value="{{ old('username') }}" required>
                        <label for="username">Username</label>
                        @error('username')
                          <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                      </div>
                    </div>

                    <!-- Password -->
                    <div class="col-12">
                      <div class="form-floating mb-3">
                        <input type="password" class="form-control @error('password') is-invalid @enderror"
                          name="password" id="password" placeholder="Password" required>
                        <label for="password">Password</label>
                        @error('password')
                          <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                      </div>
                    </div>

                    <!-- Confirm Password -->
                    <div class="col-12">
                      <div class="form-floating mb-3">
                        <input type="password" class="form-control"
                          name="password_confirmation" id="password_confirmation"
                          placeholder="password_confirmation" required>
                        <label for="password_confirmation">Confirm Password</label>
                      </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="col-12">
                      <div class="d-grid">
                        <button class="btn btn-primary py-3" type="submit">Register Now</button>
                      </div>
                    </div>
                  </div>
                </form>

                <!-- Link to Login -->
                <div class="text-center mt-4">
                  <a href="{{ route('admin.login') }}" class="link-secondary text-decoration-none">Click here to login</a>
                </div>

              </div> <!-- card-body -->
            </div> <!-- card -->
          </div>
        </div>
      </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
  </body>
</html>
