@extends('layouts.app')
@section('content')
<div id="viewport">
    @include('layouts.sidebar')
    <!-- Content -->
    <div id="content">
      @include('layouts.header')
      <div class="container-fluid">
        <div class="bb-light fix-header">
                <div class="header-block header-block-w-p">
                    <h1>{{translate('Invoices')}}</h1>
                    <p>{{translate('Here are details of Your all transations.')}}.</p>
                </div>
            </div>
        
        <div class="i-content-block price-level">
          <table class="table table-hover datatable">
            <thead>
              <tr>
                <th width="30%">Invoice Ref.</th>
                <th>Issuing Date</th>
                <th>Amount</th>
                <th>Status</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($invoices as $invoice)
                  <tr class="">
                    <td>
                      <strong>{{$invoice->invoice_id}}</strong>
                    </td>
                    <td>{{ $invoice->created_at->format('Y-m-d')  }}</td>
                    <td>{{$invoice->price_payed}}€</td>
                    <td><span class="label-success">Paid</span></td>
                    <td>
                        @if(!$invoice->elorus_permalink)
                        <a href="{{ route('pdfview',['id'=>$invoice->id]) }}" target="_blank" data-position="bottom" data-tooltip="Download">
                        <i class="fa fa-download"></i>
                        <strong> Download</strong>
                        </a>
                        @else
                        <a href="{{ $invoice->elorus_permalink }}" target="_blank" data-position="bottom" data-tooltip="Download">
                          <i class="fa fa-download"></i>
                          <strong> Download</strong>
                          </a>
                        @endif
                    </td>

                  </tr>
              @endforeach
            </tbody>
          </table>
        </div>
       </div>
      </div>
    </div>
  </div>
@endsection

@section('pagespecificscripts')

<script type="text/javascript">

    $( document ).ready(function(event) {

    });

</script>

@endsection