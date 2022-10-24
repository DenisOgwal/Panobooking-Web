<!doctype html>
<html lang="en">
	<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">

	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Panobooking</title>
	 <link rel="shortcut icon" type="image/x-icon" href="{{asset('images/panaicon.png')}}" />
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="Panobooking.com" />
	<meta name="keywords" content="Panobooking.com, booking, travel, reservations, car hire, airport taxi, ambulance" />
	<meta name="author" content="PANOBOOKING LTD" />
	<meta property="og:title" content=""/>
	<meta property="og:image" content=""/>
	<meta property="og:url" content=""/>
	<meta property="og:site_name" content=""/>
	<meta property="og:description" content=""/>
	<meta name="twitter:title" content="" />
	<meta name="twitter:image" content="" />
	<meta name="twitter:url" content="" />
	<meta name="twitter:card" content="" />
    <link rel="preconnect" href="https://fonts.gstatic.com">
	<script src="https://kit.fontawesome.com/820c685d12.js" crossorigin="anonymous"></script>
	<link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
	<link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
	<!--<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" integrity="sha512-iBBXm8fW90+nuLcSKlbmrPcLa0OT92xO1BIsZ+ywDWZCvqsWgccV3gFoRBv0z+8dLJgyAHIhR35VZc2oM/gI1w==" crossorigin="anonymous" referrerpolicy="no-referrer" />-->

	<link rel="stylesheet" href="{{asset('css/animate.css')}}">
    <link rel="stylesheet" href="{{asset('css/icomoon.css')}}">
    <link rel="stylesheet" href="{{asset('css/jquery-ui.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/meanmenu.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/superfish.css')}}">
    <link rel="stylesheet" href="{{asset('css/magnific-popup.css')}}">
    <link rel="stylesheet" href="{{asset('css/bootstrap-datepicker.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/cs-select.css')}}">
	<link rel="stylesheet" href="{{asset('css/cs-skin-border.css')}}">
    <link rel="stylesheet" href="{{asset('css/style.css')}}">
    <link rel="stylesheet" href="{{asset('css/styles.css')}}">
    <link rel="stylesheet" href="{{asset('css/responsive.css')}}">

    <link rel="stylesheet" href="{{asset('css/pe-icon-7-stroke.css')}}">
    <link rel="stylesheet" href="{{asset('css/flaticon.css')}}">

    <link rel="stylesheet" href="{{asset('venobox/venobox.css')}}" type="text/css" media="screen">
    <link rel="stylesheet" href="{{asset('css/nivo-slider.css')}}" type="text/css">
    <link rel="stylesheet" href="{{asset('css/preview.css')}}" type="text/css" media="screen" >

    <link rel="stylesheet" href="{{asset('css/owl.carousel.css')}}">
    <link rel="stylesheet" href="{{asset('css/owl.carousel.min.css')}}">
	<link rel="stylesheet" href="{{asset('css/owl.theme.default.min.css')}}">
	<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/css/bootstrap-datetimepicker.min.css" rel="stylesheet">
    @livewireStyles
	<script async src="https://www.googletagmanager.com/gtag/js?id=UA-233515440-1">
	</script>
	<script>
	  window.dataLayer = window.dataLayer || [];
	  function gtag(){dataLayer.push(arguments);}
	  gtag('js', new Date());
	
	  gtag('config', 'UA-233515440-1');
	</script>
</head>
<body>
	
<!-- end:header-top -->
{{$slot}}	

<script src="{{asset('js/jquery.min.js')}}"></script>
<script src="{{asset('js/jquery.easing.1.3.js')}}"></script>
<script src="{{asset('js/jquery.waypoints.min.js')}}"></script>
<script src="{{asset('js/sticky.js')}}"></script>
<script src="{{asset('js/jquery.stellar.min.js')}}"></script>
<script src="{{asset('js/hoverIntent.js')}}"></script>
<script src="{{asset('js/superfish.js')}}"></script>
<script src="{{asset('js/jquery.magnific-popup.min.js')}}"></script>
<script src="{{asset('js/magnific-popup-options.js')}}"></script>
<script src="{{asset('js/bootstrap-datepicker.min.js')}}"></script>
<script src="{{asset('js/classie.js')}}"></script>
<script src="{{asset('js/selectFx.js')}}"></script>
<script src="{{asset('js/main.js')}}"></script>
<script src="{{asset('js/owl.carousel.min.js')}}"></script>
<script src="{{asset('lib/js/jquery.nivo.slider.js')}}" type="text/javascript"></script>
<script src="{{asset('lib/home.js')}}" type="text/javascript"></script>
<script src="{{asset('lib/js/jquery.nivo.slider.js')}}" type="text/javascript"></script>

<script src="{{asset('js/jquery-ui.min.js')}}"></script>
<script src="{{asset('js/jquery.counterup.min.js')}}"></script>
<script src="{{asset('js/waypoints.min.js')}}"></script>
<script type="text/javascript" src="{{asset('venobox/venobox.min.js')}}"></script>
<script src="{{asset('js/jquery.meanmenu.js')}}"></script>
<script src="{{asset('js/wow.min.js')}}"></script>
<script>
	new WOW().init();
</script>
<script src="{{asset('js/jquery.scrollUp.min.js')}}"></script>
<script src="{{asset('js/plugins.js')}}"></script>
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/js/bootstrap-datetimepicker.min.js"></script>
	@livewireScripts
    <script>
        window.addEventListener('alert', event => { 
                     toastr[event.detail.type](event.detail.message, 
                     event.detail.title ?? ''), toastr.options = {
                            "closeButton": true,
                            "progressBar": true,
                        }
                    });
        </script>
        <script>
        var slider=document.getElementById('slider-range');
        noUiSlider.create(slider,{ 
        start:[1,1000],
        connect:true,
        range:{
            'min':1,
            'max':1000
        },
        pips:{
            mode:'steps',
            stepped:true,
            density:4
        }
        });
        </script>
<script>
	$('.imagess-carousel').owlCarousel({
	loop:true,
	margin:10,
	nav:true,
	dots:false,
	autoplay:true,
	responsive:{
		0:{
			items:3
		},
		600:{
			items:3
		},
		1000:{
			items:3
		}
	}
})
</script>
<script>
	$('.singleitem-carousel').owlCarousel({
	loop:true,
	margin:10,
	nav:false,
	dots:false,
	autoplay:true,
	responsive:{
		0:{
			items:1
		},
		600:{
			items:1
		},
		1000:{
			items:1
		}
	}
})
</script>
<script>
	$('.footer-carousel').owlCarousel({
	loop:true,
	margin:10,
	nav:false,
	dots:false,
	autoplay:true,
	responsive:{
		0:{
			items:1
		},
		320:{
			items:2
		},
		480:{
			items:3
		},
		600:{
			items:3
		},
		768:{
			items:4
		},
		900:{
			items:4
		},
		1000:{
			items:6
		}
	}
})
</script>
<script>
	$('#datetime').datetimepicker({
		format: 'hh:mm:ss a'
	});
</script>
</body>
</html>
