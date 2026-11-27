<?php

namespace App\Http\Controllers\Api\V1;

use App\Listings\ListingForm;
use App\Models\BlogCommentCount;
use App\Models\BlogModel;
use App\Models\Contact;
use App\Models\FeedBackModel;
use Illuminate\Http\Request;

/**
 * Public submissions: contact messages, blog comments, testimonials and "list with us" forms.
 * Comments, testimonials and listings are saved unapproved and appear once an admin approves them.
 */
class FormsController extends ApiController
{
    /** POST /contact (same rules as the website's contact page) */
    public function contact(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|min:3|max:120',
            'email' => 'required|email|max:190',
            'message' => 'required|string|min:20|max:5000',
        ]);

        Contact::create(['Names' => $data['name'], 'Email' => $data['email'], 'Messages' => $data['message']]);

        return response()->json(['message' => 'Thanks for getting in touch! We will reply as soon as we can.'], 201);
    }

    /** POST /testimonials (the home page's "share your experience" form) */
    public function testimonial(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|min:3|max:120',
            'email' => 'required|email|max:190',
            'job' => 'nullable|string|max:120',
            'company' => 'required|string|max:120',
            'text' => 'required|string|min:20|max:2000',
        ]);

        FeedBackModel::create([
            'ClientName' => $data['name'], 'ClientEmail' => $data['email'], 'ClientJob' => $data['job'] ?? null,
            'ClientCompany' => $data['company'], 'FeedBack' => $data['text'],
        ]);

        return response()->json(['message' => 'Thanks! Your feedback will appear once it has been reviewed.'], 201);
    }

    /** POST /blog/{id}/comments */
    public function comment(Request $request, int $id)
    {
        $post = BlogModel::findOrFail($id);
        $data = $request->validate([
            'name' => 'required|string|min:3|max:120',
            'email' => 'required|email|max:190',
            'comment' => 'required|string|min:20|max:3000',
        ]);

        BlogCommentCount::create([
            'blog_model_id' => $post->id, 'BlogID' => $post->id,
            'Comment' => $data['comment'], 'NameOfCommenter' => $data['name'], 'CommentEmail' => $data['email'],
        ]);

        return response()->json(['message' => 'Thanks! Your comment will appear once it has been approved.'], 201);
    }

    /** POST /listings/{kind} (multipart, with a `photo` file); kind = property | car | ambulance */
    public function listing(Request $request, string $kind)
    {
        $class = ListingForm::forms()[$kind] ?? null;
        abort_if(! $class, 404);

        $record = app($class)->submit($request->all());

        return response()->json([
            'message' => 'Thanks! Your listing has been received and will appear once our team has approved it.',
            'data' => ['id' => $record->getKey(), 'kind' => $kind, 'status' => 'pending_approval'],
        ], 201);
    }
}
