<!DOCTYPE html>
<html lang="bn">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>লগইন - সার্ভিস বাজার</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

  <style>
    :root {
      --primary: #00d084;
      --primary-dark: #00a368;
      --dark: #0f172a;
      --light-bg: #f8fafc;
    }

    body {
      background: linear-gradient(135deg, #f0fdfa 0%, #f8fafc 100%);
      min-height: 100vh;
      display: flex;
      align-items: center;
      font-family: 'Segoe UI', system-ui, sans-serif;
    }

    .login-container {
      max-width: 420px;
      width: 100%;
      margin: 2rem auto;
    }

    .card {
      border: none;
      border-radius: 16px;
      box-shadow: 0 10px 40px rgba(0,0,0,0.08);
      overflow: hidden;
    }

    .card-header {
      background: var(--primary);
      color: white;
      text-align: center;
      padding: 2.5rem 1.5rem 1.5rem;
      border-bottom: none;
    }

    .btn-primary {
      background-color: var(--primary);
      border-color: var(--primary);
      padding: 0.75rem 1.5rem;
      font-weight: 500;
    }

    .btn-primary:hover {
      background-color: var(--primary-dark);
      border-color: var(--primary-dark);
    }

    .form-control:focus {
      border-color: var(--primary);
      box-shadow: 0 0 0 0.25rem rgba(0,208,132,0.25);
    }

    a {
      color: var(--primary);
      text-decoration: none;
    }

    a:hover {
      text-decoration: underline;
    }
    .login-error-alert {
        border-left: 5px solid #dc3545;
        font-weight: 500;
    }

  </style>
</head>
<body>

  <div class="login-container">

    <div class="text-center mb-4">
      <h2 class="fw-bold text-success">সার্ভিস বাজার</h2>
      <p class="text-muted">আপনার অ্যাকাউন্টে লগইন করুন</p>
    </div>
    @if($errors->has('login'))
        <div class="alert alert-danger login-error-alert">
            {{ $errors->first('login') }}
        </div>
    @endif

    <div class="card">
      <div class="card-header">
        <h4 class="mb-0">স্বাগতম!</h4>
      </div>

      <div class="card-body p-4 p-md-5">

        <form action="{{ route('login.submit') }}" method="post">
          @csrf
          <div class="mb-4">
            <label for="login" class="form-label fw-medium">ইমেইল বা ইউজারনেম</label>
            <div class="input-group input-group-lg">
              <span class="input-group-text"><i class="bi bi-envelope"></i></span>
              <input type="text" class="form-control" name="login" id="login" placeholder="আপনার ইমেইল বা ইউজারনেম" value="{{ old('login') }}" required>
            </div>
          </div>

          <div class="mb-4">
            <div class="d-flex justify-content-between">
              <label for="password" class="form-label fw-medium">পাসওয়ার্ড</label>
            </div>
            <div class="input-group input-group-lg">
              <span class="input-group-text"><i class="bi bi-lock"></i></span>
              <input type="password" class="form-control" name="password" id="password" placeholder="••••••••" value="{{ old('password') }}" required>
            </div>
          </div>

          <div class="form-check mb-4">
            <input class="form-check-input" type="checkbox" name="remember" id="remember">
            <label class="form-check-label" for="remember">
              আমাকে মনে রাখুন
            </label>
          </div>

          <button type="submit" class="btn btn-primary btn-lg w-100 mb-4">
            লগইন করুন
          </button>

          <div class="text-center">
            <p class="mb-0">এখনো অ্যাকাউন্ট নেই?
              <a href="{{ route('register') }}" class="fw-medium">রেজিস্টার করুন</a>
            </p>
          </div>
        </form>

      </div>
    </div>

    <div class="text-center mt-4">
      <small class="text-muted">© ২০২৬ সার্ভিস বাজার</small>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
