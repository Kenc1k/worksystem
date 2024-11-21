<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login Page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet" integrity="sha384-k6RqeWeci5ZR/Lv4MR0sA0FfDOMlU6CpYoAOlK8RFZjfT6KtD87lXwq0Rf5yX6pj" crossorigin="anonymous">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.3.1/mdb.min.css" rel="stylesheet">
</head>
<body>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-4">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{route('login')}}" method="POST">
                    <h3 class="text-center mb-4">Login</h3>
                    @csrf
                    <div class="form-outline mb-4">
                      <input type="email" id="form2Example1" name="email" class="form-control" required />
                      <label class="form-label" for="form2Example1">Email address</label>
                    </div>
                  
                    <div class="form-outline mb-4">
                      <input type="password" id="form2Example2" name="password" class="form-control" required />
                      <label class="form-label" for="form2Example2">Password</label>
                    </div>
                  
                    <div class="d-flex justify-content-between align-items-center mb-4">
                      <div class="form-check">
                          <input class="form-check-input" type="checkbox" id="rememberMe" name="remember">
                          <label class="form-check-label" for="rememberMe">Remember me</label>
                      </div>
                      <a href="#!">Forgot password?</a>
                    </div>
                  
                    <button type="submit" class="btn btn-primary btn-block mb-4">Sign in</button>
                  
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.3.1/mdb.min.js"></script>
</body>
</html>
