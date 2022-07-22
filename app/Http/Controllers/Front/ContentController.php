<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Interfaces\ContentInterface;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ContentController extends Controller
{
    public function __construct(ContentInterface $contentRepository) 
    {
        $this->contentRepository = $contentRepository;
    }

    public function termDetails(Request $request)
    {
        $data = $this->contentRepository->termDetails();
        $page = 'Terms and Consitions';

        if ($data) {
            return view('front.content.index', compact('data', 'page'));
        } else {
            return view('front.404');
        }
    }

    public function privacyDetails(Request $request)
    {
        $data = $this->contentRepository->privacyDetails();
        $page = 'Privacy statement';

        if ($data) {
            return view('front.content.index', compact('data', 'page'));
        } else {
            return view('front.404');
        }
    }

    public function securityDetails(Request $request)
    {
        $data = $this->contentRepository->securityDetails();
        $page = 'Security';

        if ($data) {
            return view('front.content.index', compact('data', 'page'));
        } else {
            return view('front.404');
        }
    }

    public function disclaimerDetails(Request $request)
    {
        $data = $this->contentRepository->disclaimerDetails();
        $page = 'Disclaimer';

        if ($data) {
            return view('front.content.index', compact('data', 'page'));
        } else {
            return view('front.404');
        }
    }

    public function shippingDetails(Request $request)
    {
        $data = $this->contentRepository->shippingDetails();
        $page = 'Shipping & Delivery';

        if ($data) {
            return view('front.content.index', compact('data', 'page'));
        } else {
            return view('front.404');
        }
    }

    public function paymentDetails(Request $request)
    {
        $data = $this->contentRepository->paymentDetails();
        $page = 'Payment, Voucher & Promotions';

        if ($data) {
            return view('front.content.index', compact('data', 'page'));
        } else {
            return view('front.404');
        }
    }

    public function returnDetails(Request $request)
    {
        $data = $this->contentRepository->returnDetails();
        $page = 'Returns Policy';

        if ($data) {
            return view('front.content.index', compact('data', 'page'));
        } else {
            return view('front.404');
        }
    }

    public function refundDetails(Request $request)
    {
        $data = $this->contentRepository->refundDetails();
        $page = 'Refund & Cancellation Policy';

        if ($data) {
            return view('front.content.index', compact('data', 'page'));
        } else {
            return view('front.404');
        }
    }

    public function serviceDetails(Request $request)
    {
        $data = $this->contentRepository->serviceDetails();
        $page = 'Service & Contact';

        if ($data) {
            return view('front.content.index', compact('data', 'page'));
        } else {
            return view('front.404');
        }
    }
}
