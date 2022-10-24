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
                    <li><a href="/">Stays</a></li>	
                    <li class="active"><a href="/TourPackages">Tour Packages</a></li>	
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

@foreach ($tourismsites as $tourismpackage)

    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <div class="single-product-details" style="margin-top:20px;">
                    <div class='availability'>
                <div class="owl-carousel availabilitycategory-carousel owl-theme">
                <div class="item">
                        <h2>{{$tourismpackage->PackageName}}</h2>
                </div>
                <div class="item">
                        @if(session()->has('pano_client_data'))
                        <form wire:submit.prevent="submitbooking">
                        <button  class="btn btn-primary btn-outline btn-lg">Book Now </button>
                        </form>
                        @else
                        <button  class="btn btn-primary btn-outline btn-lg" data-toggle="modal" data-target="#modal-login">Login To Book</button>    
                        @endif      
                </div>
            </div>
            <div class="owl-carousel availabilitycategory-carousel owl-theme">
                <div class="item">
                    <h3>Destinations Visted</h3>
                <p style="text-align:justify;">{!!$tourismpackage->DestinationsVisted!!}</p>
                </div>
                <div class="item">
                    <h3>Arrangement</h3>
                <ul>
                <li>Travel Date: {{$tourismpackage->TravelDate}}</li>
                <li>Travel Hours:{{$tourismpackage->TravelHours}}</li>
                <li>No. of Days: {{$tourismpackage->TripDays}}</li>
            
                </ul>
                <div class="single-product-price">
                    <h4>Package Price &nbsp;</h4>
                   <h4>{!!$currencypick!!} 
                       {{number_format(ceil($tourismpackage->PackageFees2/$currenyvalue),0)}}</h4>
                   </div>
                </div>
            </div>

            <div class="owl-carousel availabilitycategory-carousel owl-theme">
                <div class="item">
                    <h3>Extra Activites</h3>
                <p style="text-align:justify;">{!!$tourismpackage->ExtraActivites!!}</p>
                </div>
                <div class="item">
                    <h3>Services Offered</h3>
                <p style="text-align:justify;">{!!$tourismpackage->ServicesOffered!!}</p>
                </div>
            </div>
        </div>
    </div>
</div>

            <div class="col-md-6 col-sm-7">
                <div class="single-product-image-inner">
                    <!-- Tab panes -->
                    <div class="tab-content">
                        <div class="owl-carousel singleitem-carousel owl-theme">
                        @php $imagesnumber=$tourismpackage->PhotoUri @endphp
                        @for ($i = 1; $i <= (int)$imagesnumber; $i++)
                        @if ($i==1)
                        <div class="item">
                        <div role="tabpanel" class="tab-pane active" id="{{$i}}">
                            <a class='venobox' href='' data-gall='gallery' title=''>
                            <img src='https://panobooking.com/AndroidFiles/TourPackages/{{$tourismpackage->TravelCompany}}{{$tourismpackage->PackageName}}.jpg'  style='height: auto;width:100%;border-radius:10px;' onerror="this.onerror=null;this.src='images/place-2.jpg';"/>
                            </a>
                        </div>
                    </div>
                        @else
                        <div class="item">
                        <div role="tabpanel" class="tab-pane" id="{{$i}}">
                            <a class='venobox' href='' data-gall='gallery' title=''>
                            <img src='https://panobooking.com/AndroidFiles/TourPackages/{{$tourismpackage->TravelCompany}}{{$tourismpackage->PackageName}}{{$i}}.jpg'  style='height: auto;width:100%;border-radius:10px;' onerror="this.onerror=null;this.src='images/place-2.jpg';"/>
                            </a>
                        </div>
                    </div>
                        @endif
                        @endfor
                    </div>    
                </div>
            </div>
        </div>

        <div wire:ignore.self class="modal fade" id="modal-login">
            <div class="modal-dialog modal-sm">
              <div class="modal-content bg-danger" style="background-color:#e8effa;">
                <div class="modal-header">
                  <h4 class="modal-title">LOGIN</h4>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body">
               <form wire:submit.prevent="signin" >
                @csrf
                    
                    <div class="form-group">
                        @error('clientemail') <span class="text-danger">{{ $message }}</span> @enderror
                        <input type="text" class="form-control" id="clientemail" placeholder="Add your email here" name="clientemail" wire:model.defer="clientemail"  required>
                   </div>

                    <div class="form-group">
                        @error('password') <span class="text-danger">{{ $message }}</span> @enderror
                        <input type="password" class="form-control" id="password" placeholder="Add your password here" wire:model.defer="password" required>
                    </div>

                
                    <div class="form-group">        
                   <button type="submit" name="saveupdate" class="btn btn-success justify-content-between">Login</button>
                    </div>

                </form>
            </div>
                
              </div>
              <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
          </div>
          <!-- /.modal -->
        @endforeach
    </div>
        </div>


    <div class="related-product-area">
    <h2 class="section-title">Related Tour Packages</h2>
  <!-- Shop Area Start -->
    <div class="container">
        <div class="row">
            <div class="owl-carousel carscategory-carousel owl-theme" >
                @foreach ($tourismsitess as $tourpack)
                <div class="item" >
                     <div class='col-md-12'>
                         <div class='single-banner'>
                             <div class='product-wrapper'>
                                 <a href='{{route('TourPackageDetails',['searchslug'=>$tourpack->IDs])}}' class='single-banner-image-wrapper'>
                                     <img alt='' src='https://panobooking.com/AndroidFiles/TourPackages/{{$tourpack->TravelCompany}}{{$tourpack->PackageName}}.jpg ' style='Height:200px; width:100%;border-radius:10px;' onerror="this.onerror=null;this.src='images/place-2.jpg';"/>
                                     <div class='price'><span></div>
                                 </a>
                                 <div class='product-description'>
                                     <div class='functional-buttons'>
                                         <a href='{{route('TourPackageDetails',['searchslug'=>$tourpack->IDs])}}' title='Order Now'>
                                             <i class='fa fa-shopping-cart'></i>
                                         </a>
                                         <a href='#' title='Quick View'>
                                             <i class='fa fa-compress'></i>
                                         </a>
                                     </div>
                                 </div>
                             </div>
                             <div class='banner-bottom3 text-center'>
                                 <a href='{{route('TourPackageDetails',['searchslug'=>$tourpack->IDs])}}'>{{$tourpack->PackageName}}</a>
                             </div>
                         </div>
                     </div>
        </div>
        @endforeach
    </div>
            </div>
        </div>
    </div>
<!-- Shop Area End -->
<!-- Related Product Area End -->

<!-- Single Product Area End -->


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
</div>