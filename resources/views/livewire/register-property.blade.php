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

<div class="container mt-5 mb-5 p-4 border border-info rounded">
<form wire:submit.prevent="submit" enctype="multipart/form-data">
  @csrf
    <div class="row">
        <div class="col">
        <h2>Property Details</h2>
        </div>
        <div class="col mr-auto">
            <a href="/"><img src="images/panologo2.png" style="height: 40px;float: right;"></a>
        </div>
    </div>
      <div class="row">
        <div class="col-xxs-12 col-xs-6 mt alternate">
          <label for="exampleInputEmail1" id="labels" class="form-label">Country</label>
          @error('country') <span class="text-danger">{{ $message }}</span> @enderror
          <input type="text" class="form-control" placeholder="Enter Country of Propeerty Location" name="country" wire:model="country">
        </div>
        <div class="col-xxs-12 col-xs-6 mt alternate">
            <label for="exampleInputPassword1" id="labels" class="form-label">City</label>
            @error('city') <span class="text-danger">{{ $message }}</span> @enderror
          <input type="text" class="form-control" placeholder="Enter City or Town of Property Location" name="city" wire:model="city">
        </div>
      </div>
      <div class="row">
        <div class="col-xxs-12 col-xs-6 mt alternate">
            <label for="exampleInputEmail1" id="labels" class="form-label">Region</label>
            @error('region') <span class="text-danger">{{ $message }}</span> @enderror
          <input type="text" class="form-control" placeholder="Which Region of the country?" name="region" wire:model="region">
        </div>
        <div class="col-xxs-12 col-xs-6 mt alternate">
            <label for="exampleInputPassword1" id="labels" class="form-label">Place Name</label>
            @error('place') <span class="text-danger">{{ $message }}</span> @enderror
          <input type="text" class="form-control" placeholder="What is the place name?" name="place" wire:model="place">
        </div>
      </div>
      <div class="row">
        <div class="col-xxs-12 col-xs-6 mt alternate">
            <label for="exampleInputEmail1" id="labels" class="form-label">Place Type</label>
            @error('placetype') <span class="text-danger">{{ $message }}</span> @enderror
            <select class="form-select" name="placetype" id="optionals" wire:model="placetype">
                <option> Select Facility Category</option>
                <option Value="Hotel">Hotel</option>
                <option Value="Home Stays">Home Stays</option>
                <option Value="Guest Houses">Guest Houses</option>
                <option Value="Apartments">Apartments</option>
                <option Value="Country Homes">Country Homes</option>
                <option Value="Villas">Villas</option>
                <option Value="Homes" >Home Guest House</option>
              </select> 
        </div>
        <div class="col-xxs-12 col-xs-6 mt alternate">
            <label for="exampleInputPassword1" id="labels" class="form-label">Place Ratings</label>
            @error('ratings') <span class="text-danger">{{ $message }}</span> @enderror
          <input type="number" class="form-control" placeholder="What is your Ratings from ascale of 3-5?" name="ratings" wire:model="ratings">
        </div>
      </div>

      <div class="row">
        <div class="col-xxs-12 col-xs-6 mt alternate">
            <label for="exampleInputEmail1" id="labels" class="form-label">Least Price</label>
            @error('leastprice') <span class="text-danger">{{ $message }}</span> @enderror
            <input type="number" class="form-control" placeholder="What is the least price for the rooms you offer?" name="leastprice" wire:model="leastprice">
        </div>
        <div class="col-xxs-12 col-xs-6 mt alternate">
            <label for="exampleInputPassword1" id="labels" class="form-label">Taxes Inclusive</label>
            @error('taxesinclusive') <span class="text-danger">{{ $message }}</span> @enderror
            <select class="form-select" name="taxesinclusive" id="optionals" wire:model="taxesinclusive">
                <option> Select Tax Inclusiveness</option>
                <option Value="Tax Inclusive">Tax Inclusive</option>
                <option Value="Not Inclusive">Not Inclusive</option>
              </select> 
        </div>
      </div>
      <div class="row">
        <div class="col-xxs-12 col-xs-6 mt alternate">
            <label for="exampleInputEmail1" id="labels" class="form-label" >Least Price Short Description</label>
            @error('pricedescription') <span class="text-danger">{{ $message }}</span> @enderror
          <input type="text" class="form-control" placeholder="Talk about the advantages of the price offer." name="pricedescription" wire:model="pricedescription">
        </div>
        <div class="col-xxs-12 col-xs-6 mt alternate">
            <label for="exampleInputPassword1" id="labels" class="form-label">Comment "Slogan"</label>
            @error('slogan') <span class="text-danger">{{ $message }}</span> @enderror
          <input type="text" class="form-control" placeholder="What iss the slogan of the faciity" name="slogan" wire:model="slogan">
        </div>
      </div>

      <div class="row">
        <div class="col-xxs-12 col-xs-6 mt alternate">
            <label for="exampleInputEmail1" id="labels" class="form-label">Credit Card Needed</label>
            @error('creditcard') <span class="text-danger">{{ $message }}</span> @enderror
            <select class="form-select" name="creditcard" id="optionals" wire:model="creditcard">
                <option> Select Credit Card Options</option>
                <option Value="Credit Card Allowed">Credit Card Allowed</option>
                <option Value="Credit Card Not Allowed">Creit Card Not Allowed</option>
              </select> 
        </div>
        <div class="col-xxs-12 col-xs-6 mt alternate">
            <label for="exampleInputPassword1" id="labels" class="form-label">Exact Location</label>
            @error('exactlocation') <span class="text-danger">{{ $message }}</span> @enderror
          <input type="text" class="form-control" placeholder="E.g Plot No., House Name, Block, Street Name" name="exactlocation" wire:model="exactlocation">
        </div>
      </div>

      <div class="row">
        <div class="col-xxs-12 col-xs-6 mt alternate">
            <label for="exampleInputEmail1" id="labels" class="form-label">Bed and BreakFast Info</label>
            @error('bedandbreakfast') <span class="text-danger">{{ $message }}</span> @enderror
          <input type="text" class="form-control" placeholder="Enter Bed and Breakfast info related to the stay at the facility" name="bedandbreakfast" wire:model="bedandbreakfast">
        </div>
        <div class="col-xxs-12 col-xs-6 mt alternate">
            <label for="exampleInputPassword1" id="labels" class="form-label">Facility Main Photo</label>
            @error('formFile') <span class="text-danger">{{ $message }}</span> @enderror
            <input class="form-control" type="file" id="formFile" name="formFile" wire:model="formFile">
        </div>
      </div>

      <div class="row">    
        <div class="col-xxs-12 col-xs-6 mt alternate">
            <label for="exampleInputPassword1" id="labels" class="form-label">Payment Options</label>
            @error('paymentoption') <span class="text-danger">{{ $message }}</span> @enderror
            <select class="form-select" name="paymentoption" id="optionals"  wire:model="paymentoption">
                <option> Select Payment Options</option>
                <option Value="Pay At Facility">Pay At Facility</option>
                <option Value="E-Payment">E-Payment</option>
                <option Value="Pay In the Bank">Pay In the Bank</option>
              </select> 
        </div>
        <div class="col-xxs-12 col-xs-6 mt alternate">
            <label for="exampleInputEmail1" id="labels" class="form-label">Telephone No.</label>
            @error('telephonenumber') <span class="text-danger">{{ $message }}</span> @enderror
            <input type="number" class="form-control" placeholder="What is the contact no. for the facility e.g 2567XXXXXXXX" name="telephonenumber" wire:model="telephonenumber">
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
          <div class="mb-3 form-check">
            <div id="emailHelp" class="form-text">By signing in or creating an account, you agree with our <a href="/TermsAndConditions">Terms & Conditions</a> and <a href="/privacypolicy">Privacy Statement</a></div>
            <input type="checkbox" class="form-check-input" id="exampleCheck1">
            <label class="form-check-label" for="exampleCheck1">Check me out if you agree</label>
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
