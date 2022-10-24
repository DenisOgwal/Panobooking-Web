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
                    <li><a href="/ambulance">Ambulance</a></li>
                    <li  class="active"><a href="/blog">Blog</a></li>
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

		<div wire:ignore id="fh5co-blog-section" class="fh5co-section-gray">
			<div class="container">
				<div class="row  mt-5">
					<div class="col-md-8 col-md-offset-2 text-center heading-section animate-box">
						<h3>Our Blog</h3>
						<p>Interest yourself today in reading these blog posts, Our Blogs are Informative and Educative.</p>
					</div>
				</div>
			</div>
			<div class="container">
				<div class="row row-bottom-padded-md">
                    @foreach ($blogs as $blog)
					<div class="col-lg-4 col-md-4 col-sm-6">
						<div class="fh5co-blog animate-box">
							<a href="{{route('BlogDetail',['blogslug'=>$blog->ID])}}"><img class="img-responsive" src="https://panobooking.com/AndroidFiles/BlogsThumbnail/{{$blog->BlogTitle}}.jpg" onerror="this.onerror=null;this.src='images/image-placeholder.png';"/></a>
							<div class="blog-text">
								<div class="prod-title">
									<h3><a href="{{route('BlogDetail',['blogslug'=>$blog->ID])}}">{{$blog->BlogTitle}}</a></h3>
                                    <span class="posted_by">{{$blog->Date}}</span>
                                    <span class="comment"><a href="">{{$blog->comments_count}}<i class="icon-bubble2"></i></a></span>
                                    <p style="text-align:Center;text-align:justify;text-justify:inter-word;">{!!Str::limit($blog->BlogShortDescription,100)!!}</p>
                                    <p><a href="{{route('BlogDetail',['blogslug'=>$blog->ID])}}">Learn More...</a></p>
								</div>
							</div> 
						</div>
					</div>
                    @endforeach
					<div class="clearfix visible-sm-block"></div>
				</div>
                <div class="col-md-12 text-center animate-box">{{ $blogs->links() }}</div>
			</div>
		</div>
		<!-- fh5co-blog-section -->


        <div id="fh5co-testimonial" style="background-image:url('images/img_bg_1.jpg');">
            <div class="container">
                <div class="row animate-box">
                    <div class="col-md-8 col-md-offset-2 text-center fh5co-heading">
                        <h2>Happy Clients</h2>
                    </div>
                </div>
                <div class="row">
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
                            <div class="modal-body">
                           <form wire:submit.prevent="submitfeedback" >
                            @csrf
                                <div class="form-group">
                                    @error('clientname') <span class="text-danger">{{ $message }}</span> @enderror
                                     <input type="text" class="form-control" wire:model.lazy="clientname" id="clientname" placeholder="Add your name here" name="clientname" required>
                                </div>

                                <div class="form-group">
                                    @error('clientemail') <span class="text-danger">{{ $message }}</span> @enderror
                                    <input type="text" class="form-control" id="clientemail" placeholder="Add your email here" name="clientemail" wire:model.lazy="clientemail"  required>
                               </div>

                                <div class="form-group">
                                    @error('clientcompany') <span class="text-danger">{{ $message }}</span> @enderror
                                    <input type="text" class="form-control" id="clientcompany" placeholder="Add your country here.." name="clientcompany" wire:model.lazy="clientcompany" required>
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