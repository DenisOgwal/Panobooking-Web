<div class="row">
    @foreach ($hotelsearched as $hotelsearch)
    <div class="col-md-2 col-sm-6 fh5co-tours animate-box" data-animate-effect="fadeIn">
        <div href="#"><img src="https://panobooking.com/AndroidFiles/DestinationImages/{{ $hotelsearch->Place}}.jpg" alt="{{ $hotelsearch->Place}} Image" class="img-responsive">
            <div class="desc">
                <span></span>
                <h3>{{ $hotelsearch->Place}}</h3>
                <span>{{$hotelsearch->City}}</span>
                <span class="price">UGX.{{$hotelsearch->Prices}}</span>
                <a class="btn btn-primary btn-outline" href="{{route('HotelSingle',['hotelslug'=>$hotelsearch->IDs])}}">Book Now <i class="icon-arrow-right22"></i></a>
            </div>
        </div>
    </div>   
    @endforeach
    
    
<div class="col-md-12 text-center animate-box">
  
    {!!$hotelsearched->links()!!}
</div>
</div>

