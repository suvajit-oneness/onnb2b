@extends('front.profile.layouts.app')

@section('profile-content')
<style>
    .border td {
        border: 1px solid #ddd;
    }
    table,
    table p {
        font-size: 12px;
    }
    table h3 {
        font-size: 16px;
    }
</style>

@php
    if (!function_exists('amountInWords')) {
        // number to word
        function amountInWords(float $number)
        {
            $decimal = round($number - ($no = floor($number)), 2) * 100;
            $hundred = null;
            $digits_length = strlen($no);
            $i = 0;
            $str = array();
            $words = array(0 => '', 1 => 'one', 2 => 'two',
                3 => 'three', 4 => 'four', 5 => 'five', 6 => 'six',
                7 => 'seven', 8 => 'eight', 9 => 'nine',
                10 => 'ten', 11 => 'eleven', 12 => 'twelve',
                13 => 'thirteen', 14 => 'fourteen', 15 => 'fifteen',
                16 => 'sixteen', 17 => 'seventeen', 18 => 'eighteen',
                19 => 'nineteen', 20 => 'twenty', 30 => 'thirty',
                40 => 'forty', 50 => 'fifty', 60 => 'sixty',
                70 => 'seventy', 80 => 'eighty', 90 => 'ninety');
            $digits = array('', 'hundred','thousand','lakh', 'crore');
            while( $i < $digits_length ) {
                $divider = ($i == 2) ? 10 : 100;
                $number = floor($no % $divider);
                $no = floor($no / $divider);
                $i += $divider == 10 ? 1 : 2;
                if ($number) {
                    $plural = (($counter = count($str)) && $number > 9) ? 's' : null;
                    $hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
                    $str [] = ($number < 21) ? $words[$number].' '. $digits[$counter]. $plural.' '.$hundred:$words[floor($number / 10) * 10].' '.$words[$number % 10]. ' '.$digits[$counter].$plural.' '.$hundred;
                } else $str[] = null;
            }
            $Rupees = implode('', array_reverse($str));
            $paise = ($decimal > 0) ? "." . ($words[$decimal / 10] . " " . $words[$decimal % 10]) . ' Paise' : '';
            return ($Rupees ? $Rupees . 'Rupees ' : '') . $paise;
        }
    }
@endphp

<div class="col-sm-9">
    <div class="profile-card">
        <h3>Order Invoice</h3>
        <div class="w-100 text-right mb-4">
            <a href="javascript: void(0)" type="button" class="btn btn-primary btn-sm" onclick="printInvoice()">Print</a>
        </div>

        <div class="printDiv">
            <table border="1" class="table-bordered" style="width: 100%; border-collapse: collapse;"
                cellpadding="0" cellspacing="0">
                <tr>
                    <td>
                        <table border="1" style="width: 100%; border-collapse: collapse;"
                            class="table-bordered" cellpadding="10" cellspacing="0">
                            <tr>
                                <td style="width: 35%;" rowspan="2">
                                    <p style="margin: 0">Sender</p>
                                    <p style="margin: 0;"><strong>cozyworld</strong><br />C-158, Amar Gyan Industrial Estate, Khopat,Thane Maharashtra - 400601 Maharashtra (27) ,India</p>
                                    <p style="margin: 0;">Ph No: 9007015173</p>
                                    <p style="margin: 0;">GSTIN:</p>
                                </td>
                                <td style="width: 35%;">
                                    <p style="margin: 0;">Invoice
                                        Code:<br /><strong>I1923CO000000429</strong></p>
                                </td>
                                <td style="width: 30%;">
                                    <p style="margin: 0;">Invoice Date:<br /><strong>{{date('j-M-Y', strtotime($data->created_at))}}</strong>
                                    </p>
                                </td>
                            </tr>
                            <tr>
                                <td align="center">
                                    <p style="margin: 0;">Order No:
                                        {{ $data->order_no }}</p>
                                    <p style="margin: 0;">Order Date: {{date('j-M-Y', strtotime($data->created_at))}}</p>
                                </td>
                                <td>
                                    <p style="margin: 0;">Portal: <strong>MYNTRA_PPMP</strong></p>
                                    <p style="margin: 0;">Payment Mode</p>
                                    <p style="margin: 0;"><strong>PREPAID</strong></p>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <p style="margin: 0;">Bill To: <br />
                                        <strong>{{$data->fname.' '.$data->lname}}</strong>
                                        <br />{{$data->billing_address}}, {{$data->billing_landmark}}, {{$data->billing_pin.', '.$data->billing_city.', '.$data->billing_state.', '.$data->billing_country}}</p>
                                    <p style="margin: 0;">T : {{$data->mobile}}</p>
                                </td>
                                <td>
                                    <p style="margin: 0;">Ship To: <br />
                                        <strong>{{$data->fname.' '.$data->lname}}</strong>
                                        <br />{{$data->shipping_address}}, {{$data->shipping_landmark}}, {{$data->shipping_pin.', '.$data->shipping_city.', '.$data->shipping_state.', '.$data->shipping_country}}</p>
                                    <p style="margin: 0;">T : {{$data->mobile}}</p>
                                </td>
                                <td>
                                    <p style="margin: 0;">Dispatch Through<br /><strong>EK</strong><br />AWB
                                        No<br /> MYNP0032032743</p>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td>
                        <table border="1" style="width: 100%; border-collapse: collapse;"
                            class="table-bordered" cellpadding="10" cellspacing="0">
                            <tr>
                                <th align="center">SI No.</th>
                                <th align="center">Descriptions of Goods</th>
                                <th align="center">Part No</th>
                                <th align="center">Qty</th>
                                <th align="center">Rate</th>
                                <th align="center">Taxable Value<br />(INR)</th>
                                <th align="center">IGST (INR)</th>
                                <th align="center">Amount (INR)</th>
                            </tr>
                            @foreach($data->orderProducts as $productKey => $productVal)

                            @php
                                $rate = 0;
                                $tax = 0;
                                $gst = 5;
                                $amount = 0;

                                $amount = $productVal->offer_price * $productVal->qty;
                                $amountShow = sprintf("%.2f", $amount);

                                $gstShow = sprintf("%.3f", $gst);
                                $taxInTotalAmount = ($gst / 100) * $amount;
                                $withoutTaxAmount = $amount - $taxInTotalAmount;
                            @endphp

                            <tr>
                                <td align="center">1</td>
                                <td><strong>{{ $productVal->product_name }}</strong></td>
                                <td>GENX_GUSTO_BRF_OE_AST_7 5_3PC</td>
                                <td align="center">{{ $productVal->qty }}</td>
                                <td align="right">{{ sprintf('%.2f', $productVal->offer_price) }}</td>
                                <td align="right">{{ $withoutTaxAmount }}</td>
                                <td align="right">{{ $taxInTotalAmount }}<br />({{ $gstShow }}%)</td>
                                <td align="right">{{ $amountShow }}</td>
                            </tr>
                            @endforeach
                        </table>
                    </td>
                </tr>
                <tr>
                    <td>
                        <table border="0" style="width: 100%; border-collapse: collapse;"
                            class="" cellpadding="10" cellspacing="0">
                            <tr>
                                <td valign="top">
                                    <p style="margin: 0;">Amount Chargeable (in words)<br /><strong>INR {{ amountInWords($data->final_amount) }} Only Tax is payable on reverse charge basis: No</strong></p>
                                </td>
                                <td align="right" valign="top">
                                    <h4 style="margin: 0; font-size: 14px;">E. & O.E</h4>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 50%;">
                                    <p style="margin: 0;"><u>Declaration</u></p>
                                    <p style="margin: 0;">1. All claims, if any, for shortages or damages
                                        must be reported to customer service on the day of delivery through
                                        the contact us page on the web store 2. All Disputes are subject to
                                        Maharashtra (27) jurisdiction only.</p>
                                </td>
                                <td align="center"
                                    style="width: 50%; border-top: 1px solid #000; border-left: 1px solid #000;">
                                    <h3>ONN International</h3>
                                    <h3>Authorised Signatory</h3>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td>
                        <table border="0" style="width: 100%; border-collapse: collapse;"
                            class="" cellpadding="10" cellspacing="0">
                            <tr>
                                <td style="width: 49%;">
                                    <p style="margin: 0;"><strong>Bill By</strong>
                                </td>
                                <td style="width: 2%;" align="center">:</td>
                                <td style="width: 49%;"></td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</div>
@endsection

@section('script')
    <script src="{{ asset('js/printThis.js') }}"></script>

    <script>
        function printInvoice() {
            $('.printDiv').printThis();
        }
    </script>
@endsection
