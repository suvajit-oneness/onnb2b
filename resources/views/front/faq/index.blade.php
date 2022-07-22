@extends('layouts.app')

@section('page', 'FAQ')

@section('content')
<section class="cart-header mb-3 mb-sm-5 mt-5">
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <h4>Frequently Asked Question</h4>
            </div>
        </div>
    </div>
</section>

<section class="cart-wrapper">
    <div class="container">
        <div class="row">
            <div class="col-sm-3">
                <ul class="account-list mt-0">
                    <li>
                        <span><strong>Quick Links</strong></span>
                        <ul>
                            <li><a href="{{route('front.faq.index')}}">FAQ</a></li>
                            <li><a href="{{route('front.user.order')}}">My Shopping</a></li>
                            <li><a href="{{route('front.content.shipping')}}">Shipping & Delivery</a></li>
                            <li><a href="{{route('front.content.payment')}}">Payment, Voucher & Promotions</a></li>
                            <li><a href="{{route('front.content.return')}}">Returns Policy</a></li>
                            <li><a href="{{route('front.content.refund')}}">Refund & Cancellation Policy</a></li>
                            <li><a href="{{route('front.content.service')}}">Service & Contact</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
            <div class="col-sm-8">
                <div class="cms_context">
                    {{-- <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque ipsum nisi, dapibus in odio id, fringilla fringilla lectus. Etiam efficitur libero ut nulla aliquet tristique. In ac dignissim est. Interdum et malesuada fames ac ante ipsum primis in faucibus. Donec egestas magna massa, a accumsan dui commodo eu. Vestibulum nunc leo, porta laoreet ullamcorper et, vestibulum at tellus. Nullam nec lectus ac leo porta tristique. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia curae; Nunc scelerisque nulla consequat, blandit ligula sit amet, elementum urna.</p>
                    <p>Aenean eget orci tortor. Interdum et malesuada fames ac ante ipsum primis in faucibus. Duis convallis consequat orci, in hendrerit tellus pretium at. Phasellus semper ornare feugiat. Ut ullamcorper molestie justo sed ultricies. Vestibulum lacinia et sem tempor finibus. Mauris sit amet imperdiet risus. Nullam tellus lorem, venenatis ut auctor vestibulum, vulputate a mi. In hac habitasse platea dictumst. Curabitur turpis libero, vehicula sit amet metus vel, molestie rutrum est. Nam feugiat, ligula non aliquet finibus, massa massa dictum ex, quis auctor libero mi id justo.</p>
                    <p>Nam posuere sollicitudin massa imperdiet lacinia. Aliquam sodales sapien a turpis semper rutrum. Vivamus quis tincidunt justo. Aliquam erat volutpat. Donec scelerisque porttitor ligula id gravida. Praesent id maximus erat. Duis fringilla in neque at interdum. Aliquam egestas nunc convallis leo consequat, id congue ligula tincidunt. Donec id hendrerit enim, in consequat mauris. Quisque sodales metus in urna maximus interdum. In egestas turpis tellus, ac molestie mi sodales quis. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia curae;</p>
                    <p>Fusce feugiat sagittis orci, non feugiat lectus. Nunc egestas non felis in accumsan. Suspendisse dignissim euismod lorem at ornare. Maecenas ut massa libero. Fusce consectetur placerat ante, eget volutpat erat lobortis ac. In non porta erat. Nulla ut arcu venenatis, congue eros at, condimentum odio. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur lacus nisi, tristique in congue id, maximus a erat. Fusce vitae semper neque, a imperdiet ipsum. Sed ac orci augue. Phasellus faucibus est quis sodales sagittis. Nullam ac vulputate mi.</p>
                    <p>In dapibus leo metus, nec placerat quam laoreet eget. Fusce ut urna id tortor interdum tincidunt. In luctus hendrerit nibh sed vestibulum. Fusce efficitur vel tortor nec facilisis. Mauris condimentum porta posuere. Etiam est urna, mattis id commodo non, euismod id lacus. Vestibulum pharetra purus tortor, quis sodales mauris commodo eu. Ut nisi ex, interdum eget mauris sit amet, lobortis mattis lectus. Vestibulum scelerisque est vitae enim rutrum, ut mattis turpis condimentum. In hac habitasse platea dictumst. Maecenas interdum scelerisque vehicula.</p> --}}


                    @foreach ($data as $faqKey => $faqValue)
                    <h3 class="faq_heading">{!! $faqValue->question !!}</h3>
                    <div class="faq_content">
                        {!! $faqValue->answer !!}
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>
@endsection