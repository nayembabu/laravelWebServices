<!DOCTYPE html>
<html lang="bn">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>রেজিস্টার - সার্ভিস বাজার</title>
  
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
    
    .register-container {
      max-width: 460px;
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
  </style>
</head>
<body>

  <div class="register-container">
    
    <div class="text-center mb-4">
      <h2 class="fw-bold text-success">সার্ভিস বাজার</h2>
      <p class="text-muted">নতুন অ্যাকাউন্ট তৈরি করুন</p>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            {{ $errors->first() }}
        </div>
    @endif

    <div class="card">
      <div class="card-header">
        <h4 class="mb-0">রেজিস্টার করুন</h4>
      </div>
      
      <div class="card-body p-4 p-md-5">
        
        <form action="{{ route('register.submit') }}" method="post">
          @csrf
          <div class="row g-3">
            <div class="col-md-12">
              <label for="name" class="form-label fw-medium">পুরো নাম</label>
              <input type="text" class="form-control form-control-lg" name="name" id="name" placeholder="আপনার নাম" required>
            </div>
          </div>

          <div class="mb-3 mt-3">
            <label for="email" class="form-label fw-medium">ইমেইল</label>
            <div class="input-group input-group-lg">
              <span class="input-group-text"><i class="bi bi-envelope"></i></span>
              <input type="email" class="form-control" name="email" id="email" placeholder="আপনার ইমেইল" required>
            </div>
          </div>

          <div class="mb-3">
            <label for="username" class="form-label fw-medium">ইউজারনেম</label>
            <div class="input-group input-group-lg">
              <span class="input-group-text">@</span>
              <input type="text" class="form-control" name="username" id="username" placeholder="আপনার ইউজারনেম" required>
            </div>
          </div>
          <div class="mb-3">
            <label for="phone" class="form-label fw-medium">ফোন নম্বর</label>
            <div class="input-group input-group-lg">
              <span class="input-group-text"><i class="bi bi-phone"></i></span>
              <input type="text" class="form-control" name="phone" id="phone" placeholder="আপনার ফোন নম্বর" required>
            </div>
          </div>

          <div class="mb-3">
            <label for="password" class="form-label fw-medium">পাসওয়ার্ড</label>
            <div class="input-group input-group-lg">
              <span class="input-group-text"><i class="bi bi-lock"></i></span>
              <input type="password" class="form-control" name="password" id="password" placeholder="কমপক্ষে ৮ অক্ষর" required>
            </div>
          </div>

          <div class="mb-4">
            <label for="confirmPassword" class="form-label fw-medium">পাসওয়ার্ড নিশ্চিত করুন</label>
            <div class="input-group input-group-lg">
              <span class="input-group-text"><i class="bi bi-lock"></i></span>
              <input type="password" class="form-control" name="password_confirmation" id="confirmPassword" placeholder="আবার লিখুন" required>
            </div>
          </div>

          <input type="hidden" name="referral_code" value="{{ request('ref') }}">

          <div class="form-check mb-4">
            <input class="form-check-input" type="checkbox" name="terms" id="terms" required>
            <label class="form-check-label" for="terms">
              আমি <a href="#">শর্তাবলী</a> ও <a href="#">প্রাইভেসি পলিসি</a> মেনে নিচ্ছি
            </label>
          </div>

          <button type="submit" class="btn btn-primary btn-lg w-100 mb-4">
            অ্যাকাউন্ট তৈরি করুন
          </button>

          <div class="text-center">
            <p class="mb-0">ইতিমধ্যে অ্যাকাউন্ট আছে? 
              <a href="{{ route('login') }}" class="fw-medium">লগইন করুন</a>
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