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

@foreach ($hotels as $hotels)
<div class="single-product-area section-padding">
    <div class="container">
        <div class="row">
            <div class="col-md-6 col-sm-7">
                <div class="single-product-image-inner">
                    <!-- Tab panes -->
                    <div class="tab-content">
                        @php $imagesnumber=$hotels->PhotoUrl @endphp
                        @for ($i = 1; $i <= (int)$imagesnumber; $i++)
                        @if ($i==1)
                        <div role="tabpanel" class="tab-pane active" id="{{$i}}">
                            <a class='venobox' href='' data-gall='gallery' title=''>
                            <img src='https://panobooking.com/AndroidFiles//GuestHouseImages/{{$hotels->GuestHouseName}}.jpg'  style=' height: auto;width:100%;' onerror="this.onerror=null;this.src='images/image-placeholder.png';"/>
                            </a>
                        </div>
                        @else
                        <div role="tabpanel" class="tab-pane" id="{{$i}}">
                            <a class='venobox' href='' data-gall='gallery' title=''>
                            <img src='https://panobooking.com/AndroidFiles//GuestHouseImages/{{$hotels->GuestHouseName}}{{$i}}.jpg'  style='height: auto;width:100%;'onerror="this.onerror=null;this.src='images/image-placeholder.png';"/>
                            </a>
                        </div>
                        @endif
                        @endfor
                    </div>
                    <!-- Nav tabs -->
                    <div class="owl-carousel images-carousel owl-theme" >
                        @for ($k = 1; $k <= (int)$imagesnumber; $k++)
                        @if ($k==1)
                        <div style="Height:160px;Width:150px;" class="item" role="presentation" class="active"><a href="#{{$k}}" aria-controls="{{$k}}" role="tab" data-toggle="tab"><img src="https://panobooking.com/AndroidFiles//GuestHouseImages/{{$hotels->GuestHouseName}}.jpg"  style="height: auto;Width:100%;" onerror="this.onerror=null;this.src='images/image-placeholder.png';"/></a></div>
                        @else
                        <div style="Height:160px;Width:150px;" class="item" role="presentation"><a href="#{{$k}}" aria-controls="{{$k}}" role="tab" data-toggle="tab"><img src="https://panobooking.com/AndroidFiles//GuestHouseImages/{{$hotels->GuestHouseName}}{{$k}}.jpg"  style="height: auto;Width:100%;" onerror="this.onerror=null;this.src='images/image-placeholder.png';"/></a></div>
                        @endif
                
                        @endfor
                    </div>
                </div>
                <h4 style="font-weight: bold;">Most Popular Facilities</h4>
                <div style="padding:10px;">
                @if($hotels->popularamenities==null)
                @else
                @if($hotels->popularamenities['SwimmingPool']=="Yes")
                <span><i class="fa-solid fa-water-ladder icolor"></i> &nbsp;Swimming Pool</span>
                @endif
                @if($hotels->popularamenities['Shuttle']=="Yes")
                <i class="fa-solid fa-van-shuttle icolor"></i> &nbsp;Shuttle
                @endif
                @if($hotels->popularamenities['Wifi']=="Yes")
                <i class="fa-solid fa-wifi icolor"></i> &nbsp;Wifi
                @endif
                @if($hotels->popularamenities['NoSmokingRoom']=="Yes")
                <i class="fa-solid fa-ban-smoking icolor"></i> &nbsp;No Smoking Room
                @endif
                @if($hotels->popularamenities['FitnessCenter']=="Yes")
                <i class="fa-solid fa-dumbbell icolor"></i>&nbsp;Fitness Center
                @endif
                @if($hotels->popularamenities['Spa']=="Yes")
                <i class="fa-solid fa-spa icolor"></i> &nbsp;Spa
                @endif
                @if($hotels->popularamenities['Restaurant']=="Yes")
                <i class="fa-solid fa-utensils icolor"></i> &nbsp;Restaurant 
                @endif
                @if($hotels->popularamenities['TeaMaker']=="Yes")
                <i class="fa-solid fa-mug-hot icolor"></i> &nbsp;Tea Maker
                @endif
                @if($hotels->popularamenities['Bar']=="Yes")
                <i class="fa-solid fa-champagne-glasses icolor"></i> &nbsp;Bar
                @endif
                @if($hotels->popularamenities['BreakFast']=="Yes")
                <i class="fa-solid fa-plate-wheat icolor"></i>&nbsp;Breakfast 
                @endif
                @if($hotels->popularamenities['Forex']=="Yes")
                <i class="fa-solid fa-money-bill-transfer icolor"></i> &nbsp;Forex  
                @endif
                @endif
                </div>
                <div style="background-color: #d3f9b3;padding:10px;">
                    <p>{{$hotels->CreditCard}} For you to Book</p> <span>we will always send you Emails and App Notifications to confirm your reservations</span>
                </div>
               
            </div>

            
            <div class="col-md-6 col-sm-5">
                <div class="single-product-details">
                  
                    <h2>{{$hotels->GuestHouseName}}</h2>
                    @if($hotels->Rating==5)
                    <div class="list-pro-rating">
                        <i class="fa fa-star icolor"></i>
                        <i class="fa fa-star icolor"></i>
                        <i class="fa fa-star icolor"></i>
                        <i class="fa fa-star icolor"></i>
                        <i class="fa fa-star icolor"></i>
                    </div>
                    @elseif ($hotels->Rating==4)
                    <div class="list-pro-rating">
                        <i class="fa fa-star icolor"></i>
                        <i class="fa fa-star icolor"></i>
                        <i class="fa fa-star icolor"></i>
                        <i class="fa fa-star icolor"></i>
                        <i class="fa fa-star"></i>
                    </div>
                    @elseif ($hotels->Rating==3)
                    <div class="list-pro-rating">
                        <i class="fa fa-star icolor"></i>
                        <i class="fa fa-star icolor"></i>
                        <i class="fa fa-star icolor"></i>
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star"></i>
                    </div>
                    @elseif ($hotels->Rating==2)
                    <div class="list-pro-rating">
                        <i class="fa fa-star icolor"></i>
                        <i class="fa fa-star icolor"></i>
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star"></i>
                    </div>
                    @else
                    <div class="list-pro-rating">
                        <i class="fa fa-star icolor"></i>
                        <i class="fa fa-star icolor"></i>
                        <i class="fa fa-star icolor"></i>
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star"></i>
                    </div>
                    @endif
                    <div class="col-md-12" style="display: inline-block; margin-bottom:20px;">
                        <div class="col-md-6" ><p>{{$hotels->ExactLocation}} </p></div>
                        @if(session()->has('pano_client_data'))
                        <a  href="{{route('CompleteHomeGuestBooking',['homeguestfinalslug'=>$hotels->IDs])}}"><div class="col-md-6" ><button class="btn btn-primary" >Reserve</button></div></a>
                        @else
                        <div class="col-md-6" ><button class="btn btn-primary"  data-toggle="modal" data-target="#modal-login">Login To Reserve</button></div>   
                        @endif
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




                    <div style="height:auto;">
                    {!!$hotels->Coordinates!!}
                    </div>
                        <div class="row" style="padding:20px;margin-top:20px; background-color:#1E90FF" >  
                            <div class="col-md-12" >            
                        <form name="search_hotel_form" action="{{route('HomeGuestSearch')}}" method="GET">
                                            
                            <div class="col-xxs-12 col-xs-12 mt">
                                <div class="input-field">
                                    <label for="from">Destination:</label>
                                    <input type="text" class="form-control" name="Destination" id="fDestination" value="{{ request()->input('Destination') }}" {{--wire:model="from_city"--}} placeholder="Fortportal, Uganda"/>
                                </div>
                            </div>
                            
                            <div class="col-sm-12 mt">
                                <section>
                                    <label for="class">Rooms:</label>
                                    <select class="cs-select cs-skin-border" name="no_of_rooms" id="no_of_rooms" {{--wire:model="no_of_rooms"--}} >
                                        <option value="" disabled selected>1 Room</option>
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
                            <div class="col-xxs-12 col-xs-6 mt">
                                <section>
                                    <label for="class">Adult:</label>
                                    <select class="cs-select cs-skin-border" name="no_of_adults" id="no_of_adults" {{--wire:model="no_of_adults"--}}>
                                        <option value="" disabled selected>1 Adult</option>
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
                            <div class="col-xxs-12 col-xs-6 mt">
                                <section>
                                    <label for="class">Children:</label>
                                    <select class="cs-select cs-skin-border" name="no_of_children" id="no_of_children" {{--wire:model="no_of_children"--}}>
                                        <option value="" disabled selected>0</option>
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
                                <input type="submit" class="btn btn-primary btn-block" name="Search_HomeGuest" value="Search Home Guest House">
                            </div>
                             </form>

                    </div>
                </div> 
                </div>
            </div>
        </div>
    </div>
</div> 

        <div class="single-product-area section-padding" style ="overflow-x:auto;">
            <div class="container">
        <div class="row" >
            <div class="col-md-12" >
                <div class="p-details-tab-content">
                    <div class="p-details-tab">
                        <ul class="p-details-nav-tab" role="tablist">
                            <li role="presentation"><a href="#more-info" aria-controls="more-info" role="tab" data-toggle="tab">Description</a></li>
                            <li role="presentation"><a href="#facilities" aria-controls="facilities" role="tab" data-toggle="tab">Facilities</a></li>
                            <li role="presentation" class="active"><a href="#houserules" aria-controls="houserules" role="tab" data-toggle="tab">House Rules</a></li>
                            <li role="presentation"><a href="#surroundings" aria-controls="surroundings" role="tab" data-toggle="tab">Surroundings</a></li>
                        </ul>
                    </div>
                    <div class="clearfix"></div>
                    <div class="tab-content review">
                        <div role="tabpanel" class="tab-pane" id="more-info">
                            <div class="col-md-4 col-sm-12 col-xs-12" style="background-color:#e8effa;">
                                <h4 style="text-align:center;padding:20px; color:black">Property Highlights</h4>
                                <p style="font-weight: bold; color:black">Breakfast Info:</p>
                                <p>{{$hotels->BedBreakFast}}<p>
                                <p style="font-weight: bold;">Rooms with:</p>
                                @if($hotels->roomwith==null)
                                @else
                                @if($hotels->roomwith['CityView']=="Yes")
                                <p><i class="fa-solid fa-city"></i> &nbsp;City View</p>
                                @endif
                                @if($hotels->roomwith['PoolView']=="Yes")
                                <p><i class="fa-solid fa-water-ladder"></i> &nbsp;Pool View</p>
                                @endif
                                @if($hotels->roomwith['LakeView']=="Yes")
                                <p><i class="fa-solid fa-water"></i>&nbsp;Lake View</p>
                                @endif
                                @if($hotels->roomwith['GardenView']=="Yes")
                                <p><i class="fa-solid fa-check"></i>&nbsp;Garden View</p>
                                @endif
                                @if($hotels->roomwith['TerraceView']=="Yes")
                                <p><i class="fa-solid fa-check"></i>&nbsp;Terrace View</p>
                                @endif
                                @if($hotels->roomwith['LandMarkView']=="Yes")
                                <p><i class="fa-solid fa-landmark"></i>&nbsp;LandMark View</p>
                                @endif
                                @if($hotels->roomwith['MountainView']=="Yes")
                                <p><i class="fa-solid fa-mountain"></i>&nbsp;Mountain View</p>
                                @endif
                                @if($hotels->roomwith['StreetView']=="Yes")
                                <p><i class="fa-solid fa-street-view"></i>&nbsp;Street View</p>
                                @endif
                                @endif
                                <p><i class="fa-solid fa-square-parking icolor"></i> &nbsp;{{$hotels->Parking}}<p>
                            </div>
                            <div class="col-md-8 col-sm-12 col-xs-12" style ="text-align: justify;text-justify: inter-word;">
                                @if($hotels->propertydescription==null)
                                @else
                                @foreach($hotels->propertydescription as $propertydes)
                                <p >{{ $propertydes->Description}}</p>
                                @endforeach
                                @endif
                            </div>
                           
                        </div>
                        <div role="tabpanel" class="tab-pane" id="facilities">
                            <div class="col-md-4 col-sm-12 col-xs-12">
                            <p style="font-weight: bold;"><i class="fa-solid fa-sink"></i>&nbsp;Bathroom:</p>
                            @if($hotels->bathroomdetail==null)
                            @else
                            @foreach($hotels->bathroomdetail as $bathroomdetail)
                            <p > <i class="fa-solid fa-check icolor"></i>{{ $bathroomdetail->Amenity}}</p>
                            @endforeach
                            @endif

                            <p style="font-weight: bold;"><i class="fa-solid fa-bed"></i>&nbsp;Bedroom:</p>
                            @if($hotels->bedroomdetail==null)
                            @else
                            @foreach($hotels->bedroomdetail as $bedroomdetail)
                            <p > <i class="fa-solid fa-check icolor"></i>{{ $bedroomdetail->Amenity}}</p>
                            @endforeach
                            @endif

                            <p style="font-weight: bold;"><i class="fa-solid fa-binoculars"></i>&nbsp;Views:</p>
                            @if($hotels->viewdetail==null)
                            @else
                            @foreach($hotels->viewdetail as $viewdetail)
                            <p > <i class="fa-solid fa-check icolor"></i>{{ $viewdetail->Amenity}}</p>
                            @endforeach
                            @endif
                            <p style="font-weight: bold;"><i class="fa-solid fa-campground"></i>&nbsp;Outdoor:</p>
                            @if($hotels->outdoordetail==null)
                            @else
                            @foreach($hotels->outdoordetail as $outdoordetail)
                            <p > <i class="fa-solid fa-check icolor"></i>{{ $outdoordetail->Amenity}}</p>
                            @endforeach
                            @endif
                            <p style="font-weight: bold;"><i class="fa-solid fa-kitchen-set"></i>&nbsp;Kitchen:</p>
                            @if($hotels->kitchendetail==null)
                            @else
                            @foreach($hotels->kitchendetail as $kitchendetail)
                            <p > <i class="fa-solid fa-check icolor"></i>{{ $kitchendetail->Amenity}}</p>
                            @endforeach
                            @endif
                        </div>

                        <div class="col-md-4 col-sm-12 col-xs-12">
                            <p style="font-weight: bold;"><i class="fa-solid fa-tachograph-digital"></i>&nbsp;Media & Technology:</p>
                            @if($hotels->mediaandtechdetail==null)
                            @else
                            @foreach($hotels->mediaandtechdetail as $mediaandtechdetail)
                            <p > <i class="fa-solid fa-check icolor"></i>{{$mediaandtechdetail->Amenity}}</p>
                            @endforeach
                            @endif

                            <p style="font-weight: bold;"><i class="fa-solid fa-bowl-food"></i>&nbsp;Food & Drink:</p>
                            @if($hotels->fooddetail==null)
                            @else
                            @foreach($hotels->fooddetail as $fooddetail)
                            <p > <i class="fa-solid fa-check icolor"></i>{{ $fooddetail->Amenity}}</p>
                            @endforeach
                            @endif

                            <p style="font-weight: bold;"><i class="fa-solid fa-square-parking"></i>&nbsp;Parking:</p>
                            @if($hotels->parkingdetail==null)
                            @else
                            @foreach($hotels->parkingdetail as $parkingdetail)
                            <p > <i class="fa-solid fa-check icolor"></i>{{ $parkingdetail->Amenity}}</p>
                            @endforeach
                            @endif
                            <p style="font-weight: bold;"><i class="fa-solid fa-bus-simple"></i>&nbsp;Transportation:</p>
                            @if($hotels->transportationdetail==null)
                            @else
                            @foreach($hotels->transportationdetail as $transportationdetail)
                            <p > <i class="fa-solid fa-check icolor"></i>{{ $transportationdetail->Amenity}}</p>
                            @endforeach
                            @endif
                            <p style="font-weight: bold;"><i class="fa-solid fa-bus-simple"></i>&nbsp;Accessibility:</p>
                            @if($hotels->accessibilitydetail==null)
                            @else
                            @foreach($hotels->accessibilitydetail as $accessibilitydetail)
                            <p > <i class="fa-solid fa-check icolor"></i>{{ $accessibilitydetail->Amenity}}</p>
                            @endforeach
                            @endif
                        </div>


                        <div class="col-md-4 col-sm-12 col-xs-12">
                            <p style="font-weight: bold;"><i class="fa-solid fa-soap"></i>&nbsp;Cleaning Services:</p>
                            @if($hotels->cleaningdetail==null)
                            @else
                            @foreach($hotels->cleaningdetail as $cleaningdetail)
                            <p > <i class="fa-solid fa-check icolor"></i>{{$cleaningdetail->Amenity}}</p>
                            @endforeach
                            @endif

                            <p style="font-weight: bold;"><i class="fa-solid fa-business-time"></i>&nbsp;Business Facilities:</p>
                            @if($hotels->businessfacilitydetail==null)
                            @else
                            @foreach($hotels->businessfacilitydetail as $businessfacilitydetail)
                            <p > <i class="fa-solid fa-check icolor"></i>{{ $businessfacilitydetail->Amenity}}</p>
                            @endforeach
                            @endif

                            <p style="font-weight: bold;"><i class="fa-solid fa-lock"></i>&nbsp;Safety and Security:</p>
                            @if($hotels->safetyandsecuritydetail==null)
                            @else
                            @foreach($hotels->safetyandsecuritydetail as $safetyandsecuritydetail)
                            <p > <i class="fa-solid fa-check icolor"></i>{{ $safetyandsecuritydetail->Amenity}}</p>
                            @endforeach
                            @endif
                            <p style="font-weight: bold;"><i class="fa-solid fa-spa"></i>&nbsp;Spa:</p>
                            @if($hotels->spadetail==null)
                            @else
                            @foreach($hotels->spadetail as $spadetail)
                            <p > <i class="fa-solid fa-check icolor"></i>{{ $spadetail->Amenity}}</p>
                            @endforeach
                            @endif
                        </div>
                        </div>
                        <div role="tabpanel" class="tab-pane active" id="houserules">
                            <div class="col-md-12 col-sm-12 col-xs-12" >
                               
                                <table class="table-data-sheet">
                                    <tbody>
                                        <tr class="odd">
                                            <td><i class="fa-solid fa-timeline"></i>&nbsp;Check-in</td>
                                            <td> @if($hotels->houserulesdetail==null)
                                                @else
                                                <p >{{$hotels->houserulesdetail['CheckIn']}}</p>
                                                <p >Guests are required to show a photo ID at Check-in</p>
                                                @endif</td>
                                        </tr>
                                        <tr class="even">
                                            <td><i class="fa-solid fa-timeline"></i>&nbsp;Check-out</td>
                                            <td> @if($hotels->houserulesdetail==null)
                                                @else
                                                <p >{{$hotels->houserulesdetail['CheckOut']}}</p>
                                               
                                                @endif</td>
                                        </tr>
                                        <tr class="odd">
                                            <td><i class="fa-solid fa-circle-info"></i>&nbsp;Cancellation/prepayment </td>
                                            <td> @if($hotels->houserulesdetail==null)
                                                @else
                                                @if($hotels->houserulesdetail['PaymentCancelation']=="No")
                                                <p >Payment Cancellation/Prepayment is not allowed. </p>
                                                <p >However Cancellation and prepayment policies vary according to accommodations type. Check what conditions might apply to each option when making your selection. </p>
                                               @else
                                               <p >Payment Cancellation/Prepayment is allowed. </p>
                                               <p >However Cancellation and prepayment policies vary according to accommodations type. Check what conditions might apply to each option when making your selection. </p>
                                                @endif
                     
                                                @endif</td>
                                        </tr>
                                        <tr class="even">
                                            <td><i class="fa-solid fa-children"></i>&nbsp;Children & Beds</td>
                                            <td> <p>Children Policies</p>
                                                @if($hotels->childrenpolicydetail==null)
                                                @else
                                                @foreach($hotels->childrenpolicydetail as $childrenpolicydetail)
                                                <p > <i class="fa-solid fa-check icolor"></i>{{ $childrenpolicydetail->Policy}}</p>
                                                @endforeach
                                                @endif
                                                <p>Crib and extra bed policies</p>
                                                @if($hotels->childrenextrabeddetail->isEmpty())
                                                <p><i class="fa-solid fa-check icolor"></i>There's no capacity for cribs at this property.</p>
                                                <p><i class="fa-solid fa-check icolor"></i>This property doesn't offer extra beds.</p>
                                                @else
                                                <table>
                                                @foreach($hotels->childrenextrabeddetail as $childrenextrabeddetail)
                                                <tr><td>{{ $childrenextrabeddetail->YearsRange}}</td><td>{{ $childrenextrabeddetail->ExtraCondition}}</td></tr>
                                                @endforeach
                                            </table>
                                                <p><i class="fa-solid fa-check icolor"></i>Additional fees are not calculated automatically in the total cost and will have to be paid for separately during your stay.</p>
                                                <p><i class="fa-solid fa-check icolor"></i>The maximum number of extra beds allowed depends on the room you choose. Double-check the maximum capacity for the room you selected.</p>
                                                <p><i class="fa-solid fa-check icolor"></i>All cribs and extra beds are subject to availability.</p>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr class="odd">
                                            <td><i class="fa-solid fa-child"></i>&nbsp;Age restriction </td>
                                            <td> @if($hotels->houserulesdetail==null)
                                                @else
                                                @if($hotels->houserulesdetail['AgeRestriction']=="No")
                                                <p >There's no age requirement for check-in </p>
                                               @else
                                               <p >There's  age requirement for check-in. Contact us for Further Details </p>
                                                @endif
                     
                                                @endif</td>
                                        </tr>
                                        <tr class="even">
                                            <td><i class="fa-solid fa-cat"></i>&nbsp;Pets</td>
                                            <td> @if($hotels->houserulesdetail==null)
                                                @else
                                                @if($hotels->houserulesdetail['Pets']=="No")
                                                <p >Pets are not allowed. </p>
                                               @else
                                               <p >Pets are allowed at Premises.</p>
                                                @endif
                     
                                                @endif</td>
                                        </tr>
                                        <tr class="odd">
                                            <td><i class="fa-solid fa-credit-card"></i>&nbsp;Credit Cards accepted at this Facility</td>
                                            <td> @if($hotels->houserulesdetail==null)
                                                @else
                                                @if($hotels->houserulesdetail['CardAcceptability']=="No")
                                                <p >Credit Card Payment not Accepted at Facility. </p>
                                               @else
                                               <p >Credit Card Payment Accepted at Facility.</p>
                                                @endif
                     
                                                @endif</td>
                                        </tr>
                                    </tbody>
                               </table>
                            </div>
                        </div>
                        <div role="tabpanel" class="tab-pane" id="surroundings">
                            <div class="col-md-4 col-sm-12 col-xs-12">
                                <p style="font-weight: bold;"><i class="fa-brands fa-nfc-directional"></i>&nbsp;What's nearby:</p>
                                @if($hotels->nearbyplacesdetail==null)
                                @else
                                @foreach($hotels->nearbyplacesdetail as $nearbyplacesdetail)
                                <p > <i class="fa-solid fa-check icolor"></i>{{$nearbyplacesdetail->Place}}&nbsp;( {{$nearbyplacesdetail->Distance}})</p>
                                @endforeach
                                @endif

                                <p style="font-weight: bold;"><i class="fa-solid fa-store"></i>&nbsp;Nearby Markets:</p>
                                @if($hotels->nearbymarketsdetail==null)
                                @else
                                @foreach($hotels->nearbymarketsdetail as $nearbymarketsdetail)
                                <p > <i class="fa-solid fa-check icolor"></i>{{$nearbymarketsdetail->Place}}&nbsp;( {{$nearbymarketsdetail->Distance}})</p>
                                @endforeach
                                @endif
                                <p style="font-weight: bold;"><i class="fa-solid fa-cart-arrow-down"></i>&nbsp;Nearby SuperMarkets:</p>
                                @if($hotels->nearbysupermarketsdetail==null)
                                @else
                                @foreach($hotels->nearbysupermarketsdetail as $nearbysupermarketsdetail)
                                <p > <i class="fa-solid fa-check icolor"></i>{{$nearbysupermarketsdetail->Place}}&nbsp;( {{$nearbysupermarketsdetail->Distance}})</p>
                                @endforeach
                                @endif
                            </div>
                            <div class="col-md-4 col-sm-12 col-xs-12">
                                <p style="font-weight: bold;"><i class="fa-solid fa-magnet"></i>&nbsp;Top attractions:</p>
                                @if($hotels->topattractionsdetail==null)
                                @else
                                @foreach($hotels->topattractionsdetail as $topattractionsdetail)
                                <p > <i class="fa-solid fa-check icolor"></i>{{$topattractionsdetail->Place}}&nbsp;( {{$topattractionsdetail->Distance}})</p>
                                @endforeach
                                @endif

                                <p style="font-weight: bold;"><i class="fa-solid fa-image"></i></i>&nbsp;Natural Beauty :</p>
                                @if($hotels->naturalbeautydetail==null)
                                @else
                                @foreach($hotels->naturalbeautydetail as $naturalbeautydetail)
                                <p > <i class="fa-solid fa-check icolor"></i>{{$naturalbeautydetail->Place}}&nbsp;( {{$naturalbeautydetail->Distance}})</p>
                                @endforeach
                                @endif
                            </div>
                            <div class="col-md-4 col-sm-12 col-xs-12">
                                <p style="font-weight: bold;"><i class="fa-solid fa-utensils"></i>&nbsp;Restaurants & cafes :</p>
                                @if($hotels->nearbyrestaurantsdetail==null)
                                @else
                                @foreach($hotels->nearbyrestaurantsdetail as $nearbyrestaurantsdetail)
                                <p > <i class="fa-solid fa-check icolor"></i>{{$nearbyrestaurantsdetail->Place}}&nbsp;( {{$nearbyrestaurantsdetail->Distance}})</p>
                                @endforeach
                                @endif

                                <p style="font-weight: bold;"><i class="fa-solid fa-plane"></i>&nbsp;Closest Airports:</p>
                                @if($hotels->closestairportdetail==null)
                                @else
                                @foreach($hotels->closestairportdetail as $closestairportdetail)
                                <p > <i class="fa-solid fa-check icolor"></i>{{$closestairportdetail->Airport}}&nbsp;( {{$closestairportdetail->Distance}})</p>
                                @endforeach
                                @endif

                                <p style="font-weight: bold;"><i class="fa-solid fa-bus"></i></i>&nbsp;Public transit:</p>
                                @if($hotels->publictransportdetail==null)
                                @else
                                @foreach($hotels->publictransportdetail as $publictransportdetail)
                                <p > <i class="fa-solid fa-check icolor"></i>{{$publictransportdetail->TransportType}}&nbsp;- {{$publictransportdetail->TransportName}}&nbsp;( {{$publictransportdetail->Distance}})</p>
                                @endforeach
                                @endif
                            </div>
                        </div>
                        <!--<div role="tabpanel" class="tab-pane" id="data">
                            <table class="table-data-sheet">
                                <tbody>
                                    <tr class="odd">
                                        <td>Compositions</td>
                                        <td>Cotton</td>
                                    </tr>
                                    <tr class="even">
                                        <td>Styles</td>
                                        <td>Casual</td>
                                    </tr>
                                    <tr class="odd">
                                        <td>Properties</td>
                                        <td>Short Sleeve</td>
                                    </tr>
                                </tbody>
                           </table>
                        </div>
                        <div role="tabpanel" class="tab-pane" id="reviews">
                            <div id="product-comments-block-tab">
                                <a href="#" class="comment-btn"><span>Be the first to write your review!</span></a>
                            </div>
                        </div>-->
                    </div>
                </div>
            </div>
        </div>  
    </div>
</div>
@endforeach


    
  <div class="shopping-area section-padding">
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="shop-tab-area">
                        <div id="menu1" class="tab-pane fade in active">                            
                            <div class="modal fade" id=" ">
                                <div class="modal-dialog modal-lg">
                                  <div class="modal-content" style="background-color:#e8effa;" >
                                    <div class="modal-header">
                                      <h3 class="modal-title">Specifiy Reservation Details for </h3>
                                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                      </button> 
                                    </div>
                                    <div class="modal-body">
                                        <div class="row">
                                        <div class="col-md-6 col-sm-7">
                                            <div class="single-product-image-inner">
                                                <!-- Tab panes -->
                                                <div class="tab-content">
                                
                                                </div>
                                                <!-- Nav tabs -->    
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-sm-7">
                                            
                                           <div class='product-price'>
                                            Room Price: <span class='new-price'>UGX.</span>
                                            </div>
                                           <div class='deal-product-content'>
                                            <p><i class="fa fa-coffee" aria-hidden="true"></i> </p>
                                            <div class='product-price'>
                                                <i class="fa fa-money" aria-hidden="true"></i>&nbsp;<span class='new-price'></span>
                                            </div>
                                            <div class='product-price'>
                                                <label for="from">No Of Rooms</label>
                                                <select class="cs-select cs-skin-border" name="Norooms" id="Norooms">
                                                    <option value="1" disabled selected>1</option>
                                                    <option value="2">2</option>
                                                    <option value="3">3</option>
                                                    <option value="4">4</option>
                                                    <option value="5">5</option>
                                                </select>
                                            </div>
                                            <div class='product-price'>
                                            <label for="from">Total Payable</label>
                                            <input type="number" class="form-control" name="Destination" id="from-place" placeholder="Total Price for this"/>    
                                            </div>
                                            <div class='product-price'>
                                                <label for="from">Note: You will be contacted shortly after, Your Booking is Received. Thank you.</label>
                                            </div>
                                            <div class='availability'>
                                                <span><a href="#" data-toggle="modal" data-target="#">Reserve Now</a></span>
                                            </div>
                                        </div>
                                        </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer justify-content-between">
                                      <button type="button" class="btn btn-outline-light" data-dismiss="modal">Close</button>
                                    </div>
                                  </div>
                                  <!-- /.modal-content -->
                                </div>
                                <!-- /.modal-dialog -->
                              </div>

                    
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="single-product-area section-padding">
        <div class="container">
            <div class="row">
    <div class='col-xs-12 col-sm-12 col-md-12' style="background-color: #e8effa; padding:10px;">
        <h3 style="font-weight: bold;">{{$hotels->reasonsforstaydetail->count()}} reasons to choose {{$hotels->GuestHouseName}}</h3>
        @if($hotels->reasonsforstaydetail==null)
        @else
        <div style="padding:10px;display: flex;"> 
        @foreach($hotels->reasonsforstaydetail as $reasonsforstaydetail)
        <div style="padding:20px;"> <i class="fa-solid fa-check icolor"></i>&nbsp;{{$reasonsforstaydetail->Reason }}</div>
        @endforeach
        </div>
        @endif
    </div>
</div>
</div>
</div>
<!-- Shop Area End -->
<!-- Related Product Area End -->
<div class="related-product-area">
    <h2 class="section-title">Related Propertys</h2>
  <!-- Shop Area Start -->
    <div class="container">
        <div class="row">
            <div class="owl-carousel category-carousel owl-theme" >
                @foreach ($hotelCat as $hotelCat)
                <div class="item" >
                     <div class='col-md-12'>
                         <div class='single-banner'>
                             <div class='product-wrapper'>
                                 <a href='{{route('HotelSingle',['hotelslug'=>$hotelCat->IDs])}}' class='single-banner-image-wrapper'>
                                     <img alt='' src='https://panobooking.com/AndroidFiles/GuestHouseImages/{{$hotelCat->GuestHouseName}}.jpg' style='Height:150px; width:100%;' onerror="this.onerror=null;this.src='images/image-placeholder.png';"/>
                                     <div class='price'><span>UGX.</span>{{$hotelCat->Prices}}</div>
                                     <!--<div class='rating-icon'>
                                                     <i class='fa fa-star icolor'></i>
                                                     <i class='fa fa-star icolor'></i>
                                                     <i class='fa fa-star icolor'></i>
                                                     <i class='fa fa-star'></i>
                                                     <i class='fa fa-star'></i>
                                                 </div>-->
                                 </a>
                                 <div class='product-description'>
                                     <div class='functional-buttons'>
                                         <a href='{{route('HotelSingle',['hotelslug'=>$hotelCat->IDs])}}' title='Book Now'>
                                             <i class='fa fa-shopping-cart'></i>
                                         </a>
                                         <a href='#' title='Quick View'>
                                             <i class='fa fa-compress'></i>
                                         </a>
                                     </div>
                                 </div>
                             </div>
                             <div class='banner-bottom3 text-center'>
                                 <a href='{{route('HotelSingle',['hotelslug'=>$hotelCat->IDs])}}'>{{$hotelCat->GuestHouseName}}</a>
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
