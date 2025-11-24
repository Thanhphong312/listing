<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <link rel="shortcut icon" href="https://pressify.us/assets/img/logo.png" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        crossorigin="anonymous"></script>
    <script src="{{ asset('assets/css/fontawesome_pressify.js')}}"></script>
    <link href="{{ asset('assets/css/bootstrap.css')}}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/style_pressify.css')}}">
    <title>Pressify</title>
</head>

<body>
    <!-- Navbar -->
    <header class="bg-dark">
        <div class="container-fluid bg-dark p-2 fs-6 lh-lg px-4">
            <div class="row">
                <div class="col-12 col-md-6 col-lg-4 text-start">
                    <a class="navbar-brand text-white" href="#">
                        <i class="fa-regular fa-envelope pe-2"></i>info@pressify.us
                    </a>
                </div>
                <div class="col-12 col-md-6 col-lg-4 text-center">
                    <a class="nav-link active text-white" aria-current="page" href="#">
                        Free $30 for your first order
                    </a>
                </div>
                <div class="col-12 col-md-6 col-lg-4 text-end">
                    <!-- Phần tử này sẽ nằm bên phải trên màn hình lớn -->
                </div>
            </div>
        </div>
    </header>
    <section class="hero-container">
        
        <!-- Background Video -->
        <video class="elementor-background-video-hosted elementor-html5-video" autoplay muted playsinline loop src="{{ asset('assets/img/image_pressify/Home-1-Slider-Bg-Vid.mp4')}}"></video>
        <div class="hero-overlay"></div>
        <!-- Overlay Menu -->
        <nav class="navbar navbar-expand-lg navbar-dark position-absolute w-100 px-4" style="top: 0;">
            <div class="container-fluid">
                <!-- Logo -->
                <a class="navbar-brand" href="#">
                    <img src="{{ asset('assets/img/image_pressify/logo_PRESSIFY_big.png')}}" height="50px" alt="Logo">
                </a>
                <!-- Toggler Button -->
                <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <!-- Navbar Content -->
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav mx-auto text-uppercase fw-bold fs-6">
                        <li class="nav-item me-3">
                            <a class="nav-link text-white menu-active" aria-current="page" href="/">Home</a>
                        </li>
                        <li class="nav-item me-3">
                            <a class="nav-link text-white" href="#about">About</a>
                        </li>
                        <li class="nav-item me-3">
                            <a class="nav-link text-white" href="https://docs.google.com/spreadsheets/d/1kkKmHyboIlQkVNOZbDQCxanenNkfm_JUZQEWX3gDJjM/edit?gid=184711144#gid=184711144">Catalog</a>
                        </li>
                        <li class="nav-item me-3">
                            <a class="nav-link text-white" href="#api">Integration</a>
                        </li>
                        <li class="nav-item me-3">
                            <a class="nav-link text-white" href="#">Bulk Orders</a>
                        </li>
                    </ul>
                    <!-- Icons -->
                    <div class="d-flex align-items-center ms-3">
                        <a class="btn btn-dark rounded-circle d-flex align-items-center justify-content-center text-white" style="width: 40px; height: 40px;" href="{{route('auth.login')}}">
                            <i class="fa-regular fa-user"></i>
                        </a>
                        <!-- <a class="btn btn-dark rounded-circle d-flex align-items-center justify-content-center text-white ms-2" style="width: 40px; height: 40px;" href="#">
                            <i class="fa-solid fa-cart-shopping"></i>
                        </a>
                        <a class="btn btn-dark rounded-circle d-flex align-items-center justify-content-center text-white ms-2" style="width: 40px; height: 40px;" href="#">
                            <i class="fa-regular fa-heart"></i>
                        </a> -->
                    </div>
                </div>
            </div>
        </nav>
        <!-- Offcanvas Menu -->
        <div class="offcanvas offcanvas-end text-bg-light" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">
            <div class="offcanvas-header">
                <h5 class="" id="offcanvasNavbarLabel">Menu</h5>
                <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="">
                <ul class="navbar-nav justify-content-end flex-grow-1 pe-3 mobile-menu">
                    <li class="nav-item mobile-menu-active">
                        <a class="nav-link border-top " aria-current="page" href="#">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link border-top" href="#about">About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link border-top" href="#">Printme</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link border-top" href="#">Pages</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link border-top" href="mailto:info@pressify.us">Bulk Orders</a>
                    </li>
                    <li class="nav-item">
                        <a class="m-3" href="{{route('auth.login')}}"><button class="btn btn-primary">Login</button></a>
                    </li>
                </ul>
            </div>
        </div>
        <!-- Hero Content -->
        <div class="hero-content container-fluid">
            <div class="row">
                <!-- Text Box -->
                <div class="col-12 col-md-6 d-flex align-items-center text-box">
                    <div class="text-content">
                        <span class="text-uppercase d-block mb-2">Digital Printing Service</span>
                        <h1 class="fw-bold fs-1 fs-md-2 fs-sm-3">Truly Inspired Personal & Promotional Gifts</h1>
                        <p class="fs-6 fs-md-5 fs-sm-6">Specializing in custom printing, particularly for t-shirts, and offering warehouse rental services, we provide comprehensive solutions tailored to your needs.</p>
                        <div class="d-flex text-white fs-5 fs-md-6">
                            <div class="me-5">
                                <i class="fa-solid fa-laptop-code me-2 fs-3 fs-md-4 fs-sm-5"></i>50+ Big Partners
                            </div>
                            <div>
                                <i class="fa-solid fa-laptop-code me-2 fs-3 fs-md-4 fs-sm-5"></i>500k+ Item Processed
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Image -->
                <div class="col-12 col-md-6 d-flex justify-content-center align-items-center">
                    <img src="{{ asset('assets/img/image_pressify/Home-1-Banner-3.png')}}" class="img-fluid" alt="Additional Image">
                </div>
            </div>
        </div>
    </section>
    
    <section class="py-5">
        <div class="container">
            <h1 class="text-center fw-bold mb-4">Print Solutions</h1>
            <div class="text-center mx-auto mb-5" style="max-width: 800px;">
                Modern production systems utilize DTF (Direct to Film) technology for printing. This innovative method streamlines the printing process, enhancing efficiency and output quality.
            </div>
        </div>
        <div class="container">
            <div class="row g-4">
                <!-- Card 1 -->
                <div class="col-sm-12 col-md-6 col-lg-3 d-flex flex-column">
                    <div class="order-2 order-md-1 card-body p-3">
                        <h5 class="card-title text-uppercase fw-bold">1. Branding</h5>
                    </div>
                    <img src="{{ asset('assets/img/image_pressify/home-1-img-box-img01.jpg')}}" class="order-1 order-md-2 card-img-top rounded-3" alt="Image 1">
                </div>
                <!-- Card 2 -->
                <div class="col-sm-12 col-md-6 col-lg-3 d-flex flex-column">
                    <img src="{{ asset('assets/img/image_pressify/home-1-img-box-img02.jpg')}}" class="order-1 order-md-1 card-img-top rounded-3" alt="Image 2">
                    <div class="order-2 card-body p-3">
                        <h5 class="card-title text-uppercase fw-bold mb-3">2. Shirt Printing</h5>
                        <!-- <p class="card-text text-uppercase mb-0 d-flex align-items-center justify-content-start">
                            View More <i class="fa-solid fa-caret-right fs-3 ms-2"></i>
                        </p> -->
                    </div>
                </div>
                <!-- Card 3 -->
                <div class="col-sm-12 col-md-6 col-lg-3 d-flex flex-column">
                    <div class="order-2 order-md-1 card-body p-3">
                        <h5 class="card-title text-uppercase fw-bold mb-3">3. Warehouse</h5>
                       <!--  <p class="card-text text-uppercase mb-0 d-flex align-items-center justify-content-start">
                            View More <i class="fa-solid fa-caret-right fs-3 ms-2"></i>
                        </p> -->
                    </div>
                    <img src="{{ asset('assets/img/image_pressify/home-1-img-box-img03.jpg')}}" class="order-1 order-md-2 card-img-top rounded-3" alt="Image 3">
                </div>
                <!-- Card 4 -->
                <div class="col-sm-12 col-md-6 col-lg-3 d-flex flex-column">
                    <img src="{{ asset('assets/img/image_pressify/home-1-img-box-img04.jpg')}}" class="order-1 order-md-1 card-img-top rounded-3" alt="Image 4">
                    <div class="order-2 card-body p-3">
                        <h5 class="card-title text-uppercase fw-bold mb-3">4. Custom gift</h5>
                        <!-- <p class="card-text text-uppercase mb-0 d-flex align-items-center justify-content-start">
                            View More <i class="fa-solid fa-caret-right fs-3 ms-2"></i>
                        </p> -->
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    
    <section class="py-5">
        <div class="container">
            <div class="position-relative">
                <!-- Card 1 -->
                <div class="card bg-about-us mb-5 border-0 bg-primary text-white">
                    <div class="card-body" style="padding: 80px">
                        <div class="container">
                            <div class="row ">
                                <!-- Image Column -->
                                <div class="col-md-5 position-relative d-flex align-items-center order-md-1">
                                    <!-- Image Container -->
                                    <div class="position-relative">
                                        <!-- Image -->
                                        <img src="{{ asset('assets/img/image_pressify/home-01-portfolio-01.png')}}" class="img-fluid rounded" alt="Get in Touch Image">
                                        <!-- Dark Overlay on Image -->
                                        <div class="position-absolute top-0 start-0 w-100 h-100 bg-dark opacity-50 rounded"></div>
                                    </div>
                                    <!-- Overlay Content -->
                                    <div class="position-absolute top-0 end-0 p-2 text-white rounded" style="max-width: 50%; width: auto;">
                                        <div class="row">
                                            <!-- Second Column -->
                                            <div class="col-8">
                                                <p class="mb-0">500k+ happy customers</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Text Column -->
                                <div class="col-md-7 d-flex flex-column justify-content-center order-md-0">
                                    <h2 class="fs-1 fw-bold">About Us</h2>
                                    <p>Based in Texas, we're inspired by our state's dynamic culture and diverse community. Our team is committed to exceptional customer service, offering fast support and utilizing modern production systems like DTF technology for swift, 24-hour production. With our efficient tracking and management system, we ensure your t-shirt fulfillment experience is seamless and exceeds expectations. Specializing in custom printing, particularly for t-shirts, and offering warehouse rental services, we provide comprehensive solutions tailored to your needs.</p>
                                    <div class="mb-4">
                                        <a href="https://t.me/gunpressify" target="_blank" style="text-decoration:none">
                                                <button type="button" class="btn btn-lg d-flex align-items-center btn-custom-app">
                                            Get in Touch <i class="fa-solid fa-caret-right fs-3 ms-2"></i>
                                        </button>
                                    </a>
                                    </div>
                                    <div class="d-flex align-items-center text-white fs-5">
                                        <!-- Call Us Item -->
                                        <div class="d-flex align-items-center me-5">
                                            <a class="btn btn-dark rounded-circle d-flex align-items-center justify-content-center text-white me-2"
                                                style="width: 40px; height: 40px;" href="#">
                                                <i class="fa-solid fa-phone"></i>
                                            </a>
                                            <div class="fs-6">
                                                <div>Call Us Anytime:</div>
                                                <div>+(832) 699-6882</div>
                                            </div>
                                        </div>
                                        <!-- Email Us Item -->
                                        <div class="d-flex align-items-center">
                                            <a class="btn btn-dark rounded-circle d-flex align-items-center justify-content-center text-white me-2"
                                                style="width: 40px; height: 40px;" href="#">
                                                <i class="fa-regular fa-envelope"></i>
                                            </a>
                                            <div class="fs-6">
                                                <div>Email Us Anytime:</div>
                                                <div>info@pressify.us</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="position-absolute overlay-card" style="bottom: -180px;">
                    <div class="card border-0 shadow p-3 mb-5 bg-body-tertiary rounded">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-9">
                                    <h1 class="mb-3 fs-1">Integrate our system via API</h1>
                                    <div class="mx-auto mb-4">
                                        Easy connect fulfill with our system by CSV or API, save time and automatic. Through our API, businesses can effortlessly connect their platforms with our fulfillment services, enabling smooth order processing and tracking. The API offers comprehensive documentation and support, facilitating a hassle-free integration experience for developers.
                                    </div>
                                </div>
                                <div class="col-3">
                                    <a href="https://docs.google.com/document/d/17hDrcNUcgtJ2_FC8jtkALcMIUJL3ynbT4m5xR0Y7QMs/edit#heading=h.vvozvt6heu9l" target="_blank" style="text-decoration: none">
                                        <button type="button" class="btn btn-lg d-flex align-items-center btn-custom-app">
                                        View Document <i class="fa-solid fa-caret-right fs-3 ms-2"></i>
                                    </button>
                                </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    
    <section class="py-5 bg-statistical">
        <div class="container">
            <h1 class="fw-bold mb-4">Our best index</h1>
            <div class="row mt-4">
                <!-- Row 1 -->
                <div class="col-sm-12 col-md-6 col-lg-4 mb-4">
                    <div class="card shadow-none statistical border-0">
                        <div class="card-body">
                            <h1 class="fw-lighter" style="font-size: 70px;">98%</h1>
                            <h4 class="fw-bold">Same day production</h4>
                            <p class="card-text">Our production process guarantees fast turnaround times, completing orders within 24 hours</p>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12 col-md-6 col-lg-4 mb-4">
                    <div class="card shadow-none statistical border-0">
                        <div class="card-body">
                            <h1 class="fw-lighter" style="font-size: 70px;">95%</h1>
                            <h4 class="fw-bold">Delivery under 6 days</h4>
                            <p class="card-text">Our service using USPS ground, it's delivery faster than economy</p>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12 col-md-6 col-lg-4 mb-4">
                    <div class="card shadow-none statistical border-0">
                        <div class="card-body">
                            <h1 class="fw-lighter" style="font-size: 70px;">99%</h1>
                            <h4 class="fw-bold">Customer happy</h4>
                            <p class="card-text">
We provide fast support services to ensure timely assistance for our customers. Our dedicated team is committed to resolving inquiries and issues promptly</p>
                        </div>
                    </div>
                </div>
            
           
        </div>
    </div>
    </section>
    <section class="py-5" id="ourteam">
        <div class="container">
            <h1 class="text-center fw-bold mb-4">Our Team</h1>
            <div class="text-center mx-auto mb-5" style="max-width: 800px;">
                We are a group of innovative, experienced, and proficient teams. You will love to collaborate with us.
            </div>
            <div class="container">
                <div class="row g-3">
                    <!-- Card 1 -->
                    <div class="col-md-4">
                        <div class="card border-0">
                            <img src="{{ asset('assets/img/image_pressify/henry2.jpeg')}}" class="card-img-top rounded-3" alt="Image 1">
                            <div class="card-body text-center">
                                <h5 class="card-title text-uppercase fw-bold mb-3">Henry Le</h5>
                                <p class="card-text text-uppercase mb-0">
                                    Chairman
                                </p>
                            </div>
                        </div>
                    </div>
                    <!-- Card 2 -->
                    <div class="col-md-4">
                        <div class="card border-0">
                            <div class="card-body text-center">
                                <h5 class="card-title text-uppercase fw-bold mb-3">Kelly Nguyen</h5>
                                <p class="card-text text-uppercase mb-0">
                                    Vice President
                                </p>
                            </div>
                            <img src="{{ asset('assets/img/image_pressify/kelly2.jpeg')}}" class="card-img-bottom rounded-3" alt="Image 2">
                        </div>
                    </div>
                    <!-- Card 3 -->
                    <div class="col-md-4">
                        <div class="card border-0">
                            <img src="{{ asset('assets/img/image_pressify/dung.png')}}" class="card-img-top rounded-3" alt="Image 3">
                            <div class="card-body text-center">
                                <h5 class="card-title text-uppercase fw-bold mb-3">Thuy Dung</h5>
                                <p class="card-text text-uppercase mb-0">
                                    Head of Growth
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </section>
    <section class="py-5">
        <div class="container">
            <h1 class="text-center fw-bold mb-4">Customer's Feedbacks</h1>
            <div class="text-center mx-auto mb-5" style="max-width: 800px;">
                Real Experiences from Our Satisfied Clients
            </div>
            <div class="row mt-4">
                <!-- Row 1 -->
                <div class="col-sm-12 col-md-6 col-lg-4 mb-4">
                    <div class="card shadow-sm border-0">
                        <div class="card-body p-4" style="background-color: #FFF1F1;">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center mb-4">
                                    
                                    <div class="name">
                                        <h5 class="card-title fw-bold">Quoc Viet</h5>
                                        <h6 class="card-subtitle text-muted">- Manager</h6>
                                    </div>
                                </div>
                                <div class="icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" width="50"
                                        height="50">
                                        <path
                                            d="M42.82,51.76c-.34,14.06-6.8,25.08-18.38,33a39.22,39.22,0,0,1-22,6.87c-.24,0-.51,0-.75,0V77.85c1.35-.08,2.71,0,4-.24a27.5,27.5,0,0,0,22.94-22c.35-1.69.41-3.43.61-5.22H1.71V9.27c.34,0,.69-.06,1-.06H42.88c0,.33,0,.53,0,.75C42.92,23.89,43.16,37.83,42.82,51.76Z"
                                            fill="#FF6A66"></path>
                                        <path
                                            d="M97.86,50.26c-.37,17.82-8.94,30.54-25,38.09a36.72,36.72,0,0,1-15.32,3.27h-.7V78a27.33,27.33,0,0,0,22.1-11.38,26.72,26.72,0,0,0,5.28-16.14H56.77V9.27H97.92v1C97.92,23.58,98.14,36.92,97.86,50.26Z"
                                            fill="#FF6A66"></path>
                                    </svg>
                                </div>
                            </div>
                            <p class="card-text">"This was my first time using a print-on-demand service, and I’m beyond impressed! The print quality exceeded my expectations, and the product arrived earlier than expected. The attention to detail in the packaging was a nice touch. I will definitely recommend this service to others!"

</p>
                            <footer class="blockquote-footer mt-3">24rd Jan 2024</footer>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12 col-md-6 col-lg-4 mb-4">
                    <div class="card shadow-sm border-0">
                        <div class="card-body p-4" style="background-color: #FFF1F1;">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center mb-4">
                                    
                                    <div class="name">
                                        <h5 class="card-title fw-bold">Tan Bui</h5>
                                        <h6 class="card-subtitle text-muted">- CEO</h6>
                                    </div>
                                </div>
                                <div class="icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" width="50"
                                        height="50">
                                        <path
                                            d="M42.82,51.76c-.34,14.06-6.8,25.08-18.38,33a39.22,39.22,0,0,1-22,6.87c-.24,0-.51,0-.75,0V77.85c1.35-.08,2.71,0,4-.24a27.5,27.5,0,0,0,22.94-22c.35-1.69.41-3.43.61-5.22H1.71V9.27c.34,0,.69-.06,1-.06H42.88c0,.33,0,.53,0,.75C42.92,23.89,43.16,37.83,42.82,51.76Z"
                                            fill="#FF6A66"></path>
                                        <path
                                            d="M97.86,50.26c-.37,17.82-8.94,30.54-25,38.09a36.72,36.72,0,0,1-15.32,3.27h-.7V78a27.33,27.33,0,0,0,22.1-11.38,26.72,26.72,0,0,0,5.28-16.14H56.77V9.27H97.92v1C97.92,23.58,98.14,36.92,97.86,50.26Z"
                                            fill="#FF6A66"></path>
                                    </svg>
                                </div>
                            </div>
                            <p class="card-text">"I'm thrilled with the quality of the prints and the timely delivery! The colors were vibrant, and the print was true to my original design. The entire process was smooth, from uploading my artwork to receiving the final product. I’ll definitely be using this service again!"</p>
                            <footer class="blockquote-footer mt-3">23rd Oct 2023</footer>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12 col-md-6 col-lg-4 mb-4">
                    <div class="card shadow-sm border-0">
                        <div class="card-body p-4" style="background-color: #FFF1F1;">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center mb-4">
                                   
                                    <div class="name">
                                        <h5 class="card-title fw-bold">Cong Nguyen</h5>
                                        <h6 class="card-subtitle text-muted">- Manager</h6>
                                    </div>
                                </div>
                                <div class="icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" width="50"
                                        height="50">
                                        <path
                                            d="M42.82,51.76c-.34,14.06-6.8,25.08-18.38,33a39.22,39.22,0,0,1-22,6.87c-.24,0-.51,0-.75,0V77.85c1.35-.08,2.71,0,4-.24a27.5,27.5,0,0,0,22.94-22c.35-1.69.41-3.43.61-5.22H1.71V9.27c.34,0,.69-.06,1-.06H42.88c0,.33,0,.53,0,.75C42.92,23.89,43.16,37.83,42.82,51.76Z"
                                            fill="#FF6A66"></path>
                                        <path
                                            d="M97.86,50.26c-.37,17.82-8.94,30.54-25,38.09a36.72,36.72,0,0,1-15.32,3.27h-.7V78a27.33,27.33,0,0,0,22.1-11.38,26.72,26.72,0,0,0,5.28-16.14H56.77V9.27H97.92v1C97.92,23.58,98.14,36.92,97.86,50.26Z"
                                            fill="#FF6A66"></path>
                                    </svg>
                                </div>
                            </div>
                            <p class="card-text">"I couldn’t be happier with the results! The entire process was seamless, and the final product turned out exactly as I envisioned. The print quality is top-notch, and the colors are spot on. The quick turnaround time was a huge plus. This is now my go-to service for all my print needs!"
</p>
                            <footer class="blockquote-footer mt-3">23rd Dec 2023</footer>
                        </div>
                    </div>
                </div>
    
              
                
                
            </div>
    </section>
    <footer class="py-5">
        <div class="container">
            <div class="row">
                <div class="col-sm-12 col-md-6 col-lg-3 mb-2 mb-md-0 p-4">
                    <h5 class="text-uppercase fw-bold fs-5 pb-4 border-bottom 1px solid white"><i
                            class="color-icon me-2 fa-solid fa-circle-exclamation"></i>Quick Links</h5>
                    <ul class="list-unstyled fw-medium">
                        <li class="py-1"><a href="#" class="text-dark">About Us</a></li>
                        <li class="py-1"><a href="#" class="text-dark">Policy</a></li>
                        <li class="py-1"><a href="#" class="text-dark">Legal</a></li>
                    </ul>
                </div>
                <div class="col-sm-12 col-md-6 col-lg-3 mb-2 mb-md-0 p-4">
                    <h5 class="text-uppercase fw-bold fs-5 pb-4 border-bottom 1px solid white"><i
                            class="color-icon me-2 fa-regular fa-hard-drive"></i>Services</h5>
                    <ul class="list-unstyled fw-medium">
                        <li class="py-1"><a href="#" class="text-dark">Print on Demand</a></li>
                        <li class="py-1"><a href="#" class="text-dark">Warehouse</a></li>
                        <li class="py-1"><a href="#" class="text-dark">Customize product</a></li>
                    </ul>
                </div>
                <div class="col-sm-12 col-md-6 col-lg-3 mb-2 mb-md-0 p-4">
                    <h5 class="text-uppercase fw-bold fs-5 pb-4 border-bottom 1px solid white"><i
                            class="color-icon me-2 fa-regular fa-credit-card"></i>Payment</h5>
                    <ul class="list-unstyled fw-medium">
                        <li class="py-1"> <img src="{{ asset('assets/img/image_pressify/Payment-1.png')}}" class="me-1" alt=""> <a href="#"
                                class="text-dark">Paypal</a></li>
                        <li class="py-1"> <img src="{{ asset('assets/img/image_pressify/Payment-3.png')}}" class="me-1" alt=""> <a href="#"
                                class="text-dark">Mastercard</a></li>
                        <li class="py-1"> <img src="{{ asset('assets/img/image_pressify/Payment-6.png')}}" class="me-1" alt=""> <a href="#"
                                class="text-dark">Net Banking</a></li>
                    </ul>
                </div>
                <div class="col-sm-12 col-md-6 col-lg-3 mb-2 mb-md-0 p-4">
                    <h5 class="text-uppercase fw-bold fs-5 pb-4 border-bottom 1px solid white"><i
                            class="color-icon me-2 fa-solid fa-truck-fast"></i>Shipping</h5>
                    <ul class="list-unstyled fw-medium">
                        <li class="py-1 d-flex align-items-center">
                            <div class="text-dark d-flex align-items-center">
                                <div class="row">
                                    <div class="col-2"> <i class="color-icon fa-regular fa-address-card me-3 fs-4"></i>
                                    </div>
                                    <div class="col-10">
                                        <div>
                                            <h6 class="mb-0">Express Production</h6>
                                            <p class="mb-0">1-2 Business Days</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li class="py-1 d-flex align-items-center">
                            <div class="text-dark d-flex align-items-center">
                                <div class="row">
                                    <div class="col-2"><i class="color-icon fa-regular fa-address-book me-3 fs-4"></i>
                                    </div>
                                    <div class="col-10">
                                        <div>
                                            <h6 class="mb-0">Express Shipping</h6>
                                            <p class="mb-0">3-5 Business Days</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li class="py-1 pt-3 d-flex align-items-center border-bottom 1px solid white pb-3"><i
                                class="color-icon fa-solid fa-headset me-3 fs-4"></i>
                            <h4 class="text-uppercase m-0">Connect with us</h4>
                        </li>
                        <li class="py-1 pt-3">
                            <p class="fs-6 text-secondary">Address: 10161 Harwin Dr. #150, Houston, TX 77036, United States.</p>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="container pt-4 border-top">
                <div class="row">
                    <div class="col-md-6 mb-3 mb-md-0 text-center text-md-start">
                        <p class="mb-0">&copy; 2024 Pressify. All Rights Reserved.</p>
                    </div>
                    <div
                        class="col-md-6 mb-3 mb-md-0 text-center text-md-end d-flex justify-content-center justify-content-md-end align-items-center gap-2">
                        <p class="mb-0">Follow Us</p>
                        
                        <div class="bg-icon text-center text-white rounded-circle p-2"
                            style="width:40px; height: 40px">
                            <a href="#"
                                class="text-white d-flex align-items-center justify-content-center h-100 w-100 text-decoration-none">
                                <i class="fa-brands fa-twitter"></i>
                            </a>
                        </div>
                        <div class="bg-icon text-center text-white rounded-circle p-2"
                            style="width:40px; height: 40px">
                            <a href="#"
                                class="text-white d-flex align-items-center justify-content-center h-100 w-100 text-decoration-none">
                                <i class="fa-brands fa-youtube"></i>
                            </a>
                        </div>
                        <div class="bg-icon text-center text-white rounded-circle p-2"
                            style="width:40px; height: 40px">
                            <a href="#"
                                class="text-white d-flex align-items-center justify-content-center h-100 w-100 text-decoration-none">
                                <i class="fa-brands fa-instagram"></i>
                            </a>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </footer>
</body>

</html>