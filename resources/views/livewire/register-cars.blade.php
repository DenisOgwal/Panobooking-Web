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
                        <li class="active"><a href="/cars">Car Rental</a></li>
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
  
  <div class="container mt-5 mb-5 p-4 border border-info rounded">
    <form wire:submit.prevent="submit" enctype="multipart/form-data">
      <div class="row">
          <div class="col">
          <h2>Car Details</h2>
          </div>
          <div class="col mr-auto">
              <a href="/"><img src="images/panologo2.png" style="height: 40px;float: right;"></a>
          </div>
      </div>
        <div class="row">
          <div class="col-xxs-12 col-xs-6 mt alternate">
              <label for="exampleInputEmail1" id="labels" class="form-label">Car Name</label>
              @error('carname') <span class="text-danger">{{ $message }}</span> @enderror
            <input type="text" class="form-control" placeholder="Enter Car Name/ Brand or Registration number" name="carname" wire:model="carname">
          </div>
          <div class="col-xxs-12 col-xs-6 mt alternate">
              <label for="exampleInputPassword1" id="labels" class="form-label">Car Category</label>
              @error('carcategory') <span class="text-danger">{{ $message }}</span> @enderror
            <input type="text" class="form-control" placeholder="Please Enter Car Category" name="carcategory" wire:model="carcategory">
          </div>
        </div>
        <div class="row">
          <div class="col-xxs-12 col-xs-6 mt alternate">
              <label for="exampleInputEmail1" id="labels" class="form-label">Car Specifications</label>
              @error('CarSpecs') <span class="text-danger">{{ $message }}</span> @enderror
            <input type="text" class="form-control" placeholder="Please Enter Car Specifications E.g Car Model" name="CarSpecs" wire:model="CarSpecs">
          </div>
          <div class="col-xxs-12 col-xs-6 mt alternate">
              <label for="exampleInputPassword1" id="labels" class="form-label">Ratings</label>
              @error('carratings') <span class="text-danger">{{ $message }}</span> @enderror
            <input type="number" class="form-control" placeholder="What is your Car Ratings from ascale of 3-5?" name="carratings" wire:model="carratings">
          </div>
        </div>
        <div class="row">
          <div class="col-xxs-12 col-xs-6 mt alternate">
              <label for="exampleInputEmail1" id="labels" class="form-label">Company/ Driver</label>
              @error('Carcompany') <span class="text-danger">{{ $message }}</span> @enderror
              <input type="text" class="form-control" placeholder="What is your car hiring company, else fill with your name" name="Carcompany" wire:model="Carcompany">
          </div>
          <div class="col-xxs-12 col-xs-6 mt alternate">
              <label for="exampleInputPassword1" id="labels" class="form-label">Driver Age</label>
              @error('driversage') <span class="text-danger">{{ $message }}</span> @enderror
            <input type="number" class="form-control" placeholder="What is the drivers Age" name="driversage" wire:model="driversage">
          </div>
        </div>
  
        <div class="row">
          <div class="col-xxs-12 col-xs-6 mt alternate">
              <label for="exampleInputEmail1" id="labels" class="form-label">Rental Price per day/ Kampala Drive</label>
              @error('kampaladrive') <span class="text-danger">{{ $message }}</span> @enderror
              <input type="number" class="form-control" placeholder="What is the least price for renting the car for a drive around Kampala?" name="kampaladrive" wire:model="kampaladrive">
          </div>
          <div class="col-xxs-12 col-xs-6 mt alternate">
              <label for="exampleInputPassword1" id="labels" class="form-label">Rental Price per day/ Upcountry Drive</label>
              @error('upcountrydrive') <span class="text-danger">{{ $message }}</span> @enderror
              <input type="number" class="form-control" placeholder="What is the least price for renting the car for a drive outside Kampala?" name="upcountrydrive" wire:model="upcountrydrive">
          </div>
        </div>
        <div class="row">
          <div class="col-xxs-12 col-xs-6 mt alternate">
              <label for="exampleInputEmail1" id="labels" class="form-label" >No. of Passengers</label>
              @error('noofpassengers') <span class="text-danger">{{ $message }}</span> @enderror
            <input type="text" class="form-control" placeholder="How many passengers can your car carry Maximumly?" name="noofpassengers" wire:model="noofpassengers">
          </div>
          <div class="col-xxs-12 col-xs-6 mt alternate">
              <label for="exampleInputPassword1" id="labels" class="form-label">No. of Doors</label>
              @error('noofdoors') <span class="text-danger">{{ $message }}</span> @enderror
            <input type="text" class="form-control" placeholder="How many doors does your car have?" name="noofdoors" wire:model="noofdoors">
          </div>
        </div>
  
        <div class="row">
          <div class="col-xxs-12 col-xs-6 mt alternate">
              <label for="exampleInputEmail1" id="labels" class="form-label">Automatic</label>
              @error('automaticoption') <span class="text-danger">{{ $message }}</span> @enderror
              <select class="form-select" name="automaticoption" id="optionals" wire:model="automaticoption">
                  <option> Select Automatic Options</option>
                  <option Value="Yes">Yes</option>
                  <option Value="No">No</option>
                </select> 
          </div>
          <div class="col-xxs-12 col-xs-6 mt alternate">
              <label for="exampleInputPassword1" id="labels" class="form-label">Laggages</label>
              @error('laggagesoption') <span class="text-danger">{{ $message }}</span> @enderror
              <select class="form-select" name="laggagesoption" id="optionals" wire:model="laggagesoption">
                <option> Select Laggages Options</option>
                <option Value="Yes">Yes</option>
                <option Value="No">No</option>
              </select> 
          </div>
        </div>
  
        <div class="row">
          <div class="col-xxs-12 col-xs-6 mt alternate">
              <label for="exampleInputEmail1" id="labels" class="form-label">Address</label>
              @error('Carlocation') <span class="text-danger">{{ $message }}</span> @enderror
            <input type="text" class="form-control" placeholder="Enter Full address of car parking" name="Carlocation" wire:model="Carlocation">
          </div>
          <div class="col-xxs-12 col-xs-6 mt alternate">
            <label for="exampleInputEmail1" id="labels" class="form-label">Car Main Image</label>
            @error('formFile') <span class="text-danger">{{ $message }}</span> @enderror
            <input class="form-control" type="file" id="formFile" wire:model="formFile">
        </div>
        </div>
  
        <div class="row">    
          <div class="col-xxs-12 col-xs-6 mt alternate">
              <label for="exampleInputPassword1" id="labels" class="form-label">Payment Options</label>
              @error('paymentoption') <span class="text-danger">{{ $message }}</span> @enderror
              <select class="form-select" name="paymentoption" id="optionals" wire:model="paymentoption">
                  <option> Select Payment Options</option>
                  <option Value="Pay At Facility">Pay Cash</option>
                  <option Value="E-Payment">E-Payment</option>
                  <option Value="Pay In the Bank">Pay In the Bank</option>
                </select> 
          </div>
          <div class="col-xxs-12 col-xs-6 mt alternate">
              <label for="exampleInputEmail1" id="labels" class="form-label">Telephone No.</label>
              @error('telphonenumber') <span class="text-danger">{{ $message }}</span> @enderror
              <input type="number" class="form-control" placeholder="What is the contact no. for the facility e.g +2567XXXXXXXX" name="telphonenumber" wire:model="telphonenumber">
          </div>
        </div>
  
        <div class="row">
          <div class="col-xxs-12 col-xs-6 mt alternate">
              <label for="exampleInputEmail1" id="labels" class="form-label">Email address</label>
              @error('email') <span class="text-danger">{{ $message }}</span> @enderror
            <input type="text" class="form-control" placeholder="Enter email" name="email" wire:model="email">
            <div id="emailHelp" class="form-text">We'll never share your email with anyone else.</div>
          </div>
          <div class="col-xxs-12 col-xs-6 mt alternate">
              <label for="exampleInputPassword1" id="labels" class="form-label">Password</label>
              @error('pswd') <span class="text-danger">{{ $message }}</span> @enderror
            <input type="password" class="form-control" placeholder="Enter password" name="pswd" wire:model="pswd">
          </div>
        </div>
        <div class="row">    
            <div class="col-xxs-12 col-xs-6 mt alternate">
                <label for="exampleInputPassword1" id="labels" class="form-label">Description</label>
                @error('description') <span class="text-danger">{{ $message }}</span> @enderror
                <textarea class="form-control" placeholder="Please describe the car" name="description" wire:model="description"></textarea>
            </div>
            
        
  
  
        
          <div class="col-xxs-12 col-xs-6 mt alternate">
      <div class="mb-3 form-check">
        <div id="emailHelp" class="form-text">By signing in or creating an account, you agree with our <a href="/TermsAndConditions">Terms & Conditions</a> and <a href="/privacypolicy">Privacy Statement</a></div>
        <input type="checkbox" class="form-check-input" id="exampleCheck1">
        <label class="form-check-label" for="exampleCheck1">Check This if you agree</label>
      </div> 
    </div>
        </div>
      <button type="submit" class="btn btn-primary">Submit</button>
    </form>
  </div>
  <div>
    &nbsp;
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
  