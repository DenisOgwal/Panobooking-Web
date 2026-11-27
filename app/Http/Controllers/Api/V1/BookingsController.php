<?php

namespace App\Http\Controllers\Api\V1;

use App\Bookings\BookingService;
use App\Bookings\BookingType;
use App\Bookings\Customer;
use App\Http\Resources\V1\BookingResource;
use App\Http\Resources\V1\Present;
use App\Models\CartModel;
use App\Support\Currency;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

/**
 * The signed-in customer's bookings, and new bookings ({type} = room | home-stay | car | taxi | ambulance | tour).
 * Every booking starts "pending" until the provider (via the admin) confirms or declines it.
 */
class BookingsController extends ApiController
{
    public function __construct(private BookingService $bookings) {}

    /** GET /bookings?status=pending|history|all&q= */
    public function index(Request $request)
    {
        $request->validate(['status' => 'nullable|in:pending,history,all', 'q' => 'nullable|string|max:120']);
        $status = $request->query('status', 'all');

        $query = CartModel::forCustomer($this->customer($request)->Email)->search($this->term($request))
            ->when($status === 'pending', fn ($q) => $q->pending())
            ->when($status === 'history', fn ($q) => $q->decided())
            ->orderByDesc('IDs');

        return BookingResource::collection($query->paginate($this->perPage($request)));
    }

    /** GET /bookings/{id}: only the customer's own. */
    public function show(Request $request, int $id)
    {
        return new BookingResource($this->own($request, $id));
    }

    /** DELETE /bookings/{id}: cancel a pending booking (confirmed or declined ones can't be cancelled). */
    public function cancel(Request $request, int $id)
    {
        $booking = $this->own($request, $id);
        if ($booking->Confirmed !== CartModel::PENDING) {
            return response()->json(['message' => 'Only pending bookings can be cancelled.'], 409);
        }
        $booking->delete();

        return response()->json(['message' => 'Booking cancelled.']);
    }

    /** GET /bookings/form/{type}/{id}: default values and the fields the form takes. */
    public function form(string $type, int $id)
    {
        [$booking, $item] = $this->resolve($type, $id);
        $defaults = $booking->defaults($item);

        return response()->json(['data' => [
            'type' => $type,
            'defaults' => $defaults,
            'fields' => array_keys($booking->rules($item, fn ($f) => $f)),
            'quote' => $this->quoteBody($booking, $item, $defaults),
        ]]);
    }

    /** POST /bookings/quote/{type}/{id}: price for the input, without booking. */
    public function quote(Request $request, string $type, int $id)
    {
        [$booking, $item] = $this->resolve($type, $id);
        $data = $this->bookings->validate($booking, $item, $request->all());

        return response()->json(['data' => $this->quoteBody($booking, $item, $data)]);
    }

    /** POST /bookings/{type}/{id}: place the booking (201). */
    public function store(Request $request, string $type, int $id)
    {
        [$booking, $item] = $this->resolve($type, $id);

        $cart = $this->bookings->book($booking, $item, $request->all(), Customer::fromAccount($this->customer($request)), app(Currency::class));

        return (new BookingResource($cart))
            ->additional(['message' => 'Booking received. The provider will confirm it shortly.'])
            ->response()->setStatusCode(201);
    }

    /** @return array{0: BookingType, 1: Model} */
    private function resolve(string $type, int $id): array
    {
        $booking = $this->bookings->type($type);
        abort_if(! $booking, 404);
        $item = $booking->find($id);
        abort_if(! $item, 404);

        return [$booking, $item];
    }

    private function quoteBody(BookingType $booking, Model $item, array $data): array
    {
        $quote = $this->bookings->quote($booking, $item, $data, app(Currency::class));

        return ['total' => Present::price($quote['total'])] + $quote['details'];
    }

    private function own(Request $request, int $id): CartModel
    {
        return CartModel::forCustomer($this->customer($request)->Email)->whereKey($id)->firstOrFail();
    }
}
