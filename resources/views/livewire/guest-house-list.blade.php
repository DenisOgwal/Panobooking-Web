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


        <!-- Shop Area Start -->
        <div class="shopping-area section-padding">
            <div class="container">
                <div class="row">
                    <div class="col-md-3 col-sm-3 col-xs-12">
                        <div class="shop-widget">
                            <div class="shop-widget-top">
                                <aside class="widget widget-categories">
                                    <h2 class="sidebar-title text-center">Destinations</h2>
                                    <ul class="sidebar-menu">
                                        @foreach ($firstdestinations as $destination )
									    <li>
                                        <a href='{{route('HomeGuestsList',['destinationslug'=>$destination[0]->City])}}'>
                                            <i class='fa fa-angle-double-right'></i>{{ $destination[0]->City }}<span>({{$destination[0]->cnt}})</span>
                                        </a>
                                        </li>
                                        @endforeach                                             
                                    </ul>
                                </aside> 
                               <!--   <aside class="widget shop-filter">
                                    <h2 class="sidebar-title text-center">PRICE SLIDER</h2>
                                    <div class="info-widget">
                                        <div class="price-filter">
                                            <div id="slider-range"></div>
                                            <div class="price-slider-amount">
                                                <input type="text" id="amount" name="price"  placeholder="Add Your Price" />
                                                <div class="widget-buttom">
                                                    <input type="submit"  value="Filter"/>  
                                                    <input type="reset" value="CLEAR" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </aside>                          
                            
                          <div class="shop-widget-bottom">
                                <aside class="widget widget-tag">
                                    <h2 class="sidebar-title">POPULAR TAG</h2>
                                    <ul class="tag-list">
                                        <li>
                                            <a href="#">e-book</a>
                                        </li>
                                        <li>
                                            <a href="#">writer</a>
                                        </li>
                                        <li>
                                            <a href="#">book’s</a>
                                        </li>
                                        <li>
                                            <a href="#">eassy</a>
                                        </li>
                                        <li>
                                            <a href="#">nice</a>
                                        </li>
                                        <li>
                                            <a href="#">author</a>
                                        </li>
                                    </ul>
                                </aside>
                                <aside class="widget widget-seller">
                                    <h2 class="sidebar-title">TOP SELLERS</h2>
                                       <div class='single-seller'>
                                           <div class='seller-img'>
                                               <img src='img/' style='Height:100px; width:100px' alt=''/>
                                           </div>
                                           <div class='seller-details'>
                                               <a href=''><h5>Product Name</h5></a>
                                               <h5>Shs. </h5>
                                               <ul>
                                                   <li><i class='fa fa-star icolor'></i></li>
                                                   <li><i class='fa fa-star icolor'></i></li>
                                                   <li><i class='fa fa-star icolor'></i></li>
                                                   <li><i class='fa fa-star icolor'></i></li>
                                                   <li><i class='fa fa-star icolor'></i></li>
                                               </ul>
                                           </div>
                                       </div>
                                </aside>-->
                            </div>
                        </div>
                    </div>
                    <div class="col-md-9 col-sm-9 col-xs-12">
                        <div class="shop-tab-area">
                            <div class="shop-tab-list">
                                <div class="shop-tab-pill pull-left">
                                    <ul>
                                        <li class="active" id="left"><a data-toggle="pill" href="#home"><i class="fa fa-th"></i><span>Grid</span></a></li>
                                        <li><a data-toggle="pill" href="#menu1"><i class="fa fa-th-list"></i><span>List</span></a></li>
                                    </ul>
                                </div>
                                <div class="shop-tab-pill pull-right">
                                    <ul>
                                        <li class="product-size-deatils">
                                            <div class="show-label">
                                                <label>Show : </label>
                                                <select name="post_per_page" wire:model.live="pagesize">
                                                    <option value="24" >24</option>
                                                    <option value="24">24</option>
                                                    <option value="12">12</option>
                                                    <option value="6">6</option>
                                                    <option value="3">3</option>
                                                </select>
                                            </div>
                                        </li>
                                        <li class="product-size-deatils">
                                            <div class="show-label">
                                                <label><i class="fa fa-sort-amount-asc"></i>Sort by : </label>
                                                <select name="orderby" wire:model="sorting">
                                                    <option value="default">Default</option>
                                                    <option value="Name">Name</option>
                                                    <option value="Price">Price</option>
                                                </select>
                                            </div>
                                        </li>

                                       <!-- <li class="shop-pagination"><a href="#">1</a></li>
                                        <li class="shop-pagination"><a href="#">2</a></li>
                                        <li class="shop-pagination"><a href="#"><i class="fa fa-caret-right"></i></a></li>-->
                                    </ul>
                                </div>
                            </div>
                            <div class="tab-content">
                                <div class="row tab-pane fade in active" id="home">
                                    @foreach ($hotels as $hotel)
                                       <div class='shop-single-product-area'>
                                           <div class='col-md-4 col-sm-6'>
											   <div class='single-banner'>
												   <div class='product-wrapper'>
													   <a href='{{route('HomeGuestSingle',['homeguestslug'=>$hotel->IDs])}}' class='single-banner-image-wrapper'>
														   <img  src="https://panobooking.com/AndroidFiles/GuestHouseImages/{{$hotel->GuestHouseName}}.jpg" style="Height:250px; width:100%;" onerror="this.onerror=null;this.src='images/image-placeholder.png';"/>
														   <div class='price'><span>UGX.</span> {{ $hotel->Prices}}</div>
													</a>
													   <div class='product-description'>
														   <div class='functional-buttons'>
															   <a href='{{route('HomeGuestSingle',['homeguestslug'=>$hotel->IDs])}}' title='Reserve'>
																   <i class='fa fa-shopping-cart'></i>
															   </a>
															    <a href='' title='Quick view' data-toggle='modal' data-target='#productModal'>
                                                               <i class='fa fa-compress'></i>
                                                           </a>
														   </div>
													   </div>
												   </div>
												   <div class='banner-bottom text-center'>
													   <div class='banner-bottom-title'>
														   <a href='{{route('HomeGuestSingle',['homeguestslug'=>$hotel->IDs])}}'>{{$hotel->GuestHouseName}}</a>
													   </div>
                                                       @if($hotel->Rating==5)
                                                       <div class="rating-icon">
                                                           <i class="fa fa-star icolor"></i>
                                                           <i class="fa fa-star icolor"></i>
                                                           <i class="fa fa-star icolor"></i>
                                                           <i class="fa fa-star icolor"></i>
                                                           <i class="fa fa-star icolor"></i>
                                                       </div>
                                                       @elseif ($hotel->Rating==4)
                                                       <div class="rating-icon">
                                                           <i class="fa fa-star icolor"></i>
                                                           <i class="fa fa-star icolor"></i>
                                                           <i class="fa fa-star icolor"></i>
                                                           <i class="fa fa-star icolor"></i>
                                                           <i class="fa fa-star"></i>
                                                       </div>
                                                       @elseif ($hotel->Rating==3)
                                                       <div class="rating-icon">
                                                           <i class="fa fa-star icolor"></i>
                                                           <i class="fa fa-star icolor"></i>
                                                           <i class="fa fa-star icolor"></i>
                                                           <i class="fa fa-star"></i>
                                                           <i class="fa fa-star"></i>
                                                       </div>
                                                       @elseif ($hotel->Rating==2)
                                                       <div class="rating-icon">
                                                           <i class="fa fa-star icolor"></i>
                                                           <i class="fa fa-star icolor"></i>
                                                           <i class="fa fa-star"></i>
                                                           <i class="fa fa-star"></i>
                                                           <i class="fa fa-star"></i>
                                                       </div>
                                                       @else
                                                       <div class="rating-icon">
                                                           <i class="fa fa-star icolor"></i>
                                                           <i class="fa fa-star icolor"></i>
                                                           <i class="fa fa-star icolor"></i>
                                                           <i class="fa fa-star"></i>
                                                           <i class="fa fa-star"></i>
                                                       </div>
                                                       @endif
												   </div>
											   </div>
                                           </div>
                                       </div>
                                       @endforeach
                                </div>
								
                                <div id="menu1" class="tab-pane fade">
                                    @foreach ($hotels as $hotel)
                                       <div class='row'>
                                           <div class='single-shop-product'>
                                               <div class='col-xs-12 col-sm-5 col-md-4'>
                                                   <div class='left-item'>
                                                       <a href='{{route('HomeGuestSingle',['homeguestslug'=>$hotel->IDs])}}' title='{{$hotel->GuestHouseName}}'>
                                                           <img src='https://panobooking.com/AndroidFiles/GuestHouseImages/{{$hotel->GuestHouseName}}.jpg'  style='Height:250px;width:100%;' onerror="this.onerror=null;this.src='images/image-placeholder.png';"/>
                                                       </a>
                                                   </div>
                                               </div>
                                               <div class='col-xs-12 col-sm-7 col-md-8'>
                                                   <div class='deal-product-content'>
                                                       <h4>
                                                           <a href='{{route('HomeGuestSingle',['homeguestslug'=>$hotel->IDs])}}' title='{{$hotel->GuestHouseName}}'>{{$hotel->GuestHouseName}}</a>
                                                       </h4>
                                                       <div class='product-price'>
                                                           <span class='new-price'>  UGX. {{ $hotel->Prices}}</span>
                                                           <span class='old-price'></span>
                                                       </div>

                                                       @if($hotel->Rating==5)
                                                       <div class="list-pro-rating">
                                                           <i class="fa fa-star icolor"></i>
                                                           <i class="fa fa-star icolor"></i>
                                                           <i class="fa fa-star icolor"></i>
                                                           <i class="fa fa-star icolor"></i>
                                                           <i class="fa fa-star icolor"></i>
                                                       </div>
                                                       @elseif ($hotel->Rating==4)
                                                       <div class="list-pro-rating">
                                                           <i class="fa fa-star icolor"></i>
                                                           <i class="fa fa-star icolor"></i>
                                                           <i class="fa fa-star icolor"></i>
                                                           <i class="fa fa-star icolor"></i>
                                                           <i class="fa fa-star"></i>
                                                       </div>
                                                       @elseif ($hotel->Rating==3)
                                                       <div class="list-pro-rating">
                                                           <i class="fa fa-star icolor"></i>
                                                           <i class="fa fa-star icolor"></i>
                                                           <i class="fa fa-star icolor"></i>
                                                           <i class="fa fa-star"></i>
                                                           <i class="fa fa-star"></i>
                                                       </div>
                                                       @elseif ($hotel->Rating==2)
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
                                   
                                                       <p>{{ $hotel->Description}}</p>
                                                       <div class='availability'>
                                                           <span>Available</span>
                                                           <span><a href='{{route('HomeGuestSingle',['homeguestslug'=>$hotel->IDs])}}'>Reserve</a></span>
                                                       </div>
                                                   </div>
                                               </div>
                                           </div>  
                                       </div>
                                       @endforeach
                                       
                                </div>
                                <div class="col-md-12 text-center animate-box">
                                {{$hotels->links()}}
                            </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Shop Area End -->
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

