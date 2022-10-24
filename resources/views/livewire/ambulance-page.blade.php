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
                    <li><a href="/TourPackages">Tour Packages</a></li>	
                    <li><a href="/taxis">Airport Taxi</a></li>
                    <li><a href="/cars">Car Rental</a></li>
                    <li  class="active"><a href="/ambulance">Ambulance</a></li>
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
<div class="fh5co-hero">
    <div class="fh5co-overlay"></div>
    <div class="fh5co-cover" data-stellar-background-ratio="0.5" style="background-image: url('images/cover_bg_1.jpg');">
        <div class="desc">
            <div class="container">
                <div wire:ignore class="row justify-content-start">
                    <div class="col-sm-5 col-md-5">
                        <div class="tabulation animate-box">

                          <!-- Nav tabs -->
                           <ul class="nav nav-tabs" role="tablist" data-tabs="tabs">
                            <li role="presentation">
                                   <a href="#hotels" aria-controls="hotels" role="tab" data-toggle="tab">Hotels</a>
                              </li>
                              <li role="presentation">
                                  <a href="#cars" aria-controls="cars" role="tab" data-toggle="tab">Cars</a>
                              </li>   
                              <li role="presentation">
                                   <a href="#airport" aria-controls="airport" role="tab" data-toggle="tab">Airport Taxi</a>
                              </li>
                                <li role="presentation"   class="active">
                                   <a href="#ambulance" aria-controls="ambulance" role="tab" data-toggle="tab">Ambulance</a>
                              </li>
                           </ul>

                           <!-- Tab panes -->
                            <div class="tab-content">

                                <div role="tabpanel" class="tab-pane" id="hotels">
                                    <div class="row">
                                       
                                        <form name="search_hotel_form" action="{{route('HotelSearch')}}" method="GET">
                                            
                                            <div class="col-xxs-12 col-xs-6 mt alternate">
                                                <section>
                                                    <label for="class">Rooms:</label>
                                                    <select class="cs-select cs-skin-border" name="no_of_rooms" id="no_of_rooms" >
                                                        <option value="1" disabled selected>1 Room</option>
                                                        <option value="1">1 Room</option>
                                                        <option value="2">2 Rooms</option>
                                                        <option value="3">3 Rooms</option>
                                                        <option value="4">4 Rooms</option>
                                                        <option value="5">5 Rooms</option>
                                                        <option value="6">6 Rooms</option>
                                                        <option value="7">7 Rooms</option>
                                                        <option value="8">8 Rooms</option>
                                                        <option value="9">9 Rooms</option>
                                                        <option value="10">10 Rooms</option>
                                                    </select>
                                                </section>
                                            </div>
                                           
                                            <div class="col-xxs-12 col-xs-6 mt alternate">
                                                <section>
                                                    <label for="class">Adult:</label>
                                                    <select class="cs-select cs-skin-border" name="no_of_adults" id="no_of_adults" {{--wire:model="no_of_adults"--}}>
                                                        <option value="1" disabled selected>1 Adult</option>
                                                        <option value="1">1 Adult</option>
                                                        <option value="2">2 Adults</option>
                                                        <option value="3">3 Adults</option>
                                                        <option value="4">4 Adults</option>
                                                        <option value="5">5 Adults</option>
                                                        <option value="6">6 Adults</option>
                                                        <option value="7">7 Adults</option>
                                                        <option value="8">8 Adults</option>
                                                        <option value="9">9 Adults</option>
                                                        <option value="10">10 Adults</option>
                                                    </select>
                                                </section>
                                            </div>
                                            <div class="col-xxs-12 col-xs-6 mt alternate">
                                             <div class="input-field">
                                                 <label for="from">Destination:</label>
                                                 <input type="text" class="form-control" name="from_city" id="from_city" value="{{ request()->input('from_city') }}" placeholder="Fort portal"/>
                                             </div>
                                             </div>
                                            <div class="col-xxs-12 col-xs-6 mt alternate">
                                                <section>
                                                    <label for="class">Children:</label>
                                                    <select class="cs-select cs-skin-border" name="no_of_children" id="no_of_children" {{--wire:model="no_of_children"--}}>
                                                        <option value="0" disabled selected>0</option>
                                                        <option value="0">0 Children</option>
                                                        <option value="1">1 Child</option>
                                                        <option value="2">2 Children</option>
                                                        <option value="3">3 Children</option>
                                                        <option value="4">4 Children</option>
                                                        <option value="5">5 Children</option>
                                                        <option value="6">6 Children</option>
                                                        <option value="7">7 Children</option>
                                                        <option value="8">8 Children</option>
                                                        <option value="9">9 Children</option>
                                                        <option value="10">10 Children</option>
                                                    </select>
                                                </section>
                                            </div>
                                      
                                       <div class="col-xs-12">
                                           <input type="submit" class="btn btn-primary btn-block" name="Search_Hotel" value="Search Hotel">
                                       </div>
                                        </form>
                                   </div>
                                </div>


                             <div role="tabpanel" class="tab-pane" id="cars">
                                <div class="row">
                                    <form name="CarSearch" action="{{route('CarSearch')}}" >
                                        <div class="col-xxs-12 col-xs-6 mt">
                                            <div class="input-field">
                                                <label for="from">Pickup Location:</label>
                                                <input type="text" class="form-control" id="pickuplocation"  name="pickuplocation" value="{{ request()->input('pickuplocation') }}" placeholder="Current Location"/>
                                            </div>
                                        </div>
                                        
                                        <div class="col-xxs-12 col-xs-6 mt alternate">
                                            <div class="input-field">
                                                <label for="date-start">Pickup Date:</label>
                                                <input type="date" class="form-control" name="date-start"  id="PickupDate" placeholder="mm/dd/yyyy"/>
                                            </div>
                                        </div>
                                        <div class="col-xxs-12 col-xs-6 mt alternate">
                                            <div class="input-field">
                                                <label for="class">Duration:</label>
                                                <input type="number" class="form-control" name="Duration" id="Duration" placeholder="Duration in Days e.g 1"/>
                                            </div>
                                        </div>
    
                                        <div class="col-xxs-12 col-xs-6 mt">
                                            <section>
                                                <label for="class">No of Cars:</label>
                                                <select class="cs-select cs-skin-border" name="NoofCars" id="NoofCars">
                                                    <option value="" disabled selected>1 Car</option>
                                                    <option value="1">1 Car</option>
                                                    <option value="2">2 Cars</option>
                                                    <option value="3">3 Cars</option>
                                                    <option value="4">4 Cars</option>
                                                    <option value="5">5 Cars</option>
                                                    <option value="6">6 Cars</option>
                                                    <option value="7">7 Cars</option>
                                                    <option value="8">8 Cars</option>
                                                    <option value="9">9 Cars</option>
                                                    <option value="10">10 Cars</option>
                                                    <option value="11">11 Cars</option>
                                                    <option value="12">12 Cars</option>
                                                    <option value="13">13 Cars</option>
                                                    <option value="14">14 Cars</option>
                                                    <option value="15">15 Cars</option>
                                                </select>
                                            </section>
                                        </div>
                                                           
                                    <div class="col-xs-12">
                                        <input type="submit" class="btn btn-primary btn-block" name="Search_Car"  value="Search Car">
                                    </div>
                                    </form>
                                </div>
                             </div>


                             <div role="tabpanel" class="tab-pane" id="airport">
                                <div class="row">
                                    <form name="TaxiSearch" action="{{route('TaxiSearch')}}" >
                                        <div class="col-xxs-12 col-xs-6 mt alternate">
                                            <label for="class">Pick me up</label>
                                            <select class="cs-select cs-skin-border" id="pickuppoint" name="pickuppoint" >
                                                <option value=" ">Select Pick up Point</option>
                                                <option value="From Airport">From Airport</option>
                                                <option value="To Airport">To Airport</option>
                                            </select>
                                        </div>
                                        <div class="col-xxs-12 col-xs-6 mt alternate">
                                            <section>
                                                <label for="class">Airport:</label>
                                                <select class="cs-select cs-skin-border" id="airport" name="airport" >
                                                    <option value="Entebbe" disabled selected>Entebbe</option>
                                                    <option value="Entebbe">Entebbe</option>
                                                </select>
                                            </section>
                                        </div>
                                       
                                        <div class="col-xxs-12 col-xs-6 mt alternate">
                                            <div class="input-field">
                                                <label for="date-end">Pickup Date:</label>
                                                <input type="date" class="form-control" name="PickupDate" id="PickupDate"  placeholder="mm/dd/yyyy"/>                                    
                                            </div>
                                        </div>
                                        <div class="col-xxs-12 col-xs-6 mt alternate">
                                        <label for="from">Place:</label>
                                        <select class="cs-select cs-skin-border" id="Destination" name="Destination">
                                            @if(!empty($datas))
                                                @foreach ($datas as $data)
                                                <option value="{{$data['Tos']}}">{{$data['Tos']}}</option>
                                                @endforeach
                                                                                
                                            @endif
                                        </select>    
                                    </div>
                                
                                                            
                                    <div class="col-xs-12">
                                        <input type="submit" class="btn btn-primary btn-block" name="Search_Taxi" value="Search Taxi">
                                    </div>
                                    </form>
                                </div>
                             </div>


                             <div role="tabpanel" class="tab-pane  active" id="ambulance">
                                <div class="row">
                                    <form name="TaxiSearch" action="{{route('AmbulanceSearch')}}" >
                                    <div class="col-xxs-12 col-xs-6 mt">
                                        <div class="input-field">
                                            <label for="from">Enter Area:</label>
                                            <input type="text" class="form-control" id="Area" name="Area" value="{{ request()->input('Area') }}" placeholder="Area"/>
                                        </div>
                                    </div>
                                    <div class="col-xxs-12 col-xs-6 mt">
                                            <div class="input-field">
                                                <label for="from">Pickup Point:</label>
                                                <input type="text" class="form-control"  name="pickuppoint" id="pickuppoint" placeholder="Pickup Point?"/>
                                            </div>
                                    </div>
                                    <div class="col-xxs-12 col-xs-12 mt">
                                        <div class="input-field">
                                            <label for="date-end">Reason:</label>
                                            <textarea class="form-control" rows="2" id="reason" name="reason" placeholder="Enter Reason for Request"></textarea>
                                        </div>
                                    </div>
                                                                                       
                                    <div class="col-xs-12">
                                        <input type="submit" class="btn btn-primary btn-block" name="Search_Ambulance" value="Search Ambulance">
                                    </div>
                                    </form>
                                </div>
                             </div>
                            </div>

                        </div>
                    </div>
                        @if($offeredroom->count()>0)
                        <div id="mydesc" class="col-sm-7 col-sm-push-1 col-md-5 col-md-push-1">
                            @foreach ($offeredroom as $offeredroom)
                            <p>Offered by <a href="panobooking.com" target="_blank" class="fh5co-site-name">panobooking.com</a></p>
                            <h3>Readily Available Ambulance</h3>
                            <span class="price">{{$offeredroom->TaxiNo}}</span>
                            <p><a class="btn btn-info btn-lg" href="{{route('AmbulanceDetails',['ambulanceslug'=>$offeredroom->IDs])}}">Call Now</a></p>
                            @endforeach
                        </div>
                        @else
                        <div id="mydesc" class="col-sm-7 col-sm-push-1 col-md-7 col-sm-push-1">
                            <p>Ranked by <a href="panobooking.com" target="_blank" class="fh5co-site-name">panobooking.com</a></p>
                            <h3>Featured Ambulance on Panobooking.com</h3>
                            @foreach ($hoteloffered as $hoteloffered)
                            <span class="price">{{$hoteloffered->TaxiNo}}</span>
                            <p><a class="btn btn-info btn-lg" href="{{route('AmbulanceDetails',['ambulanceslug'=>$hoteloffered->IDs])}}">Call Now</a></p>
                            @endforeach
                        </div>
                        @endif

                </div>
            </div>
        </div>
    </div>

</div>

<div wire:ignore id="fh5co-tours" class="fh5co-section-gray">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-md-offset-2 text-center heading-section animate-box">
                <h3>Available Ambulances</h3>
                <p>Call Ambulance, an emergency aid to your next hospital. We care about your health needs.</p>
            </div>
        </div>
        <div class="row">
            <div class="owl-carousel taxifeatured-carousel owl-theme">
            @foreach ($Ambulances as $Ambulance)
            <div class="item" >
            <div class="col-md-4 col-sm-6 fh5co-tours animate-box" style="width:100%;" data-animate-effect="fadeIn">
                <div href="{{route('AmbulanceDetails',['ambulanceslug'=>$Ambulance->IDs])}}"><img src="https://panobooking.com/AndroidDriver/FileUpload/{{$Ambulance->TaxiNo}}.jpg" class="img-responsive" onerror="this.onerror=null;this.src='images/image-placeholder.png';"/>
                    <div class="desc">
                        <span></span>
                        <h3>{{ $Ambulance->TaxiNo}}</h3>
                        <span>{{ $Ambulance->CarSpec}}</span>
                        <a class="btn btn-primary btn-outline" href="{{route('AmbulanceDetails',['ambulanceslug'=>$Ambulance->IDs])}}">Call Now <i class="icon-arrow-right22"></i></a>
                    </div>
                </div>
            </div>   
        </div>
            @endforeach
        </div>
    </div>
    </div>
</div>

<div wire:ignore id="fh5co-destination">
    <div class="tour-fluid">
        <div class="row">
            <div class="col-md-12">
                <ul id="fh5co-destination-list" class="animate-box">
                    @foreach ($firstdestinations as $destination)
                    <li class="one-forth text-center" style="background-image: url('https://panobooking.com/AndroidDriver/FileUpload/CarCategory/{{$destination->AmbulanceCategory}}.jpg'); ">
                        <a href="{{route('AmbulanceList',['ambulancecategoriesslug'=>$destination->AmbulanceCategory])}}">
                            <div class="case-studies-summary">
                                <h2>{{$destination->AmbulanceCategory}}</h2>
                            </div>
                        </a>
                    </li>
                   @endforeach
                    <li class="one-half text-center">
                        <div class="title-bg">
                            <div class="case-studies-summary">
                                <h2>All Available Categories</h2>
                                <span><a href="{{route('AmbulanceList')}}">View All Categories</a></span>
                            </div>
                        </div>
                    </li>
                    @foreach ($seconddestinations as $destinations)
                    <li class="one-forth text-center" style="background-image: url('https://panobooking.com/AndroidDriver/FileUpload/CarCategory/{{$destinations->AmbulanceCategory}}.jpg'); ">
                        <a href="{{route('AmbulanceList',['ambulancecategoriesslug'=>$destinations->AmbulanceCategory])}}">
                            <div class="case-studies-summary">
                                <h2>{{$destinations->AmbulanceCategory}}</h2>
                            </div>
                        </a>
                    </li>
                    @endforeach 
                </ul>		
            </div>
        </div>
    </div>
</div>
<div wire:ignore id="fh5co-blog-section" class="fh5co-section-gray">
    <div class="container">
        <div class="row  justify-content-center">
            <div class="col-md-8 col-md-offset-2 text-center heading-section animate-box">
                <h3>Recent From Blog</h3>
                <p>Interest yourself today in reading these blog posts, Our Blogs are Informative and Educative.</p>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row">
            <div class="owl-carousel blog-carousel owl-theme" style="color:black;">
            @foreach ($blogs as $blog)
            <div class="item" >
            <div class="col-lg-4 col-md-4 col-sm-6" style="width:100%;">
                <div class="fh5co-blog animate-box">
                    <a href="{{route('BlogDetail',['blogslug'=>$blog->ID])}}"><img class="img-responsive" src="https://panobooking.com/AndroidFiles/BlogsThumbnail/{{$blog->BlogTitle}}.jpg" onerror="this.onerror=null;this.src='images/image-placeholder.png';"/></a>
                    <div class="blog-text">
                        <div class="prod-title">
                            <h3><a href="{{route('BlogDetail',['blogslug'=>$blog->ID])}}">{{$blog->BlogTitle}}</a></h3>
                            <span class="posted_by">{{$blog->Date}}</span>
                            <span class="comment"><a href="">{{$blog->comments_count}}<i class="icon-bubble2"></i></a></span>
                            <p>{!!Str::limit($blog->BlogShortDescription,100)!!}</p>
                            <p><a href="{{route('BlogDetail',['blogslug'=>$blog->ID])}}">Learn More...</a></p>
                        </div>
                    </div> 
                </div>
            </div>
        </div>
            @endforeach
        </div>
    </div>
    </div>
</div>
<!-- fh5co-blog-section -->
<div  wire:ignore.self id="fh5co-testimonial" style="background-image:url('images/img_bg_1.jpg');">
    <div class="container">
        <div class="row  justify-content-center animate-box">
            <div class="col-md-8 col-md-offset-2 text-center fh5co-heading">
                <h2>Happy Clients</h2>
            </div>
        </div>
        <div class="row" style="padding-bottom: 10px;">
            <div wire:ignore class="owl-carousel feedback-carousel owl-theme" style="color:black;">
                @foreach ($clientfeedbacks as $clientfeedback)
                <div class="item" >
                <div class="col-md-12">
                    <div class="box-testimony animate-box">
                        <blockquote>
                            <span class="quote"><span><i class="icon-quotes-right"></i></span></span>
                            <p>&ldquo;{{$clientfeedback->FeedBack}}&rdquo;</p>
                        </blockquote>
                        <p class="author">{{$clientfeedback->ClientName}}, {{$clientfeedback->ClientJob}} <a href="https://panobooking.com/" target="_blank">{{$clientfeedback->ClientCompany}}</a></p>
                    </div>  
                </div>
                </div>
               @endforeach
                </div>

               <div class="col-md-12 text-center animate-box">
                <p><button class="btn btn-primary btn-outline btn-lg" data-toggle="modal" data-target="#modal-danger">Add Feedback</button></p>
                </div>

                <div wire:ignore.self class="modal fade" id="modal-danger">
                    <div class="modal-dialog">
                      <div class="modal-content bg-danger" style="background-color:#e8effa;">
                        <div class="modal-header">
                          <h4 class="modal-title">Add Feedback</h4>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                          </button>
                        </div>
                        <div wire:ignore.self class="modal-body">
                       <form wire:submit.prevent="submitfeedback" >
                        @csrf
                            <div class="form-group">
                                @error('clientname') <span class="text-danger">{{ $message }}</span> @enderror
                                 <input type="text" class="form-control"  id="clientname" placeholder="Add your name here" name="clientname" wire:model.lazy="clientname" required>
                            </div>

                            <div class="form-group">
                                @error('clientemail') <span class="text-danger">{{ $message }}</span> @enderror
                                <input type="text" class="form-control" id="clientemail" placeholder="Add your email here" name="clientemail" wire:model.lazy="clientemail"  required>
                           </div>

                            <div class="form-group">
                                @error('clientcompany') <span class="text-danger">{{ $message }}</span> @enderror
                                <input type="text" class="form-control" id="clientcompany" placeholder="Add your Country here" name="clientcompany" wire:model.lazy="clientcompany" required>
                            </div>

                            <div class="form-group">
                                @error('clientfeedback') <span class="text-danger">{{ $message }}</span> @enderror
                                <textarea  class="form-control" cols="30" rows="2" placeholder="Your Feedback..." id="clientfeedback"  name="clientfeedback"  required="required" wire:model.lazy="clientfeedback" ></textarea>
                            </div>

                            <div class="form-group">        
                           <button type="submit" name="saveupdate" class="btn btn-success justify-content-between">Submit Feedback</button>
                            </div>

                        </form>
                    </div>
                        <div class="modal-footer justify-content-between">
                          <button type="button" class="btn btn-outline-light" data-dismiss="modal">Close</button>
                        </div>
                      </div>
                      <!-- /.modal-content -->
                    </div>
                    <!-- /.modal-dialog -->
                  </div>
                  <!-- /.modal -->
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
</div>