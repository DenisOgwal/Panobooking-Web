<?php

namespace Tests\Feature;

use App\Livewire\BlogDetailComponent;
use App\Livewire\ContactPage;
use App\Livewire\TourPackageDetails;
use App\Support\CustomerAuth;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Livewire\Livewire;
use Tests\TestCase;

class ContentPagesTest extends TestCase
{
    use RefreshDatabase;

    protected $seed = true;

    public function test_new_blog_comments_wait_for_approval(): void
    {
        Livewire::withQueryParams(['blogslug' => 1])
            ->test(BlogDetailComponent::class)
            ->set('inputname', 'Test Reader')
            ->set('email', 'reader@example.test')
            ->set('comments', 'A really useful guide, thanks for sharing it.')
            ->call('comment')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('blogcomments', ['blog_model_id' => 1, 'NameOfCommenter' => 'Test Reader', 'Approval' => null]);
        $this->get('/BlogDetail?blogslug=1')->assertOk()->assertDontSee('A really useful guide');
    }

    public function test_contact_form_stores_the_message(): void
    {
        Livewire::test(ContactPage::class)
            ->set('inputname', 'Jane Doe')
            ->set('email', 'jane@example.test')
            ->set('contactinfo', 'short')
            ->call('sendmessage')
            ->assertHasErrors('contactinfo')
            ->set('contactinfo', 'I would like to list my guest house in Jinja. Who should I talk to?')
            ->call('sendmessage')
            ->assertHasNoErrors()
            ->assertSet('inputname', '');

        $this->assertDatabaseHas('messages', ['Names' => 'Jane Doe', 'Email' => 'jane@example.test']);
    }

    public function test_tour_reservation_charges_per_traveller(): void
    {
        Http::fake();
        session([CustomerAuth::SESSION_KEY => ['Email' => 'demo@panobooking.test', 'FullNames' => 'Demo Traveller']]);

        // Package 1: 3-Day Jinja Adventure, UGX 650,000 per person
        Livewire::withQueryParams(['searchslug' => 1])
            ->test(TourPackageDetails::class)
            ->set('travellers', 3)
            ->assertSet('total', 1950000.0)
            ->call('submitbooking')
            ->assertRedirect('/BookingSuccess');

        $row = DB::table('cart')->where('BookingType', 'Tour Package')->orderByDesc('IDs')->first();
        $this->assertEquals(3, $row->Quantity);
        $this->assertEquals(1950000, $row->Cost);
    }

    public function test_information_pages_keep_their_copy(): void
    {
        $this->get('/privacypolicy')->assertOk()->assertSee('A. OVERVIEW')->assertSee('Data Retention and Deletion');
        $this->get('/TermsAndConditions')->assertOk()->assertSee('Terms and conditions');
        $this->get('/Faqs')->assertOk()->assertSee('accordion', false)->assertSee('UMF House');
    }

    public function test_missing_content_returns_404(): void
    {
        $this->get('/BlogDetail?blogslug=999')->assertNotFound();
        $this->get('/TourPackageDetails?searchslug=999')->assertNotFound();
        $this->get('/TourismSiteDetails?searchslug=999')->assertNotFound();
        $this->get('/EventsDetails?searchslug=999')->assertNotFound();
    }
}
