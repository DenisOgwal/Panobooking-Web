<div id="fh5co-page">
    
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
    
            <div class="container mt-5 mb-5 p-2 border border-info rounded">
                <div class="row">
                    <div class="col-md-6"  wire:ignore> 
                        <div  class="single-product-image-inner">
                            @foreach ($rooms as $room) 
                            <div wire:ignore class="tab-content">
                                <div class="owl-carousel singleitem-carousel owl-theme">
                                @php $imagesnumbers=$room->PhotoUrl @endphp
                                @for ($j = 1; $j <= (int)$imagesnumbers; $j++)
                                @if ($j==1)
                                <div class="item">
                                <div role="tabpanel" class="tab-pane active" id="{{$j}}">
                                    <a class='venobox' href='' data-gall='gallery' title=''>
                                    <img src='https://panobooking.com/AndroidFiles/RoomImages/{{$room->HotelName}}{{$room->RoomName}}.jpg'  style='height: auto;width:100%;border-radius:10px;' onerror="this.onerror=null;this.src='images/image-placeholder.png';"/>
                                    </a>
                                </div>
                                </div>
            
                                @else
                                <div class="item">
                                <div role="tabpanel" class="tab-pane" id="{{$j}}">
                                    <a class='venobox' href='' data-gall='gallery' title=''>
                                    <img src='https://panobooking.com/AndroidFiles/RoomImages/{{$room->HotelName}}{{$room->RoomName}}{{$j}}.jpg'  style='height: auto;width:100%;border-radius:10px;' onerror="this.onerror=null;this.src='images/image-placeholder.png';"/>
                                    </a>
                                </div>
                                </div>
                                @endif
                                @endfor
                            </div>
                        </div>
                        <div wire:ignore class="owl-carousel imagess-carousel owl-theme">
                            @for ($k = 1; $k <= (int)$imagesnumbers; $k++)
                            @if ($k==1)
                            <div style="Height:120px;Width:120px;" class="item" role="presentation" class="active"><a href="#{{$k}}" aria-controls="{{$k}}" role="tab" data-toggle="tab"><img src="https://panobooking.com/AndroidFiles/RoomImages/{{$room->HotelName}}{{$room->RoomName}}.jpg"  style="height: auto;Width:100%;border-radius:10px;" onerror="this.onerror=null;this.src='images/image-placeholder.png';"/></a></div>
                            @else
                            <div style="Height:120px;Width:120px;" class="item" role="presentation"><a href="#{{$k}}" aria-controls="{{$k}}" role="tab" data-toggle="tab"><img src="https://panobooking.com/AndroidFiles/RoomImages/{{$room->HotelName}}{{$room->RoomName}}{{$k}}.jpg"  style="height: auto;Width:100%;border-radius:10px;" onerror="this.onerror=null;this.src='images/image-placeholder.png';"/></a></div>
                            @endif
                            @endfor
                        </div>
                        @endforeach
                    </div>

                    <div wire:ignore class="col-md-12">
                        @foreach ($rooms as $room) 
                        @if($room->roomdetails==null)
                        @else
                        @if($room->roomdetails['KingBed']=="Yes")
                        <i class="fa fa-bed" aria-hidden="true"> </i>&nbsp;1 King Bed
                        @endif
                        @if($room->roomdetails['QueenBed']=="Yes")
                        <i class="fa fa-bed" aria-hidden="true"></i>&nbsp; 1 Queen Bed
                        @endif
                        @if($room->roomdetails['TwinBed']=="Yes")
                        <i class="fa fa-bed" aria-hidden="true"> </i>&nbsp;1 Twin Bed
                        @endif
                        <i class="fa fa-arrows" aria-hidden="true"></i>&nbsp;{{$room->roomdetails['Feet']}}
                        @if($room->roomdetails['PoolView']=="Yes")
                        <i class="fa-solid fa-water-ladder"></i>&nbsp;Pool View
                        @endif
                        @if($room->roomdetails['CityView']=="Yes")
                        <i class="fa-solid fa-city"></i>&nbsp;City View
                        @endif
                        @if($room->roomdetails['LakeView']=="Yes")
                        <i class="fa-solid fa-water"></i>&nbsp;Lake View
                        @endif
                        @if($room->roomdetails['GardensView']=="Yes")
                        <i class="fa-solid fa-street-view"></i>&nbsp;Gardens View
                        @endif
                        <p>
                        @if($room->roomdetails['Balcony']=="Yes")
                        <i class="fa-solid fa-check icolor"></i>&nbsp;Balcony
                        @endif
                        @if($room->roomdetails['BathTub']=="Yes")
                        <i class="fa-solid fa-check icolor"></i>&nbsp;Bathtub
                        @endif
                        @if($room->roomdetails['AirConditioning']=="Yes")
                        <i class="fa-solid fa-check icolor"></i>&nbsp;Air Conditioning
                        @endif
                        @if($room->roomdetails['AttachedBathroom']=="Yes")
                        <i class="fa-solid fa-check icolor"></i>&nbsp;Attached Bathroom
                        @endif
                        @if($room->roomdetails['FlatScreen']=="Yes")
                        <i class="fa-solid fa-check icolor"></i>&nbsp;Flat Screen
                        @endif
                        @if($room->roomdetails['MiniBar']=="Yes")
                        <i class="fa-solid fa-check icolor"></i>&nbsp;MiniBar 
                        @endif
                        @if($room->roomdetails['FreeWifi']=="Yes")
                        <i class="fa-solid fa-check icolor"></i>&nbsp;Free Wifi 
                        @endif
                        @if($room->roomdetails['Toilet']=="Yes")
                        <i class="fa-solid fa-check icolor"></i>&nbsp;Toilet 
                        @endif
                        @if($room->roomdetails['HotTub']=="Yes")
                        <i class="fa-solid fa-check icolor"></i>&nbsp;HotTub 
                        @endif
                        @if($room->roomdetails['BathRobe']=="Yes")
                        <i class="fa-solid fa-check icolor"></i>&nbsp;BathRobe 
                        @endif
                        @if($room->roomdetails['Safe']=="Yes")
                        <i class="fa-solid fa-check icolor"></i>&nbsp;Safe 
                        @endif
                        @if($room->roomdetails['Towels']=="Yes")
                        <i class="fa-solid fa-check icolor"></i>&nbsp;Towels  
                        @endif
                        @if($room->roomdetails['Desk']=="Yes")
                        <i class="fa-solid fa-check icolor"></i>&nbsp;Desk
                        @endif
                        @if($room->roomdetails['SittingArea']=="Yes")
                        <i class="fa-solid fa-check icolor"></i>&nbsp;Sitting Area 
                        @endif
                        @if($room->roomdetails['Linens']=="Yes")
                        <i class="fa-solid fa-check icolor"></i>&nbsp;Linens 
                        @endif
                            @if($room->roomdetails['Slippers']=="Yes")
                            <i class="fa-solid fa-check icolor"></i>&nbsp;Slippers 
                            @endif
                            @if($room->roomdetails['Telephone']=="Yes")
                            <i class="fa-solid fa-check icolor"></i>&nbsp;Telephone 
                            @endif
                            @if($room->roomdetails['Ironing Facilities ']=="Yes")
                            <i class="fa-solid fa-check icolor"></i>&nbsp;Ironing Facilities  
                            @endif
                            @if($room->roomdetails['Satelite Channels']=="Yes")
                            <i class="fa-solid fa-check icolor"></i>&nbsp;Satelite Channels 
                            @endif
                            @if($room->roomdetails['TeaMaker']=="Yes")
                            <i class="fa-solid fa-check icolor"></i>&nbsp;Tea Maker 
                            @endif
                            @if($room->roomdetails['HairDryer']=="Yes")
                            <i class="fa-solid fa-check icolor"></i>&nbsp;Hair Dryer 
                            @endif
                            @if($room->roomdetails['Carpeted']=="Yes")
                            <i class="fa-solid fa-check icolor"></i>&nbsp;Carpeted
                            @endif
                            @if($room->roomdetails['ElectricKettle']=="Yes")
                            <i class="fa-solid fa-check icolor"></i>&nbsp;Electric Kettle
                            @endif
                            @if($room->roomdetails['WakeupService']=="Yes")
                            <i class="fa-solid fa-check icolor"></i>&nbsp;Wakeup Service
                            @endif
                            @if($room->roomdetails['AlarmClock']=="Yes")
                            <i class="fa-solid fa-check icolor"></i>&nbsp;Alarm Clock
                            @endif
                            @if($room->roomdetails['Dryer']=="Yes")
                            <i class="fa-solid fa-check icolor"></i>&nbsp;Dryer
                            @endif
                            @if($room->roomdetails['Wardrobe']=="Yes")
                            <i class="fa-solid fa-check icolor"></i>&nbsp;Wardrobe
                            @endif
                            @if($room->roomdetails['Toiletries']=="Yes")
                            <i class="fa-solid fa-check icolor"></i>&nbsp;Toiletries
                            @endif
                            @if($room->roomdetails['Refrigerator']=="Yes")
                            <i class="fa-solid fa-check icolor"></i>&nbsp;Refrigerator
                            @endif
                            @if($room->roomdetails['Socket']=="Yes")
                            <i class="fa-solid fa-check icolor"></i>&nbsp;Socket
                            @endif
                            @if($room->roomdetails['MosquitoNet']=="Yes")
                            <i class="fa-solid fa-check icolor"></i>&nbsp;Mosquito Net
                            @endif
                            @if($room->roomdetails['Fan']=="Yes")
                            <i class="fa-solid fa-check icolor"></i>&nbsp;Fan
                            @endif                
                        </p>            
                       @endif      
                        @endforeach
                    </div>
                </div>
                

                  <div class="col-md-6">
                    @foreach ($rooms as $room) 
                    <h3 class="modal-title" style="width:100%;text-align:center; margin-bottom:10px;">Specify Reservation Details for {{$room->RoomName}}</h3>
                    @endforeach
                    <div wire:ignore class="col-md-6">
                        @foreach ($rooms as $room) 
                       <div wire:ignore class='product-price'>
                        Room Price: <span class='new-price'>{!!$currencypick!!}. {{number_format(ceil($room->Price/$currenyvalue),0)}}</span>
                        </div>
                       <div wire:ignore class='deal-product-content'>
                        <p><i class="fa fa-coffee" aria-hidden="true"></i> {{$room->BreakFast}}</p>
                        <div wire:ignore class='product-price'>
                            <i class="fa fa-money" aria-hidden="true"></i>&nbsp;<span class='new-price'>{{$room->RefundPolicy}}</span>
                        </div>
                        </div>
                        @endforeach
                    </div>

                    <div wire:ignore.self class="col-xxs-12 col-xs-12 mt alternate">
                        
                        <form wire:submit.prevent="submitfeedback">
                            @csrf
                        <div class="col-xxs-12 col-xs-12 mt alternate">
                            <div class="input-field">
                                <label for="date-end">Check-in</label>
                                <input type="date" class="form-control" name="checkindate"  wire:model="checkindate" id="checkindate" placeholder="mm/dd/yyyy"/>
                            </div>
                        </div>
                        <script>
                            document.addEventListener('livewire:load', function () {
                                
                                    $('#date-start').on('dp.change', function (e) {
                                
                                        @this.set('date-start', e.target.value);
                                    });
                            });  
                      </script> 
                        <div class="col-xxs-12 col-xs-12 mt alternate">
                            <div wire:ignore class="input-field">
                                <label for="date-end">Check-out</label>
                                <input type="date" class="form-control" name="checkoutdate"  wire:model="checkoutdate" id="checkoutdate" placeholder="mm/dd/yyyy"/>
                            </div>
                        </div>
                        <div class='col-xxs-12 col-xs-12 mt alternate'>
                            @error('Childrenno') <span class="text-danger">{{ $message }}</span> @enderror
                            <label for="Childrenno">No. of Children</label>
                            <input type="nummber" class="form-control" name="Childrenno" id="Childrenno" wire:model="Childrenno" />    
                            </div>
                        <div wire:ignore class='col-xxs-12 col-xs-12 mt alternate'>
                            @error('Destinationquantity') <span class="text-danger">{{ $message }}</span> @enderror
                            <label  wire:ignore for="Destinationquantity">No. Of Rooms ( @foreach ($rooms as $room) {{$room->NoOfRooms}}@endforeach Rooms Of this type Left)</label>
                            <input type="number" class="form-control" name="Destinationquantity" id="Destinationquantity" wire:model="Destinationquantity" placeholder="Please Choose less or equal to No. available"/>    
                        </div>
                        <div class='col-xxs-12 col-xs-12 mt alternate'>
                        @error('DestinationPrice') <span class="text-danger">{{ $message }}</span> @enderror
                        <label for="DestinationPrice">Total Payable</label>
                        <input type="number" class="form-control" name="DestinationPrice" id="DestinationPrice" wire:model="DestinationPrice" readonly placeholder="Total Price for this"/>    
                        </div>
                        <!--<div class='col-xxs-12 col-xs-12 mt alternate'>
                            @error('BookingDescription') <span class="text-danger">{{ $message }}</span> @enderror
                            <label for="DestinationPrice">Notes</label>
                            <textarea type="hidden" class="form-control" name="BookingDescription" id="BookingDescription" wire:model="BookingDescription" placeholder="Please add Your Booking Notes here..."> </textarea>   
                            </div>-->
                        <div class='col-xxs-12 col-xs-12 mt alternate'>
                            <label for="from">Note: You will be contacted shortly after, Your Booking is Received. Thank you.</label>
                        </div>
                        <div class='availability'>
                            <button type="submit" class="btn btn-primary">Reserve Now</button>
                        </div>
                        </form>
                    </div> 
                </div> 
                            
                
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

