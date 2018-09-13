<!DOCTYPE HTML>
<html lang="en">
<head>
    <title>TITLE</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="UTF-8">


    <!-- Font -->

    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500" rel="stylesheet">
    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css?family=Montserrat">

    <!-- Stylesheets -->

    <link href="{{asset('css/front.css')}}" rel="stylesheet">

</head>
<body>
    <header>

        <div class="top-menu">

            <ul class="left-area welcome-area">
                <li class="hello-blog">Hello nice people, welcome to my blog</li>
                <li><a href="mailto:contact@juliblog.com">contact@juliblog.com</a></li>
            </ul><!-- left-area -->


            <div class="right-area">

                <div class="src-area">
                    <form action="post">
                        <input class="src-input" type="text" placeholder="Search">
                        <button class="src-btn" type="submit"><i class="ion-ios-search-strong"></i></button>
                    </form>
                </div><!-- src-area -->

                <ul class="social-icons">
                    <li><a href="#"><i class="ion-social-facebook-outline"></i></a></li>
                    <li><a href="#"><i class="ion-social-twitter-outline"></i></a></li>
                    <li><a href="#"><i class="ion-social-instagram-outline"></i></a></li>
                    <li><a href="#"><i class="ion-social-vimeo-outline"></i></a></li>
                    <li><a href="#"><i class="ion-social-pinterest-outline"></i></a></li>
                </ul><!-- right-area -->

            </div><!-- right-area -->

        </div><!-- top-menu -->

        <div class="middle-menu center-text">
            <label id="header_name">Trần Xuân Thanh Phúc</label>
        </div>

        <div class="bottom-area">

            <div class="menu-nav-icon" data-nav-menu="#main-menu"><i class="ion-navicon"></i></div>

            <ul class="main-menu visible-on-click" id="main-menu">
                <li><a href="{{url('/')}}">TRANG CHỦ</a></li>
                @foreach(\App\Repositories\CategoryRepository::getInstance()->getRootCategories() as $cate)
                    <?php
                        $childs = $cate->childs;
                        $childCount = $childs->count();
                    ?>
                    <li class="@if($childCount > 0) drop-down @endif">
                        <a href="{{url('/')}}">{{\Illuminate\Support\Str::upper($cate->name)}} @if($childCount > 0) <i class="ion-ios-arrow-down"></i> @endif</a>
                        @if($childCount > 0)
                            <ul class="drop-down-menu">
                            @foreach($childs as $child)
                                    <li><a href="#">{{\Illuminate\Support\Str::upper($child->name)}}</a></li>
                            @endforeach
                            </ul>
                        @endif
                    </li>
                @endforeach
                <li><a href="#">LIÊN HỆ</a></li>
            </ul><!-- main-menu -->

        </div><!-- conatiner -->
    </header>

    <section>
        @yield('top')
    </section>

    <section class="section blog-area">
        <div class="container">
            <div class="row">

                <div class="col-lg-8 col-md-12">
                    @yield('content')
                </div><!-- col-lg-4 -->


                <div class="col-lg-4 col-md-12">
                    <div class="sidebar-area">

                        @yield('sidebar')

                    </div><!-- about-author -->
                </div><!-- col-lg-4 -->

            </div><!-- row -->
        </div><!-- container -->
    </section><!-- section -->

    <section class="footer-instagram-area">

        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    <h5 class="title"><b class="light-color">Follow me &copy; instagram</b></h5>
                </div><!-- col-lg-4 -->
            </div><!-- row -->
        </div><!-- container -->

        <ul class="instagram">
            <li><a href="#"><img src="images_demo/instragram-1-300x400.jpg" alt="Instagram Image"></a></li>
            <li><a href="#"><img src="images_demo/instragram-2-300x400.jpg" alt="Instagram Image"></a></li>
            <li><a href="#"><img src="images_demo/instragram-3-300x400.jpg" alt="Instagram Image"></a></li>
            <li><a href="#"><img src="images_demo/instragram-4-300x400.jpg" alt="Instagram Image"></a></li>
            <li><a href="#"><img src="images_demo/instragram-5-300x400.jpg" alt="Instagram Image"></a></li>
            <li><a href="#"><img src="images_demo/instragram-6-300x400.jpg" alt="Instagram Image"></a></li>
            <li><a href="#"><img src="images_demo/instragram-7-300x400.jpg" alt="Instagram Image"></a></li>
        </ul>
    </section><!-- section -->


    <footer>
        <div class="container">
            <div class="row">

                <div class="col-sm-6">
                    <div class="footer-section">
                        <p class="copyright">Juli &copy; 2018. All rights reserved. | This template is made with <i class="ion-heart" aria-hidden="true"></i> by <a href="https://colorlib.com" target="_blank">Colorlib</a></p>
                    </div><!-- footer-section -->
                </div><!-- col-lg-4 col-md-6 -->

                <div class="col-sm-6">
                    <div class="footer-section">
                        <ul class="social-icons">
                            <li><a href="#"><i class="ion-social-facebook-outline"></i></a></li>
                            <li><a href="#"><i class="ion-social-twitter-outline"></i></a></li>
                            <li><a href="#"><i class="ion-social-instagram-outline"></i></a></li>
                            <li><a href="#"><i class="ion-social-vimeo-outline"></i></a></li>
                            <li><a href="#"><i class="ion-social-pinterest-outline"></i></a></li>
                        </ul>
                    </div><!-- footer-section -->
                </div><!-- col-lg-4 col-md-6 -->

            </div><!-- row -->
        </div><!-- container -->
    </footer>


    <!-- SCIPTS -->

    <script src="{{asset('js/front.js')}}"></script>

</body>
</html>
