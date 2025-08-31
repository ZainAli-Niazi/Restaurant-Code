<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laravel 11 Multi Auth</title>
    <link rel="stylesheet" href="https://unpkg.com/bootstrap@5.3.2/dist/css/bootstrap.min.css">
 
</head>

<body  class="bg-light">
    <section class="p-3 p-md-4 p-xl-5 login-container">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12 col-md-9 col-lg-7 col-xl-6 col-xxl-5">
                    <div class="card border border-light-subtle rounded-4">
                        <div class="card-body p-3 p-md-4 p-xl-5">
                            <div class="row">
                                <div class="col-12">
                                    @if (Session::has('success'))
                                        <div class="alert alert-success">
                                            {{ Session::get('success') }}
                                        </div>
                                    @endif

                                    @if (Session::has('error'))
                                        <div class="alert alert-danger">
                                            {{ Session::get('error') }}
                                        </div>
                                    @endif
                                    <div class="mb-5">
                                        <h4 class="text-center">Login Here</h4>
                                    </div>
                                </div>
                            </div>

                            <!-- Laravel Form Start -->
                            <form method="POST" action="{{ route('admin.authenticate') }}">
                                @csrf
                                <div class="row gy-3 overflow-hidden">

                                    <!-- Username -->
                                    <div class="col-12">
                                        <div class="form-floating mb-3">
                                            <input type="text"
                                                class="form-control @error('username') is-invalid @enderror"
                                                name="username" id="username"
                                                placeholder="Enter username"
                                                value="{{ old('username') }}">
                                            <label for="username" class="form-label">Username</label>
                                            @error('username')
                                                <p class="invalid-feedback">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Password -->
                                    <div class="col-12">
                                        <div class="form-floating mb-3">
                                            <input type="password"
                                                class="form-control @error('password') is-invalid @enderror"
                                                name="password" id="password" placeholder="Password">
                                            <label for="password" class="form-label">Password</label>
                                            @error('password')
                                                <p class="invalid-feedback">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Submit Button -->
                                    <div class="col-12">
                                        <div class="d-grid">
                                            <button class="btn btn-primary py-3" type="submit">Log in now</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                            <!-- Laravel Form End -->

                            <div class="row">
                                <div class="col-12">
                                    <hr class="mt-5 mb-4 border-secondary-subtle">
                                     
                                </div>
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
