<!DOCTYPE html>
<html lang="bn">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
        <title>সার্ভিস বাজার - সব ডিজিটাল সেবা এক জায়গায়</title>
        
        <!-- Bootstrap 5 CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        
        <!-- Bootstrap Icons -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

        <style>
            :root {
            --primary: #00d084;
            --primary-dark: #00a368;
            --dark: #0f172a;
            --light-bg: #f8fafc;
            }
            
            body {
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            color: #1e293b;
            background: var(--light-bg);
            }
            
            .navbar {
            background: white !important;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            }
            
            .hero {
            background: linear-gradient(135deg, #00d084 0%, #00a368 100%);
            color: white;
            padding: 160px 0 120px;
            }
            
            .search-box {
            max-width: 720px;
            margin: 2.5rem auto 1rem;
            }
            
            .search-box .form-control {
            border-radius: 50px 0 0 50px;
            padding: 1rem 1.5rem;
            font-size: 1.1rem;
            border: none;
            }
            
            .search-box .btn {
            border-radius: 0 50px 50px 0;
            padding: 0 2.5rem;
            font-size: 1.1rem;
            background: var(--dark);
            border: none;
            }
            
            .category-card {
            transition: all 0.3s ease;
            border: none;
            border-radius: 16px;
            background: white;
            box-shadow: 0 8px 25px rgba(0,0,0,0.06);
            text-align: center;
            padding: 2rem 1.5rem;
            }
            
            .category-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 35px rgba(0,208,132,0.25);
            }
            
            .category-icon {
            font-size: 2.8rem;
            color: var(--primary);
            margin-bottom: 1rem;
            }
            
            .popular-gig {
            border-radius: 16px;
            overflow: hidden;
            background: white;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
            transition: all 0.3s;
            }
            
            .popular-gig:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0,208,132,0.2);
            }
            
            footer {
            background: var(--dark);
            color: #cbd5e1;
            }
        </style>
    </head>
    <body>

        <!-- Navbar -->
        <nav class="navbar navbar-expand-lg fixed-top">
        <div class="container">
            <a class="navbar-brand fw-bold fs-3 text-success" href="#">সার্ভিস বাজার</a>
            
            <div class="navbar-nav ms-auto align-items-center d-block d-lg-none">
                <a href="{{ route('login') }}" class="btn btn-outline-success px-4">লগইন</a>
            </div>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item"><a class="nav-link px-3" href="#">ক্যাটাগরি</a></li>
                    <li class="nav-item"><a class="nav-link px-3" href="#">কীভাবে কাজ করে</a></li>
                    <li class="nav-item"><a class="nav-link px-3" href="#">সেলার হোন</a></li>
                    <li class="nav-item ms-3">
                        <a href="{{ route('login') }}" class="btn btn-outline-success px-4">লগইন</a>
                    </li>
                    <li class="nav-item ms-2">
                        <a href="{{ route('register') }}" class="btn btn-success px-4">সাইন আপ</a>
                    </li>
                </ul>
            </div>
        </div>
        </nav>

        <!-- Hero Section -->
        <section class="hero text-center">
            <div class="container">
                <h1 class="display-4 fw-bold mb-4">আপনার যেকোনো ডিজিটাল কাজ <br>মিনিটেই খুঁজে পান</h1>
                
                <p class="lead fs-4 mb-5 opacity-90" style="max-width: 780px; margin-left: auto; margin-right: auto;">
                    লোগো ডিজাইন • ওয়েবসাইট • ভিডিও এডিটিং • SEO • কনটেন্ট রাইটিং • অ্যাপ ডেভেলপমেন্ট • গ্রাফিক্স • মার্কেটিং — সব এক জায়গায়
                </p>

                <!-- Search Bar -->
                <div class="search-box">
                    <div class="input-group input-group-lg">
                        <input type="text" class="form-control" placeholder="আপনি কী সার্ভিস খুঁজছেন? (যেমন: লোগো ডিজাইন, ওয়েবসাইট)" aria-label="Search services">
                        <button class="btn px-5" type="button">খুঁজুন</button>
                    </div>
                </div>

                <div class="mt-4">
                    <small class="opacity-90">জনপ্রিয়: </small>
                    <a href="#" class="badge bg-white text-dark text-decoration-none px-3 py-2 m-1">লোগো ডিজাইন</a>
                    <a href="#" class="badge bg-white text-dark text-decoration-none px-3 py-2 m-1">ওয়েব ডেভেলপমেন্ট</a>
                    <a href="#" class="badge bg-white text-dark text-decoration-none px-3 py-2 m-1">ভিডিও এডিটিং</a>
                    <a href="#" class="badge bg-white text-dark text-decoration-none px-3 py-2 m-1">SEO</a>
                    <a href="#" class="badge bg-white text-dark text-decoration-none px-3 py-2 m-1">কনটেন্ট রাইটিং</a>
                </div>
            </div>
        </section>

        <!-- Categories -->
        <section class="py-5">
            <div class="container py-4">
                <h2 class="text-center fw-bold display-6 mb-5">জনপ্রিয় ক্যাটাগরি</h2>

                <div class="row g-4">
                    <div class="col-lg-2 col-md-4 col-6">
                        <div class="category-card">
                        <div class="category-icon"><i class="bi bi-pencil-square"></i></div>
                        <h5 class="fw-bold">গ্রাফিক্স ডিজাইন</h5>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-4 col-6">
                        <div class="category-card">
                        <div class="category-icon"><i class="bi bi-code-slash"></i></div>
                        <h5 class="fw-bold">প্রোগ্রামিং ও টেক</h5>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-4 col-6">
                        <div class="category-card">
                        <div class="category-icon"><i class="bi bi-film"></i></div>
                        <h5 class="fw-bold">ভিডিও ও অ্যানিমেশন</h5>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-4 col-6">
                        <div class="category-card">
                        <div class="category-icon"><i class="bi bi-bar-chart-line"></i></div>
                        <h5 class="fw-bold">ডিজিটাল মার্কেটিং</h5>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-4 col-6">
                        <div class="category-card">
                        <div class="category-icon"><i class="bi bi-pencil"></i></div>
                        <h5 class="fw-bold">রাইটিং ও ট্রান্সলেশন</h5>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-4 col-6">
                        <div class="category-card">
                        <div class="category-icon"><i class="bi bi-music-note-beamed"></i></div>
                        <h5 class="fw-bold">মিউজিক ও অডিও</h5>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Popular Gigs / Services -->
        <section class="bg-white py-5">
            <div class="container py-4">
                <h2 class="text-center fw-bold display-6 mb-5">জনপ্রিয় সার্ভিসসমূহ</h2>

                <div class="row g-4">
                    <div class="col-lg-3 col-md-6">
                        <div class="popular-gig">
                            <img src="https://images.unsplash.com/photo-1626785774573-4b7b30bb2b6d?w=500" class="card-img-top" alt="Logo Design">
                            <div class="p-3">
                                <h5 class="fw-bold mb-2">প্রফেশনাল লোগো ডিজাইন</h5>
                                <p class="text-muted small mb-2">৫ রিভিশন + সোর্স ফাইল</p>
                                <div class="d-flex justify-content-between align-items-center">
                                <span class="fw-bold text-success">৳১৫০০ থেকে শুরু</span>
                                <small class="text-warning"><i class="bi bi-star-fill"></i> 4.9 (১২৩)</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6">
                        <div class="popular-gig">
                            <img src="https://images.unsplash.com/photo-1498050108023-c5249f4df085?w=500" class="card-img-top" alt="Website">
                            <div class="p-3">
                                <h5 class="fw-bold mb-2">রেসপন্সিভ ওয়েবসাইট</h5>
                                <p class="text-muted small mb-2">৫ পেজ + SEO বেসিক</p>
                                <div class="d-flex justify-content-between align-items-center">
                                <span class="fw-bold text-success">৳৮০০০ থেকে শুরু</span>
                                <small class="text-warning"><i class="bi bi-star-fill"></i> 4.8 (৮৭)</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6">
                        <div class="popular-gig">
                            <img src="https://images.unsplash.com/photo-1557426272-fc91fdb8f385?w=500" class="card-img-top" alt="Video Edit">
                            <div class="p-3">
                                <h5 class="fw-bold mb-2">ইউটিউব ভিডিও এডিটিং</h5>
                                <p class="text-muted small mb-2">মোশন গ্রাফিক্স + কালার গ্রেড</p>
                                <div class="d-flex justify-content-between align-items-center">
                                <span class="fw-bold text-success">৳২৫০০ থেকে শুরু</span>
                                <small class="text-warning"><i class="bi bi-star-fill"></i> 4.9 (২১৪)</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6">
                        <div class="popular-gig">
                            <img src="https://images.unsplash.com/photo-1551434678-e076c223a692?w=500" class="card-img-top" alt="SEO">
                            <div class="p-3">
                                <h5 class="fw-bold mb-2">SEO + গুগল র‍্যাঙ্কিং</h5>
                                <p class="text-muted small mb-2">কীওয়ার্ড রিসার্চ + অন-পেজ</p>
                                <div class="d-flex justify-content-between align-items-center">
                                <span class="fw-bold text-success">৳৫০০০ থেকে শুরু</span>
                                <small class="text-warning"><i class="bi bi-star-fill"></i> 4.7 (১৫৬)</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Become a Seller CTA -->
        <section class="bg-success text-white text-center py-5 my-5">
            <div class="container py-5">
                <h2 class="display-5 fw-bold mb-4">আপনিও কি সার্ভিস বিক্রি করতে চান?</h2>
                <p class="lead fs-4 mb-5">হাজার হাজার ক্রেতার কাছে পৌঁছে যান — আজই সেলার অ্যাকাউন্ট খুলুন</p>
                <a href="#" class="btn btn-light btn-lg px-5 py-3 fw-bold">সেলার হোন →</a>
            </div>
        </section>

        <!-- Footer -->
        <footer class="py-5">
            <div class="container">
                <div class="row">
                    <div class="col-lg-4 mb-4">
                        <h4 class="fw-bold text-white mb-4">সার্ভিস বাজার</h4>
                        <p>বাংলাদেশের সবচেয়ে বড় ডিজিটাল সার্ভিস মার্কেটপ্লেস</p>
                    </div>

                    <div class="col-lg-2 col-6 mb-4">
                        <h5 class="text-white mb-3">কুইক লিঙ্ক</h5>
                        <ul class="list-unstyled">
                            <li><a href="#" class="text-reset text-decoration-none">হোম</a></li>
                            <li><a href="#" class="text-reset text-decoration-none">ক্যাটাগরি</a></li>
                            <li><a href="#" class="text-reset text-decoration-none">কীভাবে কাজ করে</a></li>
                            <li><a href="#" class="text-reset text-decoration-none">সাপোর্ট</a></li>
                        </ul>
                    </div>

                    <div class="col-lg-3 col-6 mb-4">
                        <h5 class="text-white mb-3">সার্ভিস</h5>
                        <ul class="list-unstyled">
                            <li><a href="#" class="text-reset text-decoration-none">গ্রাফিক্স ডিজাইন</a></li>
                            <li><a href="#" class="text-reset text-decoration-none">ওয়েব ডেভেলপমেন্ট</a></li>
                            <li><a href="#" class="text-reset text-decoration-none">ডিজিটাল মার্কেটিং</a></li>
                            <li><a href="#" class="text-reset text-decoration-none">ভিডিও এডিটিং</a></li>
                        </ul>
                    </div>

                    <div class="col-lg-3">
                        <h5 class="text-white mb-3">যোগাযোগ</h5>
                        <ul class="list-unstyled">
                            <li class="mb-2"><i class="bi bi-envelope me-2"></i> support@servicebazar.com</li>
                            <li><i class="bi bi-telephone me-2"></i> +880 1700 000000</li>
                        </ul>
                    </div>
                </div>

                <hr class="my-4 opacity-25">

                <div class="text-center">
                    <small>© ২০২৬ সার্ভিস বাজার — সর্বস্বত্ব সংরক্ষিত</small>
                </div>
            </div>
        </footer>

        <!-- Bootstrap JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    </body>
</html>

