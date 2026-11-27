<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

/**
 * Fictional sample data for local development.
 * Names, phone numbers and emails are made up.
 */
class PanobookingSeeder extends Seeder
{
    public function run()
    {
        $now = now();
        $stamp = fn (array $rows) => array_map(fn ($r) => $r + ['created_at' => $now, 'updated_at' => $now], $rows);

        $tables = ['currency', 'registration', 'destinations', 'homeguestlocations', 'pickuplocations', 'rooms', 'roomfeatures',
            'roomswith', 'popularamenities', 'propertydescription', 'houserules', 'reasonstostayatproperty', 'restaurantsonsite',
            'nearbyplaces', 'topattractions', 'closestairport', 'publictransport', 'childrenpolicies', 'childandextrabed',
            'bathroom', 'bedroom', 'view', 'outdoor', 'kitchen', 'foodanddrink', 'parking', 'transportation', 'cleaning',
            'businessfacilities', 'security', 'spa', 'accessibility', 'mediaandtechnology', 'nearbyrestaurants', 'nearbymarkets',
            'nearbysupermarkets', 'naturalbeauty', 'homeguesthouses', 'carrentals', 'carincludes', 'airporttaxi', 'taxitrip',
            'ambulances', 'ambulanceaccessories', 'blogs', 'blogcomments', 'clientfeedback', 'tourismsites', 'events',
            'tourpackage', 'cart'];
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        foreach ($tables as $t) {
            DB::table($t)->truncate();
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        DB::table('currency')->insert($stamp([
            ['CurrencyName' => 'UGX', 'CurrencyValue' => 1],
            ['CurrencyName' => 'USD', 'CurrencyValue' => 3700],
            ['CurrencyName' => 'EUR', 'CurrencyValue' => 4000],
            ['CurrencyName' => 'KES', 'CurrencyValue' => 28],
        ]));

        // Login: demo@panobooking.test / password123 (passwords are md5 in this app)
        DB::table('registration')->insert($stamp([
            ['FullNames' => 'Demo Traveller', 'Email' => 'demo@panobooking.test', 'Password' => md5('password123'),
             'Telephone' => '+256700000001', 'ApprovalStatus' => 'Approved', 'SelectedCurrency' => 'UGX'],
            ['FullNames' => 'Pending Person', 'Email' => 'pending@panobooking.test', 'Password' => md5('password123'),
             'Telephone' => '+256700000002', 'ApprovalStatus' => 'Not Approved', 'SelectedCurrency' => 'UGX'],
        ]));

        $destinations = [
            ['Kampala', 'Central', 'Uganda'], ['Entebbe', 'Central', 'Uganda'], ['Jinja', 'Eastern', 'Uganda'],
            ['Mbarara', 'Western', 'Uganda'], ['Fort Portal', 'Western', 'Uganda'], ['Gulu', 'Northern', 'Uganda'],
            ['Mbale', 'Eastern', 'Uganda'], ['Kabale', 'Western', 'Uganda'], ['Masaka', 'Central', 'Uganda'],
            ['Arua', 'Northern', 'Uganda'], ['Nairobi', 'Nairobi', 'Kenya'],
        ];
        DB::table('destinations')->insert($stamp(array_map(fn ($d, $i) => [
            'DestinationID' => $i + 1, 'City' => $d[0], 'Region' => $d[1], 'Country' => $d[2],
        ], $destinations, array_keys($destinations))));

        DB::table('homeguestlocations')->insert($stamp(array_map(fn ($c) => ['City' => $c], ['Kampala', 'Jinja', 'Fort Portal', 'Kabale'])));

        // ---- Hotels / stays ----
        $hotels = [
            ['Nile Breeze Hotel', 'Jinja', 'Eastern', 'Hotel', 4.5, 1, 'Yes', 'Yes', 1, 180000],
            ['Kololo Heights Suites', 'Kampala', 'Central', 'Apartment', 4.7, 2, 'Yes', 'Yes', 2, 250000],
            ['Lakeshore Lodge Entebbe', 'Entebbe', 'Central', 'Lodge', 4.2, 3, 'Yes', 'Yes', 3, 150000],
            ['Rwenzori View Resort', 'Fort Portal', 'Western', 'Resort', 4.8, 4, 'Yes', 'Yes', 4, 320000],
            ['Bunyonyi Hillside Cottages', 'Kabale', 'Western', 'Cottage', 4.6, 5, 'Yes', 'No', 5, 210000],
            ['Mbarara Garden Inn', 'Mbarara', 'Western', 'Hotel', 4.0, 6, 'Yes', 'No', 6, 120000],
            ['Gulu City Guesthouse', 'Gulu', 'Northern', 'Guest House', 3.9, 7, 'No', 'Yes', 7, 90000],
            ['Elgon Peaks Hotel', 'Mbale', 'Eastern', 'Hotel', 4.1, 8, 'No', 'Yes', 8, 110000],
        ];
        $hotelRows = [];
        foreach ($hotels as [$name, $city, $region, $type, $rating, $rank, $trending, $featured, $frank, $price]) {
            $hotelRows[] = [
                'Place' => $name, 'City' => $city, 'Country' => 'Uganda', 'PlaceType' => $type, 'Rating' => $rating,
                'Prices' => $price, 'TrendRank' => $rank, 'Trending' => $trending, 'Featured' => $featured, 'FaturedRank' => $frank,
                'Approved' => 'Approved', 'EmailAddress' => 'bookings@' . strtolower(str_replace(' ', '', $name)) . '.test',
                'ContactNo' => '+2567' . str_pad((string) (10000000 + $rank * 1111), 8, '0'), 'ExactLocation' => "Plot $rank, Main Street, $city",
                'coordinates' => '0.3476,32.5825', 'PhotoUrl' => 3, 'BedBreakFast' => 'Yes', 'CreditCard' => 'Yes',
                'Parking' => 'Free', 'Prepayment' => 'No', 'TaxesInclusive' => 'Yes',
                'PriceSpecifications' => 'Prices are per room per night.', 'Comment' => 'Guests love the friendly staff.',
                'SearchTerms' => "$name $city $region $type Uganda",
                'Description' => "$name is a comfortable $type in $city with modern rooms, free Wi-Fi and a restaurant serving local and international dishes.",
            ];
        }
        DB::table('pickuplocations')->insert($stamp($hotelRows));

        $roomTypes = [['Standard Room', 1.0], ['Deluxe Double', 1.4], ['Family Suite', 2.0]];
        $roomId = 0;
        foreach ($hotels as $i => [$name, , , , , , , , , $price]) {
            foreach ($roomTypes as $j => [$rname, $mult]) {
                $roomId++;
                $cost = (int) round($price * $mult, -3);
                DB::table('rooms')->insert($stamp([[
                    'IDs' => $roomId, 'HotelName' => $name, 'RoomName' => $rname, 'Price' => $cost, 'Cost' => $cost,
                    'NoOfRooms' => 5 + $j, 'Availability' => 'Available', 'Offered' => $j === 1 ? 'Offered' : 'No',
                    'BreakFast' => 'Included', 'RefundPolicy' => 'Free cancellation up to 48 hours before check-in.',
                    'RoomDesc' => "A bright $rname with comfortable bedding and a private bathroom.", 'PhotoUrl' => 2,
                ]]));
                DB::table('roomfeatures')->insert($stamp([[
                    'roomid' => $roomId, 'RoomName' => $rname, 'Feet' => (20 + $j * 10) . ' m²',
                    'KingBed' => $j > 0 ? 'Yes' : 'No', 'QueenBed' => $j === 0 ? 'Yes' : 'No', 'TwinBed' => $j === 2 ? 'Yes' : 'No',
                    'PoolView' => $i % 2 ? 'Yes' : 'No', 'CityView' => 'Yes', 'LakeView' => $i % 3 === 0 ? 'Yes' : 'No', 'GardensView' => 'Yes',
                    'Balcony' => 'Yes', 'BathTub' => $j > 0 ? 'Yes' : 'No', 'AirConditioning' => 'Yes', 'AttachedBathroom' => 'Yes',
                    'FlatScreen' => 'Yes', 'MiniBar' => $j > 0 ? 'Yes' : 'No', 'FreeWifi' => 'Yes', 'Toilet' => 'Yes', 'HotTub' => 'No',
                    'Towels' => 'Yes', 'Linens' => 'Yes', 'Slippers' => 'Yes', 'Toiletries' => 'Yes', 'Wardrobe' => 'Yes', 'Desk' => 'Yes',
                    'Telephone' => 'Yes', 'Safe' => 'Yes', 'TeaMaker' => 'Yes', 'ElectricKettle' => 'Yes', 'HairDryer' => 'Yes',
                    'Fan' => 'Yes', 'MosquitoNet' => 'Yes', 'SittingArea' => $j === 2 ? 'Yes' : 'No', 'Socket' => 'Yes',
                    'Carpeted' => 'No', 'AlarmClock' => 'Yes', 'WakeupService' => 'Yes', 'BathRobe' => 'Yes', 'Refrigerator' => 'Yes',
                    'Dryer' => 'No',
                ]]));
            }
        }

        $amenityTables = [
            'bathroom' => ['Towels', 'Hot shower', 'Free toiletries'], 'bedroom' => ['Linens', 'Wardrobe'],
            'view' => ['Garden view', 'City view'], 'outdoor' => ['Terrace', 'Garden'], 'kitchen' => ['Electric kettle'],
            'foodanddrink' => ['Restaurant', 'Bar', 'Room service'], 'parking' => ['Free private parking'],
            'transportation' => ['Airport shuttle (surcharge)'], 'cleaning' => ['Daily housekeeping', 'Laundry'],
            'businessfacilities' => ['Meeting room', 'Fax/photocopying'], 'security' => ['24-hour security', 'CCTV'],
            'spa' => ['Massage'], 'accessibility' => ['Ground floor rooms'], 'mediaandtechnology' => ['Flat-screen TV', 'Free Wi-Fi'],
        ];
        foreach ($hotels as $i => [$name, $city]) {
            DB::table('roomswith')->insert($stamp([[
                'PropertyName' => $name, 'CityView' => 'Yes', 'GardenView' => 'Yes', 'LakeView' => $i % 3 === 0 ? 'Yes' : 'No',
                'LandMarkView' => 'No', 'MountainView' => in_array($city, ['Fort Portal', 'Kabale', 'Mbale']) ? 'Yes' : 'No',
                'PoolView' => $i % 2 ? 'Yes' : 'No', 'StreetView' => 'Yes', 'TerraceView' => 'Yes',
            ]]));
            DB::table('popularamenities')->insert($stamp([[
                'PropertyName' => $name, 'Wifi' => 'Yes', 'BreakFast' => 'Yes', 'Restaurant' => 'Yes', 'Bar' => 'Yes',
                'SwimmingPool' => $i % 2 ? 'Yes' : 'No', 'FitnessCenter' => $i % 3 === 0 ? 'Yes' : 'No', 'Spa' => 'Yes',
                'Shuttle' => 'Yes', 'NoSmokingRoom' => 'Yes', 'TeaMaker' => 'Yes', 'Forex' => 'No',
            ]]));
            DB::table('propertydescription')->insert($stamp([['PropertyName' => $name,
                'Description' => "Set in $city, $name offers rooms with free Wi-Fi, a garden and a shared lounge. Breakfast is served daily."]]));
            DB::table('houserules')->insert($stamp([[
                'PropertyName' => $name, 'CheckIn' => 'From 14:00', 'CheckOut' => 'Until 11:00', 'Pets' => 'Not allowed',
                'AgeRestriction' => 'Minimum check-in age is 18', 'CardAcceptability' => 'Visa, Mastercard, Mobile Money',
                'PaymentCancelation' => 'Free cancellation up to 48 hours before arrival.',
            ]]));
            DB::table('reasonstostayatproperty')->insert($stamp([
                ['PropertyName' => $name, 'Reason' => 'Excellent location'], ['PropertyName' => $name, 'Reason' => 'Great breakfast'],
            ]));
            DB::table('restaurantsonsite')->insert($stamp([['PropertyName' => $name, 'RestaurantName' => 'The Terrace',
                'Food' => 'African, International', 'OpenFor' => 'Breakfast, Lunch, Dinner']]));
            DB::table('nearbyplaces')->insert($stamp([['PropertyName' => $name, 'Place' => "$city Central Market", 'Distance' => '1.2 km']]));
            DB::table('topattractions')->insert($stamp([['PropertyName' => $name, 'Place' => "$city Craft Village", 'Distance' => '2.5 km']]));
            DB::table('closestairport')->insert($stamp([['PropertyName' => $name, 'Airport' => 'Entebbe International Airport', 'Distance' => '40 km']]));
            DB::table('publictransport')->insert($stamp([['PropertyName' => $name, 'TransportType' => 'Bus', 'TransportName' => "$city Bus Park", 'Distance' => '1 km']]));
            DB::table('childrenpolicies')->insert($stamp([['PropertyName' => $name, 'Policy' => 'Children of all ages are welcome.']]));
            DB::table('childandextrabed')->insert($stamp([['PropertyName' => $name, 'YearsRange' => '0 - 2 years', 'ExtraCondition' => 'Cot upon request: free']]));
            DB::table('nearbyrestaurants')->insert($stamp([['PropertyName' => $name, 'Place' => 'Mama Rose Kitchen', 'Distance' => '300 m']]));
            DB::table('nearbymarkets')->insert($stamp([['PropertyName' => $name, 'Place' => "$city Market", 'Distance' => '800 m']]));
            DB::table('nearbysupermarkets')->insert($stamp([['PropertyName' => $name, 'Place' => 'Fresh Mart', 'Distance' => '500 m']]));
            DB::table('naturalbeauty')->insert($stamp([['PropertyName' => $name, 'Place' => 'Riverside Forest Walk', 'Distance' => '5 km']]));
            foreach ($amenityTables as $table => $items) {
                DB::table($table)->insert($stamp(array_map(fn ($a) => ['PropertyName' => $name, 'Amenity' => $a], $items)));
            }
        }

        // ---- Home guest houses ----
        $guest = [['Mango Tree Homestay', 'Kampala', 4.4, 60000], ['Source of the Nile Home', 'Jinja', 4.6, 75000],
                  ['Crater Lakes Farmhouse', 'Fort Portal', 4.8, 90000], ['Bunyonyi Family Home', 'Kabale', 4.3, 55000]];
        foreach ($guest as $i => [$name, $city, $rating, $price]) {
            DB::table('homeguesthouses')->insert($stamp([[
                'GuestHouseName' => $name, 'City' => $city, 'Place' => $city, 'Rating' => $rating, 'Prices' => $price,
                'NoOfRooms' => 3 + $i, 'Availability' => 'Available', 'Offered' => $i === 0 ? 'Offered' : 'No',
                'EmailAddress' => 'host' . ($i + 1) . '@homestay.test', 'ExactLocation' => "Village Road $i, $city",
                'Coordinates' => '0.3,32.5', 'PhotoUrl' => 2, 'BedBreakFast' => 'Yes', 'CreditCard' => 'No', 'Parking' => 'Free',
                'Description' => "A warm family home in $city with home-cooked meals and a quiet garden.",
            ]]));
            DB::table('houserules')->insert($stamp([['PropertyName' => $name, 'CheckIn' => 'From 12:00', 'CheckOut' => 'Until 10:00',
                'Pets' => 'Allowed on request', 'AgeRestriction' => 'None', 'CardAcceptability' => 'Cash, Mobile Money',
                'PaymentCancelation' => 'Free cancellation up to 24 hours before arrival.']]));
            DB::table('reasonstostayatproperty')->insert($stamp([['PropertyName' => $name, 'Reason' => 'Authentic local experience']]));
            DB::table('popularamenities')->insert($stamp([['PropertyName' => $name, 'Wifi' => 'Yes', 'BreakFast' => 'Yes', 'Restaurant' => 'No',
                'Bar' => 'No', 'SwimmingPool' => 'No', 'FitnessCenter' => 'No', 'Spa' => 'No', 'Shuttle' => 'No', 'NoSmokingRoom' => 'Yes',
                'TeaMaker' => 'Yes', 'Forex' => 'No']]));
            DB::table('roomswith')->insert($stamp([['PropertyName' => $name, 'GardenView' => 'Yes', 'CityView' => 'No']]));
            DB::table('propertydescription')->insert($stamp([['PropertyName' => $name, 'Description' => "Stay with a local family in $city."]]));
        }

        // ---- Rental cars ----
        $cars = [['UBA 101A', 'Pearl Car Hire', 'SUV', 'Toyota Land Cruiser Prado', 250000, 'Kampala'],
                 ['UBB 202B', 'Pearl Car Hire', 'Saloon', 'Toyota Premio', 120000, 'Kampala'],
                 ['UBC 303C', 'Safari Wheels', 'Van', 'Toyota Hiace', 200000, 'Entebbe'],
                 ['UBD 404D', 'Safari Wheels', 'SUV', 'Toyota RAV4', 180000, 'Jinja'],
                 ['UBE 505E', 'Gorilla Drives', 'Pickup', 'Toyota Hilux', 220000, 'Kabale'],
                 ['UBF 606F', 'Gorilla Drives', 'Saloon', 'Honda Fit', 90000, 'Mbarara']];
        foreach ($cars as $i => [$no, $company, $cat, $spec, $price, $city]) {
            DB::table('carrentals')->insert($stamp([[
                'TaxiNo' => $no, 'DriverName' => $company, 'Company' => $company, 'CarCategory' => $cat, 'CarSpec' => $spec,
                'Price' => $price, 'PricePerDay' => $price, 'City' => $city, 'CurrentLocation' => $city, 'Approved' => 'Approved',
                'Availability' => 'Available', 'Offered' => $i === 0 ? 'Offered' : 'No', 'Ratings' => 4.0 + ($i % 5) / 5,
                'Doors' => '4', 'Passengers' => $cat === 'Van' ? '12' : '5', 'Laggages' => '3', 'Automatic' => 'Yes', 'DriverAge' => 25,
                'EmailAddress' => strtolower(str_replace(' ', '', $company)) . '@cars.test', 'TelephoneNumber' => '+25670012345' . $i,
                'PhotoUri' => 2, 'SearchTerms' => "$spec $cat $city $company", 'Repayments' => 0,
                'Description' => "Well-maintained $spec available with or without a driver.",
            ]]));
            DB::table('carincludes')->insert($stamp([[
                'Car' => $no, 'Company' => $company, 'Bluetooth' => 'Yes', 'GPSNavigation' => 'Yes', 'AudioInput' => 'Yes',
                'USBInput' => 'Yes', 'FMRadio' => 'Yes', 'BackCamera' => $i % 2 ? 'No' : 'Yes', 'AllWheelDrive' => $cat === 'SUV' ? 'Yes' : 'No',
                'SunRoof' => 'No', 'PopupRoof' => $cat === 'Van' ? 'Yes' : 'No', 'TV' => 'No', 'AllDegreeCamera' => 'No',
            ]]));
        }

        // ---- Airport taxis ----
        $taxis = [['UAT 111A', 'Joseph Okello', 'Saloon', 'Toyota Premio', 80000], ['UAT 222B', 'Grace Namuli', 'SUV', 'Toyota Harrier', 120000],
                  ['UAT 333C', 'Peter Mugisha', 'Van', 'Toyota Noah', 150000]];
        foreach ($taxis as $i => [$no, $driver, $cat, $spec, $price]) {
            DB::table('airporttaxi')->insert($stamp([[
                'TaxiNo' => $no, 'DriverName' => $driver, 'CarCategory' => $cat, 'CarSpec' => $spec, 'Price' => $price,
                'CurrentLocation' => 'Entebbe Airport', 'Availability' => 'Available', 'Offered' => $i === 0 ? 'Offered' : 'No',
                'Ratings' => 4.5, 'Doors' => '4', 'Passengers' => $cat === 'Van' ? '7' : '4', 'Laggages' => '3', 'Automatic' => 'Yes',
                'EmailAddress' => 'taxi' . ($i + 1) . '@taxis.test', 'PhotoUri' => 1, 'SearchTerms' => "$spec $cat airport taxi",
                'Description' => "Reliable airport transfers in a clean $spec.",
            ]]));
        }
        DB::table('taxitrip')->insert($stamp([
            ['Froms' => 'Entebbe Airport', 'Tos' => 'Kampala', 'Price' => 80000],
            ['Froms' => 'Entebbe Airport', 'Tos' => 'Entebbe Town', 'Price' => 30000],
            ['Froms' => 'Entebbe Airport', 'Tos' => 'Jinja', 'Price' => 250000],
            ['Froms' => 'Entebbe Airport', 'Tos' => 'Mukono', 'Price' => 120000],
        ]));

        // ---- Ambulances ----
        $amb = [['UAM 501A', 'Rapid Response Medics', 'Advanced Life Support', 'Kampala'],
                ['UAM 502B', 'Rapid Response Medics', 'Basic Life Support', 'Entebbe'],
                ['UAM 503C', 'Nile Emergency Care', 'Patient Transport', 'Jinja']];
        foreach ($amb as $i => [$no, $op, $cat, $city]) {
            DB::table('ambulances')->insert($stamp([[
                'TaxiNo' => $no, 'DriverName' => $op, 'AmbulanceCategory' => $cat, 'City' => $city, 'CurrentLocation' => $city,
                'Price' => 0, 'Availability' => 'Available', 'Offered' => $i === 0 ? 'Offered' : 'No', 'Approved' => 'Approved',
                'Ratings' => 4.7, 'EmailAddress' => 'dispatch' . ($i + 1) . '@ambulance.test', 'TelephoneNumber' => '+25680000000' . $i,
                'PhotoUri' => 1, 'CarSpec' => 'Toyota Land Cruiser Ambulance', 'SearchTerms' => "$cat ambulance $city", 'Repayments' => 0,
            ]]));
            DB::table('ambulanceaccessories')->insert($stamp([[
                'Ambulance' => $no, 'Paramdics' => 'Yes', 'JumpBag' => 'Yes', 'SunctionUnit' => 'Yes', 'ECGMonitor' => $i === 0 ? 'Yes' : 'No',
                'SpinalBoad' => 'Yes', 'CervicalCollar' => 'Yes', 'MedicationBag' => 'Yes', 'Ventilator' => $i === 0 ? 'Yes' : 'No',
                'Haemoglucometer' => 'Yes', 'WheeledCot' => 'Yes', 'MiscelaniousItems' => 'Oxygen cylinder, first aid kit, blankets.',
            ]]));
        }

        // ---- Blogs ----
        $blogs = [['Top 5 Weekend Getaways from Kampala', 'Short drives, big adventures.'],
                  ['Rafting the Nile in Jinja: A First-Timer\'s Guide', 'What to pack and what to expect.'],
                  ['Gorilla Trekking on a Budget', 'Plan ahead and save on permits and stays.'],
                  ['Exploring the Crater Lakes of Fort Portal', 'Hidden lakes and tea plantations.']];
        foreach ($blogs as $i => [$title, $short]) {
            DB::table('blogs')->insert($stamp([['id' => $i + 1, 'BlogTitle' => $title, 'BlogShortDescription' => $short,
                'Description' => "$short This guide covers getting there, where to stay and the best things to do.",
                'Date' => now()->subDays(10 * ($i + 1))->toDateString()]]));
        }
        DB::table('blogcomments')->insert($stamp([
            ['blog_model_id' => 1, 'BlogID' => 1, 'Comment' => 'Loved this list, visiting Jinja next month!', 'NameOfCommenter' => 'Sarah Achieng',
             'CommentEmail' => 'sarah@example.test', 'Approval' => 'Approved', 'CommentDate' => now()->toDateString()],
            ['blog_model_id' => 2, 'BlogID' => 2, 'Comment' => 'Very helpful tips for first-timers.', 'NameOfCommenter' => 'Tom Kato',
             'CommentEmail' => 'tom@example.test', 'Approval' => 'Approved', 'CommentDate' => now()->toDateString()],
        ]));

        DB::table('clientfeedback')->insert($stamp([
            ['ClientName' => 'Amina Nakato', 'ClientEmail' => 'amina@example.test', 'ClientJob' => 'Tour Guide', 'ClientCompany' => 'Pearl Tours',
             'FeedBack' => 'Booking our group stay was quick and the hotel was exactly as described.', 'Approved' => 'Approved'],
            ['ClientName' => 'David Ssali', 'ClientEmail' => 'david@example.test', 'ClientJob' => 'Engineer', 'ClientCompany' => 'Self',
             'FeedBack' => 'The airport taxi was waiting when I landed. Great service.', 'Approved' => 'Approved'],
        ]));

        // ---- Tourism, events, packages ----
        DB::table('tourismsites')->insert($stamp([
            ['SiteName' => 'Source of the Nile', 'City' => 'Jinja', 'Country' => 'Uganda', 'PhotoUri' => 1, 'SearchKeyWords' => 'nile jinja river',
             'ShortDescription' => 'Where the Nile begins its journey north.', 'Description' => 'Take a boat ride to the source of the Nile and enjoy the riverside.'],
            ['SiteName' => 'Murchison Falls', 'City' => 'Masindi', 'Country' => 'Uganda', 'PhotoUri' => 1, 'SearchKeyWords' => 'waterfall park safari',
             'ShortDescription' => 'The Nile squeezes through a 7 m gorge.', 'Description' => 'Game drives, boat cruises and a hike to the top of the falls.'],
            ['SiteName' => 'Lake Bunyonyi', 'City' => 'Kabale', 'Country' => 'Uganda', 'PhotoUri' => 1, 'SearchKeyWords' => 'lake islands canoe',
             'ShortDescription' => 'A lake of 29 islands.', 'Description' => 'Canoe between islands and relax by the terraced hills.'],
        ]));
        DB::table('events')->insert($stamp([
            ['EventName' => 'Kampala City Festival', 'City' => 'Kampala', 'Country' => 'Uganda', 'PhotoUri' => 1, 'SearchKeyWords' => 'festival music',
             'StartDate' => now()->addDays(20)->toDateString(), 'EndDate' => now()->addDays(21)->toDateString(),
             'ShortDescription' => 'Music, food and culture in the streets.', 'Description' => 'A day of parades, live music and street food.'],
            ['EventName' => 'Nile Rafting Challenge', 'City' => 'Jinja', 'Country' => 'Uganda', 'PhotoUri' => 1, 'SearchKeyWords' => 'rafting sport',
             'StartDate' => now()->addDays(45)->toDateString(), 'EndDate' => now()->addDays(46)->toDateString(),
             'ShortDescription' => 'Teams race down the Nile rapids.', 'Description' => 'Watch or join teams racing grade 5 rapids.'],
        ]));
        DB::table('tourpackage')->insert($stamp([
            ['PackageName' => '3-Day Jinja Adventure', 'TravelCompany' => 'Pearl Tours', 'City' => 'Jinja', 'TripDays' => 3, 'TravelHours' => '2 hrs',
             'TravelDate' => now()->addDays(14)->toDateString(), 'PackageFees2' => 650000, 'PhotoUri' => 1, 'EmailAddress' => 'tours@pearltours.test',
             'DestinationsVisted' => 'Source of the Nile, Bujagali, Itanda Falls', 'ServicesOffered' => 'Transport, accommodation, meals',
             'ExtraActivites' => 'Rafting, quad biking', 'SearchTerms' => 'jinja rafting adventure'],
            ['PackageName' => '4-Day Gorilla Trek', 'TravelCompany' => 'Gorilla Drives', 'City' => 'Kabale', 'TripDays' => 4, 'TravelHours' => '8 hrs',
             'TravelDate' => now()->addDays(30)->toDateString(), 'PackageFees2' => 4500000, 'PhotoUri' => 1, 'EmailAddress' => 'trek@gorilladrives.test',
             'DestinationsVisted' => 'Bwindi, Lake Bunyonyi', 'ServicesOffered' => 'Transport, permits, lodge stay',
             'ExtraActivites' => 'Canoeing, community walk', 'SearchTerms' => 'gorilla bwindi trek'],
        ]));

        // A couple of bookings for the demo user
        DB::table('cart')->insert($stamp([
            ['Facility' => 'Nile Breeze Hotel', 'ProductName' => 'Deluxe Double', 'User' => 'demo@panobooking.test', 'UserNames' => 'Demo Traveller',
             'Quantity' => 1, 'Cost' => 504000, 'OrderCode' => 'N/A', 'DatesFrom' => now()->addDays(7)->toDateString(),
             'DatesTo' => now()->addDays(9)->toDateString(), 'BookingType' => 'Stays', 'Currency' => 'UGX', 'CurrencyAmount' => 504000,
             'Descriptions' => 'Has 0 Children', 'Confirmed' => 'Confirmed'],
            ['Facility' => 'Pearl Car Hire', 'ProductName' => 'UBA 101A', 'User' => 'demo@panobooking.test', 'UserNames' => 'Demo Traveller',
             'Quantity' => 1, 'Cost' => 750000, 'OrderCode' => 'N/A', 'DatesFrom' => now()->addDays(7)->toDateString(),
             'DatesTo' => now()->addDays(10)->toDateString(), 'BookingType' => 'Car Rental', 'Currency' => 'UGX', 'CurrencyAmount' => 750000,
             'Descriptions' => 'I will Pick the Car at Entebbe Airport.', 'Confirmed' => 'Pending'],
        ]));

        // Admin backend (/admin/login), sample data only: developer@, admin@ and staff@panobooking.test, password123.
        // Real deployments get their accounts from AdminAccountsSeeder (ADMIN_* / DEVELOPER_* env vars).
        foreach (['developer' => 'Demo Developer', 'admin' => 'Demo Admin', 'staff' => 'Demo Staff'] as $role => $name) {
            DB::table('users')->updateOrInsert(
                ['email' => "{$role}@panobooking.test"],
                ['name' => $name, 'password' => Hash::make('password123'), 'role' => $role, 'created_at' => $now, 'updated_at' => $now],
            );
        }
    }
}
