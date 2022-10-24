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
                <h3>MARKETPLACE TERMS AND CONDITIONS</h3>
                
            </div>
        </div>

        <div class="row">
            <div class="col-md-12  animate-box">
                <h2> 1. INTRODUCTION</h2>
                <ul style="list-style: none;">
                <li>1.1. Panobooking Limited (“Panobooking” or “we”) operates an e-commerce platform consisting of a web and mobile applications together with supporting logistics and payment infrastructure, for the sale and purchase of specified services and services in Uganda. Some of Panobooking’s services hotel booking services, car rental services, and ambulance rental services.</li>
                <li>1.2. The terms and conditions shall apply to all buyers and service providers and shall govern the use of the services offered and marketplace.</li>
                <li>1.3. By using our marketplace, you accept these general terms and conditions in full. If you disagree with these general terms and conditions or any part of these general terms and conditions, you must not use our marketplace.</li>
                <li>1.4. If you use our marketplace, in the course of business or other organizational project, then by so doing you;</li>
                <li>&nbsp;&nbsp;&nbsp;&nbsp;1.4.1. confirm that you have obtained the necessary authority to agree to these general terms and conditions;</li>
                <li>&nbsp;&nbsp;&nbsp;&nbsp;1.4.2. bind both yourself and the person, company or other legal entity that operated that business or organizational project, to these general terms and conditions and</li>
                <li>&nbsp;&nbsp;&nbsp;&nbsp;1.4.3.	agree that “you” in these general terms and conditions shall reference both the individual user and the relevant person, company or legal entity unless the context requires otherwise.</li>
                </ul>
               <h2> 2.	Registration and account</h2>
               <ul style="list-style: none;">
               <li>2.1.	You may register with our marketplace if you are under 18 years of age. (by using our marketplace or agreeing to these general terms and conditions, you warrant and represent to us that you are at least 18 years of age)</li>
               <li>2.2.	You may register for an account with our marketplace by completing and submitting the registration form on our marketplace.</li>
               <li>2.3.	You represent and warrant that all information provided in the registration form is complete and accurate.</li>
               <li>2.4.	If you register for an account as a merchant with our marketplace, you will be asked to provide:</li>
                <li>&nbsp;&nbsp;&nbsp;&nbsp;2.4.1.	National ID copy;</li>
                <li>&nbsp;&nbsp;&nbsp;&nbsp;2.4.2.	Certificate of incorporation of the company intending to have its services booked from our platforms;</li>
                <li>&nbsp;&nbsp;&nbsp;&nbsp;2.4.3.	Bank account details both personal and company; </li>
                <li>&nbsp;&nbsp;&nbsp;&nbsp;2.4.4.	Passport size photos of the proposed merchant company’s directors. </li>
               <li>2.5.	If you register for an account with our marketplace, you will be asked to provide an email address/ users ID and password and you agree to;<li> 
               <li>&nbsp;&nbsp;&nbsp;&nbsp;2.5.1.	Keep your password confidential;</li>
               <li>&nbsp;&nbsp;&nbsp;&nbsp;2.5.2.	Notify us in writing immediately (using out contact details provided at section 24 if you become aware of any disclosure of your password; and </li>
               <li>&nbsp;&nbsp;&nbsp;&nbsp;2.5.3.	Be responsible for any activity on our marketplace arising out of any failure to keep your password confidential, and that you may be held liable for any losses arising out of such a failure.</li>
                <li>2.6	Your account shall be used exclusively by you and you shall not transfer your account to any third party. If you authorize any third party to manage your account on your behalf this shall be at your own risk.</li>
                <li>2.7.	We may suspend or cancel your account, and/ or edit your account details, at any time in our sole discretion and without notice or explanation, providing that if we cancel any services or services you have paid for but not received, and you have not breached these general terms and conditions, we will refer you to the Service Provider’s complaint response department for further guidance.</li>
                <li>2.8.	You may cancel your account on our marketplace by contacting us as provided under these terms.</li>
                </ul>
                <h2>3. Terms and conditions of sale </h2>
                <ul style="list-style: none;">
                <li>3.1.	You acknowledge and agree that:</li>
                <li>&nbsp;&nbsp;&nbsp;&nbsp;3.1.1.	the marketplace provides an online location for service providers to sell and buyers to purchase services;</li>
                <li>&nbsp;&nbsp;&nbsp;&nbsp;3.1.2.	We shall accept binding sales, on behalf of service providers, but Panobooking is not a party to the transaction between the service providers and buyers.</li>
                <li>&nbsp;&nbsp;&nbsp;&nbsp;3.1.3.	a contract for sale and purchase of a service or services will come into force between the buyer and service provider, and accordingly you commit to buying or selling the relevant service or services, upon the buyer’s confirmation of purchase via the marketplace.</li>
                <li>3.2.	Subject to these general terms and conditions, the service provider’s terms of business shall govern the contract for sale and purchase between the buyer and the service provider. Notwithstanding this, the following provisions will be incorporated into the contract of sale and purchase between the buyer and service provider:</li>
                <li>&nbsp;&nbsp;&nbsp;&nbsp;3.2.1.	the price for a service will be as stated in the relevant service listing;</li>
                <li>&nbsp;&nbsp;&nbsp;&nbsp;3.2.2.	the price for the service must include all taxes and comply with applicable laws in force from time to time;</li>
                <li>&nbsp;&nbsp;&nbsp;&nbsp;3.2.3.	services must be of satisfactory quality, fit and safe for any purpose specifies in, and conform in all material respect to, the service listing and any other description of the services supplied or made available by the service provider to the buyer; and </li>
                <li>&nbsp;&nbsp;&nbsp;&nbsp;4.2.4.	the service provider warrants that the service provider has good title to, and is the sole legal and beneficial owner of, the services, and that the services are not subject to any third-party rights or restrictions including in respect of third-party intellectual property rights and/ or nay criminal, insolvency or tax investigation or proceedings.</li>
                </ul>
                <h2>4.	Bookings and Performance </h2>
                <ul style="list-style: none;">
                    <li>4.1.	Bookings are made by customers at checkout point </li>
                    <li>4.2.	Catering for bookings is done once the order is accepted and confirmed by the Panobooking administrator and; </li>
                    <li>&nbsp;&nbsp;&nbsp;&nbsp;4.2.1.	performance is completed by Panobooking Service Providers. </li>
                    <li>&nbsp;&nbsp;&nbsp;&nbsp;4.2.2.	For purposes of this Agreement, “Panobooking Service Providers” are the entities that will be in charge of catering for bookings from one place to another to the customers subject to separate agreements with Panobooking.</li>
                </ul>
                <h2>5.	Returns and refunds</h2>
                <ul style="list-style: none;">
                    <li>5.1.	We generally do not make refunds on services or services once they have been sold or booked. We retain the right to determine when any exception to this Clause may apply.</li>
                    <li>5.2.	Cancellations are possible within a period of 7 days after which users will not be able to make cancellations on the booked services.</li>
                    <li>5.3.	In the event of a cancellation, we may refer the user to the Panobooking Service Providers for purposes of refunds, in our discretion:</li>
                    <li>&nbsp;&nbsp;&nbsp;&nbsp;5.3.1.	in respect of the service price;</li>
                    <li>&nbsp;&nbsp;&nbsp;&nbsp;5.3.2.	by way of service provider credits, wallet refunds, vouchers, mobile money transfer, bank transfers or such other method as we may determine from time to time.</li>
                </ul>
                <h2>6. Payments </h2>
                <ul style="list-style: none;">
                <li>6.1.	You must make payments due under these general terms and conditions in accordance with the Payments Information and Guidelines on the marketplace.</li>
                <li>6.2.	Payments shall be made in a manner determined by the Panobooking Service Providers.</li>
                <li>6.2.1.	Payments will be made at the checkout point</li>
                <li>6.2.2.	Payment will be made online through mobile money, VISA, MasterCard. </li>
                <li>6.2.3.	Payment can be before or after the order is placed and at delivery point.</li>
                <li>6.2.4.	Payment to service providers will be made within a maximum of 5 days from the time the service provider requests to cash out their earnings through the Panobooking Platform.</li>
                </ul>
                <h2>7.	Rules about Merchant’s content. </h2>
                <ul style="list-style: none;">
                    <li>7.1.	As a merchant, in these general terms and conditions, “your content” means: </li>
                    <li>&nbsp;&nbsp;&nbsp;&nbsp;7.1.1.	all works and materials (including without limitation text, graphics, images, audio material, video material, audio-visual material, scripts, software and files) that you submit to us or our marketplace for storage or publication, processing by, or onward transmission; and</li>
                    <li>&nbsp;&nbsp;&nbsp;&nbsp;7.1.2.	all communications on the marketplace, including service reviews, feedback and comments. </li>
                    <li>7.2.	Your content and the use of your content by us in accordance with these general terms and conditions, must be accurate, complete, and truthful. </li>
                    <li>7.3.	Your content, and the use of your content by us in accordance with these general terms and conditions, must be accurate, complete and truthful.</li>
                    <li>&nbsp;&nbsp;&nbsp;&nbsp;7.3.1.	be offensive, obscene, indecent, pornographic, lewd, suggestive or sexually explicit;</li>
                    <li>&nbsp;&nbsp;&nbsp;&nbsp;7.3.2.	depict violence in an explicit, graphic or gratuitous manner; or be blasphemous, in breach of racial or religious hatred or discrimination legislation;</li>
                    <li>&nbsp;&nbsp;&nbsp;&nbsp;7.3.3.	cause annoyance, inconvenience or needless anxiety to any person; or</li>
                    <li>&nbsp;&nbsp;&nbsp;&nbsp;7.3.4.	be spam.</li>
                <li>7.4.	Your content must not be illegal or unlawful, infringe any person’s legal rights, or be capable of giving rise to legal action against any person (in each case in any jurisdiction and under any applicable law). Your content must not infringe or breach:</li>
                <li>&nbsp;&nbsp;&nbsp;&nbsp;7.4.1.	any copyright, moral right, database right, trademark right, design right, right in passing off or other intellectual property right;</li>
                <li>&nbsp;&nbsp;&nbsp;&nbsp;7.4.2.	any right of confidence, right of privacy or right under data protection legislation;</li>
                <li>&nbsp;&nbsp;&nbsp;&nbsp;7.4.3.	any contractual obligation owed to any person; or</li>
                <li>&nbsp;&nbsp;&nbsp;&nbsp;7.4.4.	any court order.</li>
                <li>7.5.	You must not use our marketplace to link to any website or webpage consisting of or containing material that would, were it posted on our marketplace, breach the provisions of these general terms and conditions.</li>
                <li>7.6.	You must not submit to our marketplace any material that is or has ever been the subject of any threatened or actual legal proceedings or other similar complaint.</li>
                <li>7.7.	The review function on the marketplace may be used to facilitate buyer reviews on services. You shall not use the review function or any other form of communication to provide inaccurate, inauthentic or fake reviews.</li>
                <li>7.8.	You must not interfere with a transaction by:</li>
                <li>&nbsp;&nbsp;&nbsp;&nbsp;7.8.1.	contacting another user to buy or sell an item listed on the marketplace outside of the marketplace; or </li>
                <li>&nbsp;&nbsp;&nbsp;&nbsp;7.8.2.	communicating with a user involved in an active or completed transaction to warn them away from a particular buyer, service provider or item; or </li>
                <li>&nbsp;&nbsp;&nbsp;&nbsp;7.8.3.	contacting another user with the intent to collect any payments.</li>
                <li>7.9.	You acknowledge that all users of the marketplace are solely responsible for interactions with other users and you shall exercise caution and good judgment in your communication with users. You shall not send them personal information including credit card details.</li>
                <li>7.10.	We may periodically review your content and we reserve the right to remove any content in our discretion for any reason whatsoever.</li>
                <li>7.11.	If you learn of any unlawful material or activity on our marketplace, or any material or activity that breaches these general terms and conditions, you may inform us by contacting us as provided at section 24.</li>
                </ul>
                <h2>8.	Our rights to use your content </h2>
                <ul style="list-style: none;">
                    <li>8.1.	You grant to us a worldwide, irrevocable, non-exclusive, royalty-free license to use, reproduce, store, adapt, publish, translate and distribute your content across our marketing channels and any existing or future media.</li>
                    <li>7.2.	You grant to us the right to sub-license the rights licensed under section 8.1.</li>
                    <li>8.3.	You grant to us the right to bring an action for infringement of the rights licensed under section 8.1.</li>
                    <li>8.4.	You hereby waive all your moral rights in your content to the maximum extent permitted by applicable law; and you warrant and represent that all other moral rights in your content have been waived to the maximum extent permitted by applicable law.</li>
                    <li>8.5.	Without prejudice to our other rights under these general terms and conditions, if you breach our rules on content in any way, or if we reasonably suspect that you have breached our rules on content, we may delete, unpublish or edit any or all of your content.</li>
                </ul>
                <h2>9. Use of website and mobile applications </h2>
                <ul style="list-style: none;">
                    <li>9.1.	In this section 9 words “marketplace” and “website” shall be used interchangeably to refer to Panobooking’s websites and mobile applications. </li>
                    <li>9.2.	You may:</li>
                    <li>&nbsp;&nbsp;&nbsp;&nbsp;9.2.1.	view pages from our website in a web browser;</li>
                    <li>&nbsp;&nbsp;&nbsp;&nbsp;9.2.2.	download pages from our website for caching in a web browser;</li>
                    <li>&nbsp;&nbsp;&nbsp;&nbsp;9.2.3.	print pages from our website for your own personal and non-commercial use, providing that such printing is not systematic or excessive;</li>
                    <li>&nbsp;&nbsp;&nbsp;&nbsp;9.2.4.	stream audio and video files from our website using the media player on our website; and </li>
                    <li>&nbsp;&nbsp;&nbsp;&nbsp;9.2.5.	use our marketplace services by means of a web browser, subject to the other provisions of these general terms and conditions.</li>
                    <li>9.3.	Except as expressly permitted by section 9.2 or the other provisions of these general terms and conditions, you must not download any material from our website or save any such material to your computer.</li>
                <li>9.4.	You may only use our website for your own personal and business purposes in respect of selling or purchasing services on the marketplace.</li>
                <li>9.5.	Except as expressly permitted by these general terms and conditions, you must not edit or otherwise modify any material on our website.</li>
                <li>9.6.	Unless you own or control the relevant rights in the material, you must not:</li>
                <li>&nbsp;&nbsp;&nbsp;&nbsp;9.6.1.	Re-publish material from our website (including re-publication on another website); </li>
                <li>&nbsp;&nbsp;&nbsp;&nbsp;9.6.2.	Re-publish material from our website (including republication on another website);</li>
                <li>&nbsp;&nbsp;&nbsp;&nbsp;9.6.3.	sell, rent or sub-license material from our website; </li>
                <li>&nbsp;&nbsp;&nbsp;&nbsp;9.6.4.	show any material from our website in public; </li>
                <li>&nbsp;&nbsp;&nbsp;&nbsp;.6.5.	exploit material from our website for a commercial purpose; or</li>
                <li>&nbsp;&nbsp;&nbsp;&nbsp;9.6.6.	redistribute material from our website.</li>
                <li>9.7.	Notwithstanding section 9.6, you may forward links to services on our website and re-distribute our newsletter and promotional materials in print and electronic form to any person.</li>
            <li>9.8.	We reserve the right to suspend or restrict access to our website, to areas of our website and/or to functionality upon our website. We may, for example, suspend access to the website during server maintenance or when we update the website. You must not circumvent or bypass, or attempt to circumvent or bypass, any access restriction measures on the website.</li>
            <li>9.9.	You must not:</li>
            <li>&nbsp;&nbsp;&nbsp;&nbsp;9.9.1.	use our website in any way or take any action that causes, or may cause, damage to the website or impairment of the performance, availability, accessibility, integrity or security of the website;</li>
            <li>&nbsp;&nbsp;&nbsp;&nbsp;9.9.2.	use our website in any way that is unethical, unlawful, illegal, fraudulent or harmful, or in connection with any unlawful, illegal, fraudulent or harmful purpose or activity;</li>
            <li>&nbsp;&nbsp;&nbsp;&nbsp;9.9.3.	hack or otherwise tamper with our website; probe, scan or test the vulnerability of our website without our permission; </li>
            <li>&nbsp;&nbsp;&nbsp;&nbsp;9.9.4.	circumvent any authentication or security systems or processes on or relating to our website;</li>
            <li>&nbsp;&nbsp;&nbsp;&nbsp;9.9.5.	use our website to copy, store, host, transmit, send, use, publish or distribute any material which consists of (or is linked to) any spyware, computer virus, Trojan horse, worm, keystroke logger, rootkit or other malicious computer software;</li>
            <li>&nbsp;&nbsp;&nbsp;&nbsp;9.9.6.	impose an unreasonably large load on our website resources (including bandwidth, storage capacity and processing capacity); </li>
            <li>&nbsp;&nbsp;&nbsp;&nbsp;9.9.7.	decrypt or decipher any communications sent by or to our website without our permission;</li>
            <li>&nbsp;&nbsp;&nbsp;&nbsp;9.9.8.	conduct any systematic or automated data collection activities (including without limitation scraping, data mining, data extraction and data harvesting) on or in relation to our website without our express written consent;</li>
            <li>&nbsp;&nbsp;&nbsp;&nbsp;9.9.9.	access or otherwise interact with our website using any robot, spider or other automated means, except for the purpose of search engine indexing;</li>
            <li>&nbsp;&nbsp;&nbsp;&nbsp;9.9.10.	use our website except by means of our public interfaces;</li>
            <li>&nbsp;&nbsp;&nbsp;&nbsp;9.9.11.	violate the directives set out in the robots.txt file for our website;</li>
            <li>&nbsp;&nbsp;&nbsp;&nbsp;9.9.12.	use data collected from our website for any direct marketing activity (including without limitation email marketing, SMS marketing, telemarketing and direct mailing); or </li>
            <li>&nbsp;&nbsp;&nbsp;&nbsp;9.9.13.	do anything that interferes with the normal use of our website</li>
            </ul>
                <h2>10.	Copyright and trademarks</h2>
                <ul style="list-style: none;">
                    <li>10.1. Subject to the express provisions of these general terms and conditions:</li>
                    <li>&nbsp;&nbsp;&nbsp;&nbsp;10.1.1.	we, together with our licensors, own and control all the copyright and other intellectual property rights in our website and the material on our website; and</li>
                    <li>&nbsp;&nbsp;&nbsp;&nbsp;10.1.2.	all the copyright and other intellectual property rights in our website and the material on our website are reserved.</li>
                    <li>10.2.	Panobooking’s logos and our other registered and unregistered trademarks are trademarks belonging to us; we give no permission for the use of these trademarks, and such use may constitute an infringement of our rights.</li>
                    <li>10.3.	The third party registered and unregistered trademarks or service marks on our website are the property of their respective owners and we do not endorse and are not affiliated with any of the holders of any such rights and as such we cannot grant any license to exercise such rights.</li>
                </ul>
                <h2>11.	Data privacy</h2>
                <ul style="list-style: none;">
                    <li>11.1.	Buyers agree to the processing of their personal data in accordance with the terms of Panobooking’s Privacy and Cookie Notice.</li>
                    <li>11.2.	Panobooking shall process all personal data obtained through the marketplace and related services in accordance with the terms of our Privacy and Cookie Notice and Privacy Policy.</li>
                    <li>11.3.	Service providers shall be directly responsible to buyers for any misuse of their personal data and Panobooking shall bear no liability to buyers in respect of any misuse by service providers of their personal data.</li>
                </ul>

                <h2>12.	Due diligence and audit rights</h2>
                <ul style="list-style: none;">
                    <li>12.1.	We operate an anti-money laundering compliance program and reserve the right to perform due diligence checks on all users of the marketplace.</li>
                    <li>12.2.	You agree to provide to us all such information, documentation and access to your business premises as we may require:</li>
                    <li>&nbsp;&nbsp;&nbsp;&nbsp;12.2.1.	in order to verify your adherence to, and performance of, your obligations under this Agreement;</li>
                    <li>&nbsp;&nbsp;&nbsp;&nbsp;12.2.2.	for the purpose of disclosures pursuant to a valid order by a court or other governmental body; or </li>
                    <li>&nbsp;&nbsp;&nbsp;&nbsp;12.2.3.	as otherwise required by law or applicable regulation.</li>
                </ul>
                <h2>13.	Panobooking’s role as a marketplace</h2>
                <ul style="list-style: none;">
                    <li>13.1.	You acknowledge that:</li>
                    <li>&nbsp;&nbsp;&nbsp;&nbsp;13.1.1.	we do not confirm the identity of all marketplace users, check their credit worthiness or bona fides, or otherwise vet them;</li>
                    <li>&nbsp;&nbsp;&nbsp;&nbsp;13.1.2.	we do not check, audit or monitor all information contained in listings; we are not party to any contract for the sale or purchase of services advertised on the marketplace;</li>
                    <li>&nbsp;&nbsp;&nbsp;&nbsp;13.1.3.	we are not party to any contract for the sale or purchase of services advertised on the marketplace.</li>
                    <li>&nbsp;&nbsp;&nbsp;&nbsp;13.1.4.	we are not involved in any transaction between a buyer and a service provider in any way, save that we facilitate a marketplace for buyers and service providers and process payments on behalf of service providers;</li>
                    <li>&nbsp;&nbsp;&nbsp;&nbsp;12.1.5.	we are not the agents for any buyer or service provider, and accordingly we will not be liable to any person in relation to the offer for sale, sale or purchase of any services advertised on our marketplace; furthermore, we are not responsible for the enforcement of any contractual obligations arising out of a contract for the sale or purchase of any services and we will have no obligation to mediate between the parties to any such contract.</li>
               <li>13.2.	We do not warrant or represent:</li>
               <li>&nbsp;&nbsp;&nbsp;&nbsp;13.2.1.	the completeness or accuracy of the information published on our marketplace;</li>
               <li>&nbsp;&nbsp;&nbsp;&nbsp;13.2.2.	that the material on the marketplace is up to date; </li>
               <li>&nbsp;&nbsp;&nbsp;&nbsp;13.2.3.	that the marketplace will operate without fault; or </li>
               <li>&nbsp;&nbsp;&nbsp;&nbsp;13.2.4.	that the marketplace or any service on the marketplace will remain available.</li>
               <li>13.3.	We reserve the right to discontinue or alter any or all of our marketplace services, and to stop publishing our marketplace, at any time in our sole discretion without notice or explanation; and you will not be entitled to any compensation or other payment upon the discontinuance or alteration of any marketplace services, or if we stop publishing the marketplace.</li>
               <li>13.4.	We do not guarantee any commercial results concerning the use of the marketplace.</li>
               <li>13.5.	To the maximum extent permitted by applicable law and subject to section 14.1 below, we exclude all representations and warranties relating to the subject matter of these general terms and conditions, our marketplace and the use of our marketplace.</li>
                </ul>
                <h2>14.	Limitations and exclusions of liability</h2>
                <ul style="list-style: none;">
                    <li>14.1.	Nothing in these general terms and conditions will:</li>
                    <li>&nbsp;&nbsp;&nbsp;&nbsp;14.1.1.	limit any liabilities in any way that is not permitted under applicable law; or</li>
                    <li>&nbsp;&nbsp;&nbsp;&nbsp;14.1.2.	exclude any liabilities or statutory rights that may not be excluded under applicable law.</li>
                    <li>14.2.	The limitations and exclusions of liability set out in this section 13 and elsewhere in these general terms and conditions: </li>
                    <li>&nbsp;&nbsp;&nbsp;&nbsp;14.2.1.	are subject to section 13.1; and </li>
                    <li>&nbsp;&nbsp;&nbsp;&nbsp;14.2.2.	govern all liabilities arising under these general terms and conditions or relating to the subject matter of these general terms and conditions, including liabilities arising in contract, in tort (including negligence) and for breach of statutory duty, except to the extent expressly provided otherwise in these general terms and conditions.</li>
                    <li>14.3.	In respect of the services offered to you free of charge we will not be liable to you for any loss or damage of any nature whatsoever.</li>
                    <li>14.4.	Our aggregate liability to you in respect of any contract to provide services to you under these general terms and conditions shall not exceed the total amount paid and payable to us under the contract. Each separate transaction on the marketplace shall constitute a separate contract for the purpose of this section.13.4.</li>
                    <li>14.5.	Notwithstanding section 13.4 above, we will not be liable to you for any loss or damage of any nature, including in respect of:</li>
                    <li>14.5.1.	any losses occasioned by any interruption or dysfunction to the website;</li>
                    <li>&nbsp;&nbsp;&nbsp;&nbsp;14.5.2.	any losses arising out of any event or events beyond our reasonable control;</li>
                    <li>&nbsp;&nbsp;&nbsp;&nbsp;14.5.3.	any business losses, including (without limitation) loss of or damage to profits, income, revenue, use, production, anticipated savings, business, contracts, commercial opportunities or goodwill;</li>
                    <li>&nbsp;&nbsp;&nbsp;&nbsp;14.5.4.	any loss or corruption of any data, database or software; or</li>
                    <li>&nbsp;&nbsp;&nbsp;&nbsp;14.5.5.	any special, indirect or consequential loss or damage.</li>
                    <li>14.6.	We accept that we have an interest in limiting the personal liability of our officers and employees and, having regard to that interest, you acknowledge that we are a limited liability entity; you agree that you will not bring any claim personally against our officers or employees in respect of any losses you suffer in connection with the marketplace or these general terms and conditions (this will not limit or exclude the liability of the limited liability entity itself for the acts and omissions of our officers and employees).</li>
                    <li>14.7.	In any event that our marketplace includes hyperlinks to other websites owned and operated by third parties; such hyperlinks are not recommendations. We have no control over third party websites and their contents, and we accept no responsibility for them or for any loss or damage that may arise from your use of them.</li>
                
                </ul>
                <h2>15.	Indemnification</h2>
                <ul style="list-style: none;">
                    <li>15.1.	You hereby indemnify us, and undertake to keep us indemnified, against:</li>
                    <li>&nbsp;&nbsp;&nbsp;&nbsp;15.1.1.	any and all losses, damages, costs, liabilities and expenses (including without limitation legal expenses and any amounts paid by us to any third party in settlement of a claim or dispute) incurred or suffered by us and arising directly or indirectly out of your use of our marketplace or any breach by you of any provision of these general terms and conditions or the Panobooking codes, policies or guidelines; and</li>
                    <li>&nbsp;&nbsp;&nbsp;&nbsp;15.1.2.	any VAT liability or other tax liability that we may incur in relation to any sale, supply or purchase made through our marketplace, where that liability arises out of your failure to pay, withhold, declare or register to pay any VAT or other tax properly due in any jurisdiction. </li>                
                </ul>
                <h2>16.	Breaches of these general terms and conditions</h2>
                <ul style="list-style: none;">
                    <li>16.1.	If we permit the registration of an account on our marketplace it will remain open indefinitely, subject to these general terms and conditions.</li>
                    <li>16.2.	If you breach these general terms and conditions, or if we reasonably suspect that you have breached these general terms and conditions or any Panobooking codes, policies or guidelines in any way we may:</li>
                    <li>&nbsp;&nbsp;&nbsp;&nbsp;16.2.1.	temporarily suspend your access to our marketplace;</li>
                    <li>&nbsp;&nbsp;&nbsp;&nbsp;16.2.2.	permanently prohibit you from accessing our marketplace;</li>
                    <li>&nbsp;&nbsp;&nbsp;&nbsp;16.2.3.	block computers using your IP address from accessing our marketplace;</li>
                    <li>&nbsp;&nbsp;&nbsp;&nbsp;16.2.4.	contact any or all of your internet service providers and request that they block your access to our marketplace;</li>
                    <li>&nbsp;&nbsp;&nbsp;&nbsp;16.2.5.	suspend or delete your account on our marketplace; and/or </li>
                    <li>&nbsp;&nbsp;&nbsp;&nbsp;16.2.6.	commence legal action against you, whether for breach of contract or otherwise.</li>
                    <li>16.3.	Where we suspend, prohibit or block your access to our marketplace or a part of our marketplace you must not take any action to circumvent such suspension or prohibition or blocking (including without limitation creating and/or using a different account).</li>    
                </ul>
                <h2>17.	Entire agreement</h2>
                <ul style="list-style: none;">
                    <li>17.1.	These general terms and conditions and the Panobooking’s codes, policies and guidelines (and in respect of service providers the service provider terms and conditions) shall constitute the entire agreement between you and us in relation to your use of our marketplace and shall supersede all previous agreements between you and us in relation to your use of our marketplace.</li>
                </ul>
                <h2>18.	Hierarchy </h2>
                <ul style="list-style: none;">
                    <li>18.1.	Should these general terms and conditions, the service provider terms and conditions, and the Panobooking’s codes, policies and guidelines be in conflict, these terms and conditions, the service provider terms and conditions and the Panobooking codes, policies and guidelines shall prevail in the order here stated.</li>
                </ul>
                <h2>19.	Variation </h2>
                <ul style="list-style: none;">
                    <li>19.1.	We may revise these general terms and conditions, the service provider terms and conditions, and the Panobooking codes, policies and guidelines from time to time.</li>
                    <li>19.2.	The revised general terms and conditions shall apply from the date of publication on the marketplace.</li>
                </ul>
                <h2>20.	Severability </h2>
                <ul style="list-style: none;">
                    <li>20.1.	If a provision of these general terms and conditions is determined by any court or other competent authority to be unlawful and/or unenforceable, the other provisions will continue in effect.</li>
                    <li>20.2.	If any unlawful and/or unenforceable provision of these general terms and conditions would be lawful or enforceable if part of it were deleted, that part will be deemed to be deleted, and the rest of the provision will continue in effect.</li>
                </ul>
                <h2>21.	Assignment </h2>
                <ul style="list-style: none;">
                    <li>21.1.	You hereby agree that we may assign, transfer, sub-contract or otherwise deal with our rights and/or obligations under these general terms and conditions.</li>
                    <li>21.2.	You may not without our prior written consent assign, transfer, sub-contract or otherwise deal with any of your rights and/or obligations under these general terms and conditions.</li>
                </ul>

                <h2>22.	Third party rights  </h2>
                <ul style="list-style: none;">
                    <li>22.1.	A contract under these general terms and conditions is for our benefit and your benefit, and is not intended to benefit or be enforceable by any third party.</li>
                    <li>22.2.	The exercise of the parties’ rights under a contract under these general terms and conditions is not subject to the consent of any third party.</li>
                </ul>
                <h2>23.	Law and jurisdiction  </h2>
                <ul style="list-style: none;">
                    <li>23.1.	These general terms and conditions shall be governed by and constructed in accordance with the laws of Uganda.</li>
                    <li>23.2.	Any disputes relating to these general terms and conditions shall be subject to the exclusive jurisdiction of the courts Uganda. </li>
                </ul>
                <h2>24.	Our company details   </h2>
                <ul style="list-style: none;">
                    <li>24.1.	The marketplace is operated Panobooking. We are registered in Uganda and our registered office is at UMF House, Plot 160, Sir Apollo Kaggwa Road.</li>
                     <li>24.2.	You can contact us by using our marketplace contact form.</li>
                </ul>
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
