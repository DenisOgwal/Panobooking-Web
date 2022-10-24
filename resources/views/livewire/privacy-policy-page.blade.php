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
                <h3>PANOBOOKING PRIVACY POLICY</h3>
                <p>First things first, your privacy is important to Panobooking and will always be. This Privacy Policy covers how we collect, use, disclose, transfer and store your information. Please take a few minutes to acquaint yourself with our privacy practices and let us know if you have any questions. By visiting Panobooking sites or using the Panobooking App, you are accepting the practices described in this Privacy Policy. </p>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12  animate-box">
                <h2>A. OVERVIEW</h2>
                <p>This notice describes how Panobooking and its affiliates collect and use personal data. This notice applies to all users of our apps, websites, features, or other services anywhere in the world, unless covered by a separate privacy notice. </p>
                <ul style="list-style: none;">
                <li>a)	What kind of personal data does Panobooking.com collect? </li>
            <li>b)	How is personal information about customers used? </li>
            <li>c)  What personal information about customers does Panobooking gather?</li>
            <li>d)	What about cookies?</li>
            <li>e)	What is the accuracy of the personal data provided to Panobooking?</li>
            <li>f)	How secure is information shared with Panobooking? </li>
            <li>g)	What are your rights? </li>
            <li>h)	How does Panobooking.com treat personal data belonging to children? </li>
            <li>i)	Data Retention and Deletion</li>
            <li>j)	Updates to this Policy </li>
                </ul>
               <h3> 1.	What kind of personal data does Panobooking.com collect?</h3>
               <p>Panobooking.com collects and uses the information you provide us. When you make a request or reservation with us, you are (at a minimum) asked for your name and email address.</p>
               <p>Depending on the nature of the reservation, we may also ask for your home address, telephone number, payment information, date of birth, location (in the case of on-demand services), the names of the people traveling with you, and any preferences you might have for your trip or service (such as dietary, accessibility, or medical requirements in case you are booking ambulance services). In some cases, you may also be able to check-in online with Providers (the Providers who can be booked through Panobooking.com) on our site, for which we will ask you to share passport information or a driver’s license and signatures.</p>
               <p>If you need to get in touch with our customer service team, contact your Provider through us, or reach out to us in a different way (such as social media or via a chatbot); we’ll collect information from you there, too. This applies whether you are contacting us with feedback or asking for help using our services.</p>
               <p>You might also be invited to write reviews to help inform others about the experiences you had on your service be it a trip, a hotel or ambulance service. When you write a review on the Panobooking.com platform, we’ll collect any info you’ve included, along with your display name and avatar (if you choose one).</p>
               <p>There are other instances where you’ll provide us with information as well. For example, if you’re browsing with your mobile device, you can decide to allow Panobooking.com to see your current location or grant us access to some contact details. This helps us to give you the best possible service and experience by, for example, showing you our city guides, suggesting the nearest restaurants, hotels, taxis, ambulances or attractions to your location, or making other recommendations.</p>
               <p>If you create a user account, we’ll also store your personal settings, uploaded photos, and reviews of previous bookings. This saved data can be used to help you plan and manage future Trip Reservations or benefit from other features only available to account holders, such as incentives or other benefits.</p>
               <p>We may or may not offer you referral programs or sweepstakes. Participating in these will involve providing us with relevant personal data.</p>
            <p style="font-weight: bold"> Personal data you give us about others.</p>
            <p>Of course, you might not just be making a reservation for yourself. You might be seeking to book a service offered by one of our providers with other people or making a reservation on someone else’s behalf. In both those scenarios, you will provide their details as part of the booking/reservation. </p>
            <p>If you have a Panobooking.com account, you may choose to keep an address book on Panobooking.com to make it easier to plan and manage business travel arrangements for others.</p>
            <p>In some cases, you might use Panobooking.com to share information with others. This can take the form of sharing a wish list, taking part in a travel community or participating in a referral program, as described when you use the relevant feature.</p>
            <p>At this point, we have to make it clear that it’s your responsibility to ensure that the person or people you have provided personal data about are aware that you’ve done so, and that they have understood and accepted how Panobooking.com uses their information (as described in this Privacy Statement).</p>
            <p style="font-weight: bold"> Personal data we collect automatically.</p>
            <p>Whether or not you end up making a booking, when you visit our websites or apps, we automatically collect certain info. This includes your IP address, the date and time you accessed our services, the hardware, software, or internet browser you used, and info about your computer’s operating system like application versions and your language settings. We also collect information about clicks and which pages were shown to you.</p>
            <p>If you’re using a mobile device, we collect data that identifies the device, as well as data about your device-specific settings and characteristics, app crashes, and other system activity. When you make a booking using this kind of device, our system registers how you made your reservation (on which website), and/or which site you came from when you entered the Panobooking.com website or app.</p>
            <p style="font-weight: bold">Personal data we receive from other sources.</p>
            <p>It’s not just the things you tell us, though—we may also receive information about you from other sources. These include business partners, such as affiliate partners, the Providers whose services you book through our site, and other independent third parties.</p>
            <p>Anything we receive from these partners may be combined with information provided by you. For example, Panobooking.com booking services may not only be made available via Panobooking.com and the Panobooking.com apps, but may also be integrated into services of affiliate partners you can find online. When you use any of these services, you provide the reservation details to our business partners who then forward your details to us.</p>
            <p>We also integrate with third party service providers to facilitate payments between you and Providers. These service providers share payment information, so we can administer and handle your bookings, making sure everything goes as smoothly as possible for you.</p>
            <p>We also collect info when we receive a complaint about you from a Trip Provider (e.g., in the case of misconduct).</p>
            <p>Another way we might receive data about you is through the communication services integrated into our platforms. These communication services offer you a way to contact the Provider you’ve booked with to discuss your stay. In some cases, we receive metadata about these communication activities (such as who you are, where you called from, and the date and length of the call).</p>
            <p>We may also receive information about you in order to show you more relevant ads, such as the additional cookie data Panobooking.com social media partners make available to us. </p>
            <p>When you link your Panobooking.com user account to your social media account, you might trigger exchanges of data between Panobooking.com and that social media provider. You can always choose not to share that data.</p>
            <p>Providers you book through our site or app may share info about you with Panobooking.com, too. This could happen if you have support questions about a pending bookings/reservations, or if disputes or other issues arise about a reservation/booking.</p>
        <h3>2.	How Personal Information about Customers is used?</h3>
        <p>Panobooking mainly being a mediation services provider collects User data which may be shared with Panobooking providers to improve processing of Users’ bookings and any other customer service. Such data may also be used both for marketing research purposes and customer relation management, it being specified that some of those providers may not be located within Uganda. </p>
        <p style="font-weight: bold">What is done with your personal information?</p>
        <p>User data collected help us to personalize our sites and apps according to each user’s wishes and preferences. Offering you the most seamless booking experience is our priority. Data collected are for statistical purposes only and help us to:</p>
        <li>Process Bookings/Reservations</li>
        <li>Enable Providers to deliver products and services</li>
        <li>Process or enable Providers to process payments and communicate with you about your orders, products, services and promotional offers</li>
        <li>Keep and update our database and your accounts with us</li>
        <li>Propose a unique and targeted navigation experience</li>
        <li>Prevent and detect fraud and abuse on our website</li>
        <p></p>

        <h3>3.	What about Cookies?</h3>
        <p>Cookies are unique identifiers that we transfer to your device to enable our systems to recognize your device and to provide features to make your navigation experience unique and targeted.</p>
        <p>The acceptance of cookies is not a requirement for visiting our site. However, we would like to point out that the use of the ‘bookings’ functionality on the Site and ordering is only possible with the activation of cookies. Cookies are tiny text files which identify your computer to our server as a unique user when you visit certain pages on the site and they are stored by your Internet browser on your computer’s hard drive. Cookies can be used to recognize your Internet Protocol address, saving you time while you are on, or want to enter, the site. We only use cookies for your convenience in using the site (for example to remember who you are when you want to amend your bookings without having to re-enter your email address every time) and not for obtaining or using any other information about you (for example targeted advertising). </p>
        <p>Your browser can be set to not accept cookies, but this would restrict your use of the Site. Please accept our assurance that our use of cookies does not contain any personal or private details and are free from viruses. </p>
        <p>Panobooking uses Google Analytics for marketing and personal data optimization purposes. Panobooking also uses Google Digital Marketing to propose targeted offers. </p>
        <p style="font-weight: bold">To find out more:</p>
        <p>About Google Analytics: <a href="https://google.com/analytics/" target="_blank">Click Here</a></p>
    <h3>4.	What is the accuracy of the Personal Data provided to Panobooking?</h3>
    <p>You declare and guarantee that You are the owner or have the necessary rights on the content that You transmit to Us; that at the date of its transmission (i) the content is exact and true, (ii) the use of the content does not contravene any of our policies and will not be damaging to any third party (i.e., that the content is not defamatory).</p>
    <h3>5.	How Secure is Information about Me?</h3>
    <p>It is important for you to protect against unauthorized access to your password and to your computer. Be sure to sign off when finished using a shared computer. We work to protect the security of your information during transmission by using Secure Sockets Layer (SSL) software, which encrypts information you input. In any event we have to collect credit card information, we reveal only the last four digits of your credit card numbers when confirming an order. Of course, we transmit the entire credit card number to the appropriate credit card company during order processing.</p>
    <h3>6.	What are your rights?</h3>
    <p>If you are concerned about your data, you have the right to request access to the personal data which we may hold or process about you. You have the right to require us to correct any inaccuracies in your data free of charge. At any stage you also have the right to ask us to stop using your personal data for direct marketing purposes. </p>
    <h3>7.	How does Booking.com treat personal data belonging to children?</h3>
    <p>Unless indicated otherwise, Panobooking.com is a service you’re only allowed to use if over you’re older than 18. We only process information about children with the consent of their parents or legal guardians, or when the information is shared with us by the parents or legal guardians themselves.</p>
    <h3>8.	Data Retention and Deletion</h3>
    <p>Panobooking.com retains user data for as long as necessary for the purposes described above.</p>
    <p>Users may request deletion of their accounts at any time. Panobooking.com may retain user data after a deletion request due to legal or regulatory requirements or for reasons stated in this policy.</p>
    <p>Panobooking.com retains user data for as long as necessary for the purposes described above. This means that we retain different categories of data for different periods of time depending on the type of data, the category of user to whom the data relates, and the purposes for which we collected the data.</p>
    <p>Users may request deletion of their account at any time through the settings. Following an account deletion request, Panobooking.com deletes the user’s account and data, unless they must be retained due to legal or regulatory requirements, for purposes of safety, security, and fraud prevention, or because of an issue relating to the user’s account such as an outstanding credit or an unresolved claim or dispute. Because we are subject to legal and regulatory requirements relating to service Providers, this generally means that we retain their accounts and data for a minimum of 7 years after a deletion request. For users, their data is generally deleted within 90 days of a deletion request, except where retention is necessary for the above reasons.</p>
<h3>9.	Updates to this n Updates to this notice</h3>
<p style="font-weight: bold">We may occasionally update this notice</p>
<p>We may occasionally update this notice. If we make significant changes, we will notify users in advance of the changes through the Panobooking apps or through other means, such as email. We encourage users to periodically review this notice for the latest information on our privacy practices.</p>
<p>Use of our services after an update constitutes consent to the updated notice to the extent permitted by law. </p>
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