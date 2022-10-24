<div id="fh5co-page">
<header id="fh5co-header-section" class="sticky-banner"> 
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
    <div class="container">
        <div class="nav-header">
            <a href="#" class="js-fh5co-nav-toggle fh5co-nav-toggle dark"><i></i></a>
            <h1 id="fh5co-logo"><a href="/"><img src="images/panologo.png" style="height: 40px;"></a></h1>
            <!-- START #fh5co-menu-wrap -->
            <nav id="fh5co-menu-wrap" role="navigation">
                <ul class="sf-menu" id="fh5co-primary-menu">
                    <li><a href="/">Stays</a></li>	
                    <li><a href="/TourPackages">Tour Packages</a></li>	
                    <li><a href="/taxis">Airport Taxi</a></li>
                    <li><a href="/cars">Car Rental</a></li>
                    <li><a href="/ambulance">Ambulance</a></li>
                    <li><a href="/blog">Blog</a></li>
                    <li class="active"><a href="/contactus">Contact</a></li>
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
<div id="fh5co-contact" class="fh5co-section-gray">
    <div class="container">
        <div class="row ">
            <div class="col-md-8 col-md-offset-2 text-center heading-section">
                <h3>Contact Information</h3>
                <p>Feel free to contact us using the form provided below or you can send us a direct Email. Or you can use the phone numbers provided below. Our assurance is you will get immediate response</p>
            </div>
        </div>
        <div class="row" style="margin-top:20px;">
        <form wire:submit.prevent="sendmessage">
            @csrf
            
                <div class="col-md-6">
                    <h3>Our Address</h3>
                    <p>1 KM off Northern bypass bwaise fly over along sir Apollo Kagwa Rd</p>
                    <ul class="contact-info">
                        <li><i class="icon-location-pin" style='color:#00ff40'></i>Sir Apollo Kagwa Rd, Plot 160, UMF House, 3rd Floor</li>
                        <li><i class="icon-phone2" style='color:#00ff40'></i>+ 256 741822377 / +256 760132023</li>
                        <li><i class='icon-chat' style='color:#00ff40'></i>WhatsApp: + 256 741822377</li>
                        <li><i class="icon-mail" style='color:#00ff40'></i><a href="#">info@panobooking.email</a></li>
                        <li><i class="icon-globe2" style='color:#00ff40'></i><a href="https://panobooking.com">www.panobooking.com</a></li>
                    </ul>
                </div>
                <div class="col-md-6">
                    
                        <div class="col-md-6">
                            <div class="form-group">
                                @error('inputname') <span class="text-danger">{{ $message }}</span> @enderror
                                <input type="text" class="form-control" name="inputname" wire:model.lazy="inputname" placeholder="Name">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                @error('email') <span class="text-danger">{{ $message }}</span> @enderror
                                <input type="text" class="form-control" name="email" wire:model.lazy="email" placeholder="Email">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                @error('contactinfo') <span class="text-danger">{{ $message }}</span> @enderror
                                <textarea  class="form-control" name="contactinfo" wire:model.lazy="contactinfo" cols="30" rows="7" placeholder="Message"></textarea>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <input type="submit" value="Send Message" class="btn btn-primary">
                            </div>
                        </div>
                    </div>
                    </form>
            
        </div>
        </div>
    </div>
<div  class="fh5co-map">
    <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d15958.993003168596!2d32.5630253!3d0.3395677!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0xf334a85b026f207d!2sPANOBOOKING%20LTD.!5e0!3m2!1sen!2sug!4v1642432036230!5m2!1sen!2sug" width="100%" height="500" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
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