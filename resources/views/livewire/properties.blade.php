<div id="fh5co-page">
    <div class="Informationcontainer">
        <div class="container">
            <div class="row" id="centerrow">
                <form name="search_hotel_form" action="{{route('generalSearchs')}}" method="GET">
                <div  id="generalsearch" ><input type="search" name="salessearch" id="salessearch" placeholder="Search Accomodation, Cars, Airport Taxis, Ambulances..."/>
                <button class="btn shadow-none Listingbuttonss" type='submit' name="searchbutton">Search</button></div>
                </form>
                <form wire:submit.prevent="changecurrency">
                    @csrf
                <div id="currencysize" >
                    <span>Currency </span>
                   
                        <select class="currencyselect" id="currencytype" name="currencytype" wire:model="currencytype">
                        <option>UGX</option>
                        <option>USD</option>
                        <option>GBP</option>
                        <option>EUR</option>
                        <option>KES</option>
                        <option>TZS</option>	
                        </select>  
                    </div>     	
            </form>
                <div id="registrybuttons" ><p><a href="/RegisterProperty"><button class="btn shadow-none Listingbuttons">List Property</button></a><a href="/RegisterCars"><button class="btn shadow-none Listingbuttons">Register Car</button></a><a href="/RegisterAmbulance"><button class="btn shadow-none Listingbuttons">Add Ambulance</button></a></p></div>
            </div>
        </div>
    </div> 
<header id="fh5co-header-section" class="sticky-banner"> 
    <div class="container">
        <div class="nav-header">
            <a href="#" class="js-fh5co-nav-toggle fh5co-nav-toggle dark"><i></i></a>
            <h1 id="fh5co-logo"><a href="/"><img src="images/panologo.png" style="height: 40px;"></a></h1>
            <!-- START #fh5co-menu-wrap -->
            <nav id="fh5co-menu-wrap" role="navigation">
                <ul class="sf-menu" id="fh5co-primary-menu">
                    <li class="active"><a href="/">Stays</a></li>
                    <li><a href="/TourPackages">Tour Packages</a></li>		
                    <li><a href="/taxis">Airport Taxi</a></li>
                    <li><a href="/cars">Car Rental</a></li>
                    <li><a href="/ambulance">Ambulance</a></li>
                    <li><a href="/blog">Blog</a></li>
                    <li><a href="/contactus">Contact</a></li>
                    <li>
                        @if(session()->has('pano_client_data'))
                        
                        <a href="#" class="fh5co-sub-ddown">{!!Str::limit(session()->get('pano_client_data.FullNames'),10)!!}</a>
                        <ul class="fh5co-sub-menu">
                            <li><a href="/PendingBookings">Pending Bookings</a></li>
                            <li><a href="/Bookings">Bookings History</a></li>
                            <a href="/clientlogout">Logout</a>
                        </ul>
                        @else
                        <a href="#" class="fh5co-sub-ddown">Account</a>
                        <ul class="fh5co-sub-menu">
                            <li><a href="/Login">Sign in</a></li>
                            <li><a href="/Register">Register</a></li>
                        </ul>
                        @endif
                    </li>
                </ul>
            </nav>
        </div>
    </div>
</header>

<div wire:ignore id="fh5co-tours" class="fh5co-section-gray">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-md-offset-2 text-center heading-section animate-box">
                <h3>{{request()->input('cityslug')}} Stays</h3>
            <p>{{$hotelsearched->count()}} Stays In {{request()->input('cityslug')}}</p>
            </div>
        </div>
      
        <div class="row">
            <div class="owl-carousel featured-carousel owl-theme" >
            @foreach ($hotelsearched as $hotelsearched)
            <div class="item" >
            <div class="col-md-4 col-sm-3 fh5co-tours animate-box" style="width:100%;" data-animate-effect="fadeIn">
                <div  href="{{route('HotelSingle',['hotelslug'=>$hotelsearched->IDs])}}"><img src="https://panobooking.com/AndroidFiles/DestinationImages/{{$hotelsearched->Place}}.jpg"  class="img-responsive" onerror="this.onerror=null;this.src='images/image-placeholder.png';"/>
                    <div class="desc">
                        <span></span>
                        <h3>{{$hotelsearched->Place}}</h3>
                      
                        <span>{{ $hotelsearched->City}}</span>
                        <span class="price">{!!$currencypick!!}. {{number_format(ceil($hotelsearched->Prices/$currenyvalue),0)}}</span>
                        <a class="btn btn-primary btn-outline" href="{{route('HotelSingle',['hotelslug'=>$hotelsearched->IDs])}}">Book Now <i class="icon-arrow-right22"></i></a>
                    </div>
                </div>
            </div> 
        </div>  
            @endforeach
        </div>
    </div>
    </div>
</div>

<div id="fh5co-tours" class="fh5co-section-gray">
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2 text-center heading-section animate-box">
                <h3>{{request()->input('cityslug')}} Rental Cars</h3>
                <p>{{$carsearched->count()}} Rental Cars In {{request()->input('cityslug')}}</p>
            </div>
        </div>
        <div class="row">
            <div class="owl-carousel taxifeatured-carousel owl-theme"  style="color:black;">
                @foreach ($carsearched as $carsearched)
                <div class="item">
                <div class="col-md-4 col-sm-6 fh5co-tours animate-box" style="width:100%;" data-animate-effect="fadeIn">
                    <div  href="{{route('CarDetails',['carslug'=>$carsearched->IDs])}}"><img src="https://panobooking.com/AndroidDriver/FileUpload/{{$carsearched->DriverName}}{{$carsearched->TaxiNo}}.jpg"  class="img-responsive" onerror="this.onerror=null;this.src='images/image-placeholder.png';"/>
                        <div class="desc">
                            <span></span>
                            <h3>{{$carsearched->TaxiNo}}</h3>
                            <span class="price">{!!$currencypick!!}. {{number_format(ceil($carsearched->Price/$currenyvalue),0)}}</span>
                            <a class="btn btn-primary btn-outline" href="{{route('CarDetails',['carslug'=>$carsearched->IDs])}}">Book Now <i class="icon-arrow-right22"></i></a>
                        </div>
                    </div>
                </div> 
            </div>  
                @endforeach
            </div>
        </div> 
    </div>
</div>

<div id="fh5co-tours" class="fh5co-section-gray">
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2 text-center heading-section animate-box">
                <h3>{{request()->input('cityslug')}} Airport Taxis</h3>
                <p>{{$taxisearched->count()}} Airport Taxis In {{request()->input('cityslug')}}</p>
            </div>
        </div>
        <div class="row">
            <div class="owl-carousel taxifeatured-carousel owl-theme">
            @foreach ($taxisearched as $taxisearched)
            <div class="item" >
                <div class="col-md-4 col-sm-6 fh5co-tours animate-box" style="width:100%;" data-animate-effect="fadeIn">
                    <div  href="{{route('TaxiDetails',['taxislug'=>$taxisearched->IDs])}}"><img src="https://panobooking.com/AndroidDriver/FileUpload/{{$taxisearched->TaxiNo}}.jpg" class="img-responsive" onerror="this.onerror=null;this.src='images/image-placeholder.png';"/>
                        <div class="desc">
                            <span></span>
                            <h3>{{$taxisearched->TaxiNo}}</h3>
                            <span>{{$taxisearched->CarSpec}}</span>
                            <span>{{$taxisearched->CurrentLocation}}</span>
                            <span class="price"></span>
                            <a class="btn btn-primary btn-outline" href="{{route('TaxiDetails',['taxislug'=>$taxisearched->IDs])}}">Order Now <i class="icon-arrow-right22"></i></a>
                        </div>
                    </div>
                </div> 
            </div> 
            @endforeach
        </div>
    </div>
    </div>
</div>

<div id="fh5co-tours" class="fh5co-section-gray">
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2 text-center heading-section animate-box">
                <h3>{{request()->input('cityslug')}} Ambulances</h3>
                <p>{{$ambulancesearched->count()}} Ambulances in {{request()->input('cityslug')}}</p>
            </div>
        </div>
        <div class="row">
            <div class="owl-carousel taxifeatured-carousel owl-theme">
            @foreach ($ambulancesearched as $ambulancesearch)
            <div class="item" >
                <div class="col-md-4 col-sm-6 fh5co-tours animate-box" style="width:100%;" data-animate-effect="fadeIn">
                    <div href="{{route('AmbulanceDetails',['ambulanceslug'=>$ambulancesearch->IDs])}}"><img src="https://panobooking.com/AndroidDriver/FileUpload/{{ $ambulancesearch->TaxiNo}}.jpg" class="img-responsive" onerror="this.onerror=null;this.src='images/image-placeholder.png';"/>
                        <div class="desc">
                            <span></span>
                            <h3>{{ $ambulancesearch->TaxiNo}}</h3>
                            <span>{{ $ambulancesearch->CarSpec}}</span>
                            <span>Location: {{ $ambulancesearch->CurrentLocation}}</span>
                            <a class="btn btn-primary btn-outline" href="{{route('AmbulanceDetails',['ambulanceslug'=>$ambulancesearch->IDs])}}">Call Now <i class="icon-arrow-right22"></i></a>
                        </div>
                    </div>
                </div> 
            </div> 
            @endforeach
        </div>
    </div>
    </div>
</div>


    <footer>
        <div id="footer">
            <div class="container">
                <div class="row row-bottom-padded-md">
                    <div class="owl-carousel footer-carousel owl-theme">
                        <div class="item">
                    <div class="col fh5co-footer-link">
                        <h3>Property</h3>
                        <ul>
                            <li><a href="{{route('PropertyTypes',['propertyslug'=>'Hotel'])}}">Hotels</a></li>
                            <li><a href="{{route('PropertyTypes',['propertyslug'=>'Home Stays'])}}">Home Stays</a></li>
                            <li><a href="{{route('PropertyTypes',['propertyslug'=>'Guest Houses'])}}">Guest Houses</a></li>
                            <li><a href="{{route('PropertyTypes',['propertyslug'=>'Apartments'])}}">Apartments</a></li>
                            <li><a href="{{route('PropertyTypes',['propertyslug'=>'Country Homes'])}}">Country Homes</a></li>
                            <li><a href="{{route('PropertyTypes',['propertyslug'=>'Villas'])}}">Villas</a></li>
                            </ul>
                    </div>
                </div>
                <div class="item">
                    <div class="col fh5co-footer-link">
                        <h3>Destinations</h3>
                        <ul>
                            <li><a href="/Regions">Regions</a></li>
                        <li><a href="/Cities">Cities</a></li>
                        <li><a href="/Cities">Towns</a></li>
                        <li><a href="/TourismSites">Tourism Sites</a></li>
                        <li><a href="/Events">Events</a></li>
                        </ul>
                    </div>
                </div>
                <div class="item">
                    <div class="col fh5co-footer-link">
                        <h3>Top Propertys</h3>
                        <ul>
                            @foreach ($topproperties as $topproperty)
                            <li><a href="{{route('HotelSingle',['hotelslug'=>$topproperty->IDs])}}">{{$topproperty->Place}}</a></li>
                            @endforeach
                        </ul>
                    </div>
                </div>
               <!-- <div class="item">
                    <div class="col fh5co-footer-link">
                        <h3>Interest</h3>
                        <ul>
                            <li><a href="#">Beaches</a></li>
                            <li><a href="#">Family Travel</a></li>
                            <li><a href="#">Budget Travel</a></li>
                            <li><a href="#">Food &amp; Drink</a></li>
                            <li><a href="#">Honeymoon and Romance</a></li>
                        </ul>
                    </div>
                </div>-->
                <div class="item">
                    <div class="col fh5co-footer-link">
                        <h3>Articles</h3>
                        <ul>
                            @foreach ($topblogs as $topblog)
                            <li><a href="{{route('BlogDetail',['blogslug'=>$topblog->ID])}}">{{$topblog->BlogTitle}}</a></li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <div class="item">
                    <div class="col fh5co-footer-link">
                        <h3>Help</h3>
                        <ul>
                            <li><a href="/privacypolicy">Privacy Policy</a></li>
                            <li><a href="/TermsAndConditions">Terms & Conditions</a></li>
                            <li><a href="/Faqs">FAQS</a></li>
                            <li><a href="/HelpCenter">Help Center</a></li>
                            <li><a href="/contactus">Contact Us</a></li>
                        </ul>
                    </div>
                </div>
                </div>
            </div>
                <div class="row justify-content-center">
                    <div class="col-md-6 col-md-offset-3 text-center">
                        <p class="fh5co-social-icons">
                            <a href="https://twitter.com/Panobooking1" target="_blank"><i class="icon-twitter2"></i></a>
                            <a href="https://www.facebook.com/Panobooking1" target="_blank"><i class="icon-facebook2"></i></a>
                            <a href="https://www.instagram.com/panobooking1/" target="_blank"><i class="icon-instagram" target="_blank"></i></a>
                            <a href="https://www.youtube.com/channel/UCmjiIcgdYqeJCQn0dOb3IMA" target="_blank"><i class="icon-youtube"></i></a>
                        </p>
                        <p>Copyright &copy; <script>document.write(new Date().getFullYear());</script> <a href="panobooking.com">Panobooking</a>. All Rights Reserved.<!-- <br>Made with <i class="icon-heart3"></i> by <a href="http://freehtml5.co/" target="_blank">Freehtml5.co</a> --></p>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    </div>