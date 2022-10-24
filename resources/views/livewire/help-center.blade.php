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

<div id="fh5co-tours" class="fh5co-section-gray">
    <div class="container">
        <div class="row">
            <div class="col-md-12 text-center heading-section animate-box">
                <h2>PANO USER GUIDE.</h2>
                
            </div>
        </div>

        <div class="row">
            <div class="col-md-12  animate-box">
                <h3>HOW TO ACCESS PANOBOOKING SERVICES</h3>
                <ul style="list-style: none;">
                <li><h3>a)</h3>	Access the PANOBOOKING portal via our website (<a href="https://panobooking.com">https://panobooking.com</a>) or by downloading our mobile app from play store and explore the wide range of services offered via the platform. Further information can be accessed via our social handles (Facebook(<a href="https://facebook.com/Panobooking1">https://facebook.com/Panobooking1</a>) , Twitter(<a href="https://twitter.com/Panobooking1">https://twitter.com/Panobooking1 </a>) &Instagram(<a href="https://instagram.com/Panobooking1">https://instagram.com/Panobooking1</a>). Panobooking Limited (“Panobooking” or “we”) operates an e-commerce platform consisting of a web and mobile applications together with supporting logistics and payment infrastructure, for the sale and purchase of specified services and services in Uganda. Some of Panobooking’s services hotel booking services, car rental services, and ambulance rental services.</li>
                <li><h3>b)</h3>	Create an account with us for easy access and instant feedback incase of any inquiries made. This also helps in making inquiries and bookings for the different services offered.      How to get an account;</li>
                <li>&nbsp;&nbsp;&nbsp;&nbsp;i.	 Get to the PANOBOOKING interface </li>
                <li>&nbsp;&nbsp;&nbsp;&nbsp;ii.	Click on account</li>
                <li>&nbsp;&nbsp;&nbsp;&nbsp;iii.	For first time users, click register and fill the form given and register your account. If you already have an account simply log into your account.</li>
                    
                <li><h3>c)</h3>	Make a reservation and you will get immediate feedback about the availability of the requested for service or an answer as regard’s the inquiry made will be given immediately. How to make a reservation;</li>
                <li>&nbsp;&nbsp;&nbsp;&nbsp;i.	Booking a hotel; a) Click on stays and a list of accommodation facilities will be availed and the different regions where they are found. (b) Click on the region you wish to have a get accommodation from, hotels will be displayed then make a hotel choice. (c) Reserve your room of preference.</li>
                <li>&nbsp;&nbsp;&nbsp;&nbsp;ii.	Booking an airport taxi;(a) click on airport taxi, then feed in the information about pickup and drop off locations and dates, then reserve your ride.</li>
                <li>&nbsp;&nbsp;&nbsp;&nbsp;iii.	Hiring an ambulance; Click on ambulances, then ambulances will be displayed according to category based on how ill the person is then choose the ambulance of choice. </li>
                <li>&nbsp;&nbsp;&nbsp;&nbsp;iv.	Booking a car; click on car rental car categories will be displayed, choose the category of car you wish for such as wedding cars, safari cars and self-drive cars, then choose a car you would like to rent.</li>
                <li>&nbsp;&nbsp;&nbsp;&nbsp; v.	Book your next tip; click on the tour packages, different packages will be displayed. select one of your choices considering the destinations to be visited and the dates which best serves your interests.</li>
                    
                <li><h3>d)</h3>	The page also gives us chance to view top tourism destinations in the country and the facilities near them so that it’s easy to plan for transport and accommodation while visiting the destinations.  A click on the attraction name (red line) will enable you learn more about the destination whereas a click on view nearby properties will lead you to facilities near the tourism site.</li>
                <li><h3>e)</h3>	Interest yourself with reading our informative and educative blogs by simply clicking on the blog button, be guided as you learn and feel free to drop a comment there after.</li>
                <li><h3>f)</h3>	We welcome more service providers on the platform, accommodation facilities, car rentals and ambulances can be registered from wherever you are. </li>
                    <li>&nbsp;&nbsp;&nbsp;&nbsp;i.	For accommodation registration, click on list property and a form with the required details will be displayed. Fill the form accordingly and then submit. </li>
                    <li>&nbsp;&nbsp;&nbsp;&nbsp;ii.	For car registration, click on register car, fill the displayed form accordingly and submit the information. The car can be wedding, safari or a self-drive kind of car. </li>
                    <li>&nbsp;&nbsp;&nbsp;&nbsp;iii.	For ambulance registration; click on add ambulance, a form will be displayed, feed in the information and submit. Much emphasis is required while filling the category section to ensure that clients get exactly what they need as regards services offered while onboard. </li>
                   
                <li><h3>g)</h3>	Our customer service care team is always readily available to help, give guidance and respond to inquiries, a click on the contact button will have you all options to use to reach out to us for any inquiries.</li>
                </ul>
              
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
            <!--<div class="item">
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
