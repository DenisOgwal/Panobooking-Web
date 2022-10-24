<div id="fh5co-page">
    <header id="fh5co-header-section" class="sticky-banner"> 
        <div class="container">
            <div class="nav-header">
                <a href="#" class="js-fh5co-nav-toggle fh5co-nav-toggle dark"><i></i></a>
                <h1 id="fh5co-logo"><a href="/"><img src="images/panologo.png" style="height: 40px;"></a></h1>
                <!-- START #fh5co-menu-wrap -->
                <nav id="fh5co-menu-wrap" role="navigation">
                    <ul class="sf-menu" id="fh5co-primary-menu">
                        <li><a href="/">Stays</a></li>	
                        <li><a href="/TourPackages">Tour Packages</a></li>	
                        <li class="active"><a href="/taxis">Airport Taxi</a></li>
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
    
            <div class="container mt-5 mb-5 p-2 border border-info rounded">
                <div class="row">

                  <div class="col-md-12">
                    @foreach ($cars as $cars)
                    <h3 class="modal-title" style="width:100%;text-align:center; margin-bottom:10px;">Specify Order Details for {{$cars->TaxiNo}}</h3>
                    @endforeach
                    <div class="col-xxs-12 col-xs-12 mt alternate">
                        <form wire:submit.prevent="Submitaxirequest">
                    
                            <div class="col-xxs-12 col-xs-6 mt alternate">
                                <label for="class">Pick me up</label>
                                <select class="form-control" id="pickuppoint" name="pickuppoint" wire:model="pickuppoint">
                                    <option value=" ">Select Pick up Point</option>
                                    <option value="From Airport">From Airport</option>
                                    <option value="To Airport">To Airport</option>
                                </select>
                           </div>
        
                            <div class="col-xxs-12 col-xs-6 mt alternate">
                                <label for="class">Airport:</label>
                                <select class="form-control" wire:model="airportfield"  id="airportfield" name="airportfield"  >
                                    <option value=" ">Select Airport</option>
                                    <option value="Entebbe">Entebbe</option>
                                </select>
                           </div>
                           
                           <div class="col-xxs-12 col-xs-6 mt alternate">
                            <label for="from">Place:</label>
                            <select class="form-control" id="Destination" name="Destination" wire:model="Destination">
                            @if(!empty($datas))
                                @foreach ($datas as $data)
                                <option value="{{$data['Tos']}}">{{$data['Tos']}}</option>
                                @endforeach
                                                                
                            @endif
                            </select>
                        </div>

                          
                        
                           <div class="col-xxs-12 col-xs-6 mt alternate">
                            <label for="class">No of People:</label>
                            <select  class="form-control" wire:model="NoOfPeople" name="NoOfPeople" id="NoOfPeople">
                                <option value=" ">Select No.Of People</option>
                                <option value="1">1 Person</option>
                                <option value="2">2 People</option>
                                <option value="3">3 People</option>
                                <option value="4">4 People</option>
                                <option value="5">5 People</option>
                                <option value="6">6 People</option>
                                <option value="7">7 People</option>
                                <option value="8">8 People</option>
                                <option value="9">9 People</option>
                                <option value="10">10 People</option>
                            </select>
                          </div>
                          <div class="col-xxs-12 col-xs-6 mt alternate">
                            <label for="from">Price</label>
                            <input type="number" class="form-control" name="tripcost" id="tripcost" wire:model="tripcost" readonly placeholder="Price for this"/>    
                            </div>
                          <div class="col-xxs-12 col-xs-6 mt alternate" style="padding: 10px">
                            @error('bookingnotes') <span class="text-danger">{{ $message }}</span> @enderror
                            <textarea class="form-control" placeholder="Add Other Instruction notes for this Booking" rows="2" name="bookingnotes" wire:model="bookingnotes"></textarea>
                        </div>
        
                        <div class="col-xxs-12 col-xs-6 mt alternate">
                        <label for="from">Pickup Date:</label>
                        <input type="date" class="form-control" name="checkindate" id="checkindate" wire:model="checkindate" placeholder="mm/dd/yyyy"/>
                        </div>
                        <div class="col-xxs-12 col-xs-6 mt alternate">
                        <label for="from">Pickup Time:</label>
                        <input type="text" class="form-control" name="Pickuptime"  id="datetime" wire:model="datetime"  placeholder="HH/MM/SS a"/>
                        </div>
        
                        
                        <div class="col-xxs-12 col-xs-6 mt alternate">
                        <label for="date-end">A One Hour delay or early Arrival is Acceptable for this booking.</label>
                        </div>
                        <div class="col-xxs-12 col-xs-6 mt alternate">
                        <button class="btn btn-primary" type="submit"   style="color:White;">
                            Order Now
                        </button>
                        </div>
                        </form>
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
