<!-- About -->
<section class="page-section bg-light" id="about">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="section-heading text-uppercase position-relative d-inline-block">
                <span class="text-primary">Our</span> Journey
                <span class="position-absolute bottom-0 start-50 translate-middle-x bg-primary" style="height: 3px; width: 80px;"></span>
            </h2>
            <h3 class="section-subheading text-muted mt-3">Crafting memorable experiences since 2020</h3>
        </div>
        
        <div class="position-relative">
            <!-- Timeline line -->
            <div class="timeline-line position-absolute h-100 bg-primary" style="width: 3px; left: 50%; transform: translateX(-50%);"></div>
            
            <div class="row g-0">
                <!-- Timeline Item 1 -->
                <div class="col-md-6 mb-5">
                    <div class="card border-0 shadow-sm h-100" data-aos="fade-right">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center mb-3">
                                <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                    <i class="fas fa-lightbulb text-white fs-4"></i>
                                </div>
                                <div class="ms-3">
                                    <h4 class="mb-0">2020-2021</h4>
                                    <h5 class="text-primary mb-0">Passion Brewed into Vision</h5>
                                </div>
                            </div>
                            <p class="text-muted">DineFlow was born from a passion for good food and great coffee. What started as a dream soon evolved into a vision to create a cozy space for flavorful experiences.</p>
                        </div>
                    </div>
                </div>
                
                <!-- Spacer for alternating layout -->
                <div class="col-md-6"></div>
                
                <!-- Timeline Item 2 -->
                <div class="col-md-6"></div>
                <div class="col-md-6 mb-5">
                    <div class="card border-0 shadow-sm h-100" data-aos="fade-left">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center mb-3">
                                <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                    <i class="fas fa-store text-white fs-4"></i>
                                </div>
                                <div class="ms-3">
                                    <h4 class="mb-0">January 2022</h4>
                                    <h5 class="text-primary mb-0">Opening Our First Café</h5>
                                </div>
                            </div>
                            <p class="text-muted">DineFlow officially opened its doors, offering a handcrafted menu of freshly brewed coffee, artisan meals, and warm, welcoming vibes for everyone to enjoy.</p>
                        </div>
                    </div>
                </div>
                
                <!-- Timeline Item 3 -->
                <div class="col-md-6 mb-5">
                    <div class="card border-0 shadow-sm h-100" data-aos="fade-right">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center mb-3">
                                <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                    <i class="fas fa-users text-white fs-4"></i>
                                </div>
                                <div class="ms-3">
                                    <h4 class="mb-0">September 2023</h4>
                                    <h5 class="text-primary mb-0">Community & Creativity</h5>
                                </div>
                            </div>
                            <p class="text-muted">DineFlow became a local favorite, known not just for its delicious menu but also as a creative hub that brought people together over food, music, and conversations.</p>
                        </div>
                    </div>
                </div>
                
                <!-- Spacer -->
                <div class="col-md-6"></div>
                
                <!-- Timeline Item 4 -->
                <div class="col-md-6"></div>
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm h-100" data-aos="fade-left">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center mb-3">
                                <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                    <i class="fas fa-rocket text-white fs-4"></i>
                                </div>
                                <div class="ms-3">
                                    <h4 class="mb-0">Present</h4>
                                    <h5 class="text-primary mb-0">Serving More Than Just Food</h5>
                                </div>
                            </div>
                            <p class="text-muted">Today, DineFlow continues to grow and innovate, blending technology with hospitality to deliver an elevated café experience—one that nourishes both body and soul.</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- End Marker -->
            <div class="d-flex justify-content-center mt-4">
                <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                    <i class="fas fa-heart text-white fs-3"></i>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Add this to your head section -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
    AOS.init({
        duration: 800,
        easing: 'ease-in-out',
        once: true
    });
</script>

<style>
    .timeline-line {
        z-index: 1;
    }
    
    .card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        border-radius: 12px;
        overflow: hidden;
        position: relative;
        z-index: 2;
    }
    
    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
    }
    
    .section-heading {
        font-size: 2.5rem;
        font-weight: 700;
        letter-spacing: 1px;
    }
    
    .section-subheading {
        font-size: 1.1rem;
        font-weight: 400;
    }
    
    @media (max-width: 768px) {
        .timeline-line {
            left: 30px !important;
        }
        
        .col-md-6:not(:empty) {
            padding-left: 60px;
        }
        
        .col-md-6:empty {
            display: none;
        }
    }
</style>