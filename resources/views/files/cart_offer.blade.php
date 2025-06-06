@extends('layouts.app')
@section('pagespecificstyles')

<style>
  .card-footer ul.payments {
    
    display: flex;
    padding: 0px;
}

.card-footer ul.payments li {
    display: flex;
    margin: 5px;
}

.card-footer ul.payments li img {
    height: 25px;
    width: 36px;
}

input.qty-input {
    margin-bottom: 0;
    width: 50px;
}

.btn-red-delete{
  padding: 2px 10px !important;
}

.btn-red-full{
  width: 100%;
}


.btn-color-gold {
    background: #ffc439;
    color: #111;
}

</style>

@endsection
@section('content')
<div id="viewport">
    @include('layouts.sidebar')
    <!-- Content -->
    <div id="content">
      @include('layouts.header')
        <div class="container-fluid">
          
          <span style="display: inline-flex;" class="m-t-20">
            <button  class="btn btn-white redirect-click" data-redirect="{{route('shop-product')}}"><i class="fa fa fa-angle-left"></i></button>
            <h3 class="m-t-5 m-l-5">
              Checkout
            </h3>
          </span>

          <div class="row m-t-40 bt" style="margin-bottom: 100px;">
            
            
            <div class="col-xl-6 col-lg-6 col-md-6 m-t-40">

            <div class="card" >
              <div class="card-header">
                <h4>Billing Information</h4>
              </div>
              <div class="card-content">
                <div class="details-box">

                  <table style="background: transparent;">
                    <tbody>

                      <tr >
                        <td style="width: 60%; padding-bottom: 10px;">
                          <strong>{{translate('Name')}}:</strong>
                          <span>{{$user->name}}</span>
                        </td>
                        <td style="width: 60%; padding-bottom: 10px;">
                          <strong>{{translate('Country')}}:</strong>
                          <span>{{code_to_country(Auth::user()->country)}}</span>
                        </td>
                      </tr>
                      <tr>
                        <td>
                          <strong>{{translate('Email')}}:</strong>
                          <span>{{$user->email}}</span>
                        </td>
                        <td>
                          <strong>{{translate('VAT')}}:</strong>
                          <span>{{$group->name}} ({{$group->tax}}%)</span>
                        </td>
                      </tr>
                      
                    </tbody>
                  </table>

                </div>
              </div>

            </div>

            <div class="card" >
              <div class="card-header">
                <h4>Payment Method</h4>
              </div>
              <div class="card-content">
                <p>{{translate('When validating your payment you will automatically be redirected to the Stripe or Paypal website where you will be able to pay the amount due very easily.')}}</p>
              </div>
              <div class="card-footer">
                <ul class="payments">
                  <li><img src="https://resellers.ecutech.tech/assets/img/payments/visa.svg" alt="Visa"></li>
                  <li><img src="https://resellers.ecutech.tech/assets/img/payments/mastercard.svg" alt="Mastercard"></li>
                  <li><img src="https://resellers.ecutech.tech/assets/img/payments/amex.svg" alt="American Express"></li>
                  <li><img src="https://resellers.ecutech.tech/assets/img/payments/bancontact.svg" alt="Bancontact"></li>
                  <li><img src="https://resellers.ecutech.tech/assets/img/payments/alipay.svg" alt="Alipay"></li>
                  <li><img src="https://resellers.ecutech.tech/assets/img/payments/wechatpay.svg" alt="Wechat pay"></li>
                  <li><img src="https://resellers.ecutech.tech/assets/img/payments/ideal.svg" alt="Ideal"></li>
                  <li><img src="https://resellers.ecutech.tech/assets/img/payments/sofort.svg" alt="Klarna-Sofort"></li>
              </ul>
              </div>
            </div>

            </div>

            <div class="col-xl-6 col-lg-6 col-md-6 m-t-40">

              <div class="card" >
                <div class="card-header">
                  <h4>Cart</h4>
                  <input type="hidden" id="price_per_unit" value="{{$price->value}}" />
                  <input type="hidden" id="factor" value="{{$factor}}" />
                  <input type="hidden" id="tax" value="{{$tax}}" />
                </div>
                <div class="card-content">

                  <table class="table" id="bagItemsTable">
                    <thead>
                      <tr>
                        <th>{{translate('Item')}}</th>
                        <th>{{translate('Unit price')}}</th>
                        <th>{{translate('Qty')}}</th>
                          <th></th>
                      </tr>
                    </thead>
                    <tbody>
    
                        <tr>

                        <td>1 Tuning credit (reseller)</td>
                        
                        <td>{{$price->value}}€</td>

                        <td>
                            {{$credits_to_buy}}
                        </td>
                        {{-- <td>
                            <button type="button" id="remove-item" class="btn btn-red btn-red-delete" data-position="top" data-tooltip="Remove item">
                            <i class="fa fa-trash"></i></button>
                        </td> --}}
                        </tr>

                    </tbody>
                  </table>
                  
                  <table class="table">
                    <tbody>
                      <tr>
                        <td><p>{{__('Subtotal')}} :</p></td>
                        <td>€{{$credits_to_buy*$price->value}}</td>
                      </tr>
                      <tr>
                        <td><p>{{__('Adjustment')}} :</p></td>
                        <td>€0</td>
                      </tr>
                                      <tr>
                        <td><p>{{__('Tax')}} :</p></td>
                        <td>€{{$credits_to_buy*($tax/100)*$price->value}}</td>
                      </tr>
                      <tr>
                        <td><h4>{{__('Total Order')}} :</h4></td>
                        <td><h4>€{{ $credits_to_buy*$price->value +  $credits_to_buy*($tax/100)*$price->value }}</h4></td>
                      </tr>
                    </tbody>
                  </table>

                  @if(Auth::user()->group->stripe_active == 1)
                <form action="{{route('buy.offer')}}" method="POST">
                    @csrf
                    {{-- <input type="hidden" name="total_for_checkout" value="{{ $credits_to_buy*$price->value +  $credits_to_buy*($tax/100)*$price->value }}" > --}}
                    <input type="hidden" name="file_id" value="{{$file_id}}" >
                    <input type="hidden" name="total_credits_to_submit" value="{{$credits_to_buy}}" >
                    <input type="hidden" name="credits_for_checkout" value="{{$credits_for_checkout}}" >
                    <input type="hidden" name="type" value="stripe" >
                    {{-- <input type="hidden" name="unit_price_for_checkout" value="{{$price->value + ($tax/100)*$price->value}}" > --}}
                    <button class="btn btn-red btn-red-full" type="submit">{{__('Pay with Card')}}</button>
                </form>
                @endif

                @if(Auth::user()->group->viva_active == 1)
                <form action="{{route('buy.offer.viva')}}" method="POST" class="m-t-20">
                  @csrf
                  <input type="hidden" name="file_id" value="{{$file_id}}" >
                    <input type="hidden" name="total_credits_to_submit" value="{{$credits_to_buy}}" >
                    <input type="hidden" name="credits_for_checkout" value="{{$credits_for_checkout}}" >
                    <input type="hidden" name="type" value="viva" >
                  <button class="btn btn-viva btn-red-full" type="submit">
                    <img width="33%" data-testid="logo-img" src="https://downloads.intercomcdn.com/i/o/464635/50deae94aaf455091e46faee/4d1ca330ee42856a5f3683b9aff84c61.png" alt="Viva.com Support" class="max-h-8 contrast-80 inline">
                  </button>
                </form>
                @endif
                     
                  {{-- <form action="{{route('buy.offer')}}" method="POST">
                      @csrf
                      
                      <input type="hidden" name="total_credits_to_submit" value="{{$credits_to_buy}}" >
                      <input type="hidden" name="credits_for_checkout" value="{{$credits_for_checkout}}" >
                      <input type="hidden" name="file_id" value="{{$file_id}}" >
                      <input type="hidden" name="type" value="paypal" >
                      
                      <button class="btn  btn-color-gold btn-red-full m-t-10" type="submit">
                        <img width="5%" class="paypal-button-logo paypal-button-logo-pp paypal-button-logo-gold" src="data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjQiIGhlaWdodD0iMzIiIHZpZXdCb3g9IjAgMCAyNCAzMiIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiBwcmVzZXJ2ZUFzcGVjdFJhdGlvPSJ4TWluWU1pbiBtZWV0Ij4KICAgIDxwYXRoIGZpbGw9IiMwMDljZGUiIGQ9Ik0gMjAuOTA1IDkuNSBDIDIxLjE4NSA3LjQgMjAuOTA1IDYgMTkuNzgyIDQuNyBDIDE4LjU2NCAzLjMgMTYuNDExIDIuNiAxMy42OTcgMi42IEwgNS43MzkgMi42IEMgNS4yNzEgMi42IDQuNzEgMy4xIDQuNjE1IDMuNiBMIDEuMzM5IDI1LjggQyAxLjMzOSAyNi4yIDEuNjIgMjYuNyAyLjA4OCAyNi43IEwgNi45NTYgMjYuNyBMIDYuNjc1IDI4LjkgQyA2LjU4MSAyOS4zIDYuODYyIDI5LjYgNy4yMzYgMjkuNiBMIDExLjM1NiAyOS42IEMgMTEuODI1IDI5LjYgMTIuMjkyIDI5LjMgMTIuMzg2IDI4LjggTCAxMi4zODYgMjguNSBMIDEzLjIyOCAyMy4zIEwgMTMuMjI4IDIzLjEgQyAxMy4zMjIgMjIuNiAxMy43OSAyMi4yIDE0LjI1OCAyMi4yIEwgMTQuODIxIDIyLjIgQyAxOC44NDUgMjIuMiAyMS45MzUgMjAuNSAyMi44NzEgMTUuNSBDIDIzLjMzOSAxMy40IDIzLjE1MyAxMS43IDIyLjAyOSAxMC41IEMgMjEuNzQ4IDEwLjEgMjEuMjc5IDkuOCAyMC45MDUgOS41IEwgMjAuOTA1IDkuNSI+PC9wYXRoPgogICAgPHBhdGggZmlsbD0iIzAxMjE2OSIgZD0iTSAyMC45MDUgOS41IEMgMjEuMTg1IDcuNCAyMC45MDUgNiAxOS43ODIgNC43IEMgMTguNTY0IDMuMyAxNi40MTEgMi42IDEzLjY5NyAyLjYgTCA1LjczOSAyLjYgQyA1LjI3MSAyLjYgNC43MSAzLjEgNC42MTUgMy42IEwgMS4zMzkgMjUuOCBDIDEuMzM5IDI2LjIgMS42MiAyNi43IDIuMDg4IDI2LjcgTCA2Ljk1NiAyNi43IEwgOC4yNjcgMTguNCBMIDguMTczIDE4LjcgQyA4LjI2NyAxOC4xIDguNzM1IDE3LjcgOS4yOTYgMTcuNyBMIDExLjYzNiAxNy43IEMgMTYuMjI0IDE3LjcgMTkuNzgyIDE1LjcgMjAuOTA1IDEwLjEgQyAyMC44MTIgOS44IDIwLjkwNSA5LjcgMjAuOTA1IDkuNSI+PC9wYXRoPgogICAgPHBhdGggZmlsbD0iIzAwMzA4NyIgZD0iTSA5LjQ4NSA5LjUgQyA5LjU3NyA5LjIgOS43NjUgOC45IDEwLjA0NiA4LjcgQyAxMC4yMzIgOC43IDEwLjMyNiA4LjYgMTAuNTEzIDguNiBMIDE2LjY5MiA4LjYgQyAxNy40NDIgOC42IDE4LjE4OSA4LjcgMTguNzUzIDguOCBDIDE4LjkzOSA4LjggMTkuMTI3IDguOCAxOS4zMTQgOC45IEMgMTkuNTAxIDkgMTkuNjg4IDkgMTkuNzgyIDkuMSBDIDE5Ljg3NSA5LjEgMTkuOTY4IDkuMSAyMC4wNjMgOS4xIEMgMjAuMzQzIDkuMiAyMC42MjQgOS40IDIwLjkwNSA5LjUgQyAyMS4xODUgNy40IDIwLjkwNSA2IDE5Ljc4MiA0LjYgQyAxOC42NTggMy4yIDE2LjUwNiAyLjYgMTMuNzkgMi42IEwgNS43MzkgMi42IEMgNS4yNzEgMi42IDQuNzEgMyA0LjYxNSAzLjYgTCAxLjMzOSAyNS44IEMgMS4zMzkgMjYuMiAxLjYyIDI2LjcgMi4wODggMjYuNyBMIDYuOTU2IDI2LjcgTCA4LjI2NyAxOC40IEwgOS40ODUgOS41IFoiPjwvcGF0aD4KPC9zdmc+Cg" alt="" aria-label="pp">
                        <img width="15%" class="paypal-button-logo paypal-button-logo-paypal paypal-button-logo-gold" src="data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTAwIiBoZWlnaHQ9IjMyIiB2aWV3Qm94PSIwIDAgMTAwIDMyIiB4bWxucz0iaHR0cDomI3gyRjsmI3gyRjt3d3cudzMub3JnJiN4MkY7MjAwMCYjeDJGO3N2ZyIgcHJlc2VydmVBc3BlY3RSYXRpbz0ieE1pbllNaW4gbWVldCI+PHBhdGggZmlsbD0iIzAwMzA4NyIgZD0iTSAxMiA0LjkxNyBMIDQuMiA0LjkxNyBDIDMuNyA0LjkxNyAzLjIgNS4zMTcgMy4xIDUuODE3IEwgMCAyNS44MTcgQyAtMC4xIDI2LjIxNyAwLjIgMjYuNTE3IDAuNiAyNi41MTcgTCA0LjMgMjYuNTE3IEMgNC44IDI2LjUxNyA1LjMgMjYuMTE3IDUuNCAyNS42MTcgTCA2LjIgMjAuMjE3IEMgNi4zIDE5LjcxNyA2LjcgMTkuMzE3IDcuMyAxOS4zMTcgTCA5LjggMTkuMzE3IEMgMTQuOSAxOS4zMTcgMTcuOSAxNi44MTcgMTguNyAxMS45MTcgQyAxOSA5LjgxNyAxOC43IDguMTE3IDE3LjcgNi45MTcgQyAxNi42IDUuNjE3IDE0LjYgNC45MTcgMTIgNC45MTcgWiBNIDEyLjkgMTIuMjE3IEMgMTIuNSAxNS4wMTcgMTAuMyAxNS4wMTcgOC4zIDE1LjAxNyBMIDcuMSAxNS4wMTcgTCA3LjkgOS44MTcgQyA3LjkgOS41MTcgOC4yIDkuMzE3IDguNSA5LjMxNyBMIDkgOS4zMTcgQyAxMC40IDkuMzE3IDExLjcgOS4zMTcgMTIuNCAxMC4xMTcgQyAxMi45IDEwLjUxNyAxMy4xIDExLjIxNyAxMi45IDEyLjIxNyBaIj48L3BhdGg+PHBhdGggZmlsbD0iIzAwMzA4NyIgZD0iTSAzNS4yIDEyLjExNyBMIDMxLjUgMTIuMTE3IEMgMzEuMiAxMi4xMTcgMzAuOSAxMi4zMTcgMzAuOSAxMi42MTcgTCAzMC43IDEzLjYxNyBMIDMwLjQgMTMuMjE3IEMgMjkuNiAxMi4wMTcgMjcuOCAxMS42MTcgMjYgMTEuNjE3IEMgMjEuOSAxMS42MTcgMTguNCAxNC43MTcgMTcuNyAxOS4xMTcgQyAxNy4zIDIxLjMxNyAxNy44IDIzLjQxNyAxOS4xIDI0LjgxNyBDIDIwLjIgMjYuMTE3IDIxLjkgMjYuNzE3IDIzLjggMjYuNzE3IEMgMjcuMSAyNi43MTcgMjkgMjQuNjE3IDI5IDI0LjYxNyBMIDI4LjggMjUuNjE3IEMgMjguNyAyNi4wMTcgMjkgMjYuNDE3IDI5LjQgMjYuNDE3IEwgMzIuOCAyNi40MTcgQyAzMy4zIDI2LjQxNyAzMy44IDI2LjAxNyAzMy45IDI1LjUxNyBMIDM1LjkgMTIuNzE3IEMgMzYgMTIuNTE3IDM1LjYgMTIuMTE3IDM1LjIgMTIuMTE3IFogTSAzMC4xIDE5LjMxNyBDIDI5LjcgMjEuNDE3IDI4LjEgMjIuOTE3IDI1LjkgMjIuOTE3IEMgMjQuOCAyMi45MTcgMjQgMjIuNjE3IDIzLjQgMjEuOTE3IEMgMjIuOCAyMS4yMTcgMjIuNiAyMC4zMTcgMjIuOCAxOS4zMTcgQyAyMy4xIDE3LjIxNyAyNC45IDE1LjcxNyAyNyAxNS43MTcgQyAyOC4xIDE1LjcxNyAyOC45IDE2LjExNyAyOS41IDE2LjcxNyBDIDMwIDE3LjQxNyAzMC4yIDE4LjMxNyAzMC4xIDE5LjMxNyBaIj48L3BhdGg+PHBhdGggZmlsbD0iIzAwMzA4NyIgZD0iTSA1NS4xIDEyLjExNyBMIDUxLjQgMTIuMTE3IEMgNTEgMTIuMTE3IDUwLjcgMTIuMzE3IDUwLjUgMTIuNjE3IEwgNDUuMyAyMC4yMTcgTCA0My4xIDEyLjkxNyBDIDQzIDEyLjQxNyA0Mi41IDEyLjExNyA0Mi4xIDEyLjExNyBMIDM4LjQgMTIuMTE3IEMgMzggMTIuMTE3IDM3LjYgMTIuNTE3IDM3LjggMTMuMDE3IEwgNDEuOSAyNS4xMTcgTCAzOCAzMC41MTcgQyAzNy43IDMwLjkxNyAzOCAzMS41MTcgMzguNSAzMS41MTcgTCA0Mi4yIDMxLjUxNyBDIDQyLjYgMzEuNTE3IDQyLjkgMzEuMzE3IDQzLjEgMzEuMDE3IEwgNTUuNiAxMy4wMTcgQyA1NS45IDEyLjcxNyA1NS42IDEyLjExNyA1NS4xIDEyLjExNyBaIj48L3BhdGg+PHBhdGggZmlsbD0iIzAwOWNkZSIgZD0iTSA2Ny41IDQuOTE3IEwgNTkuNyA0LjkxNyBDIDU5LjIgNC45MTcgNTguNyA1LjMxNyA1OC42IDUuODE3IEwgNTUuNSAyNS43MTcgQyA1NS40IDI2LjExNyA1NS43IDI2LjQxNyA1Ni4xIDI2LjQxNyBMIDYwLjEgMjYuNDE3IEMgNjAuNSAyNi40MTcgNjAuOCAyNi4xMTcgNjAuOCAyNS44MTcgTCA2MS43IDIwLjExNyBDIDYxLjggMTkuNjE3IDYyLjIgMTkuMjE3IDYyLjggMTkuMjE3IEwgNjUuMyAxOS4yMTcgQyA3MC40IDE5LjIxNyA3My40IDE2LjcxNyA3NC4yIDExLjgxNyBDIDc0LjUgOS43MTcgNzQuMiA4LjAxNyA3My4yIDYuODE3IEMgNzIgNS42MTcgNzAuMSA0LjkxNyA2Ny41IDQuOTE3IFogTSA2OC40IDEyLjIxNyBDIDY4IDE1LjAxNyA2NS44IDE1LjAxNyA2My44IDE1LjAxNyBMIDYyLjYgMTUuMDE3IEwgNjMuNCA5LjgxNyBDIDYzLjQgOS41MTcgNjMuNyA5LjMxNyA2NCA5LjMxNyBMIDY0LjUgOS4zMTcgQyA2NS45IDkuMzE3IDY3LjIgOS4zMTcgNjcuOSAxMC4xMTcgQyA2OC40IDEwLjUxNyA2OC41IDExLjIxNyA2OC40IDEyLjIxNyBaIj48L3BhdGg+PHBhdGggZmlsbD0iIzAwOWNkZSIgZD0iTSA5MC43IDEyLjExNyBMIDg3IDEyLjExNyBDIDg2LjcgMTIuMTE3IDg2LjQgMTIuMzE3IDg2LjQgMTIuNjE3IEwgODYuMiAxMy42MTcgTCA4NS45IDEzLjIxNyBDIDg1LjEgMTIuMDE3IDgzLjMgMTEuNjE3IDgxLjUgMTEuNjE3IEMgNzcuNCAxMS42MTcgNzMuOSAxNC43MTcgNzMuMiAxOS4xMTcgQyA3Mi44IDIxLjMxNyA3My4zIDIzLjQxNyA3NC42IDI0LjgxNyBDIDc1LjcgMjYuMTE3IDc3LjQgMjYuNzE3IDc5LjMgMjYuNzE3IEMgODIuNiAyNi43MTcgODQuNSAyNC42MTcgODQuNSAyNC42MTcgTCA4NC4zIDI1LjYxNyBDIDg0LjIgMjYuMDE3IDg0LjUgMjYuNDE3IDg0LjkgMjYuNDE3IEwgODguMyAyNi40MTcgQyA4OC44IDI2LjQxNyA4OS4zIDI2LjAxNyA4OS40IDI1LjUxNyBMIDkxLjQgMTIuNzE3IEMgOTEuNCAxMi41MTcgOTEuMSAxMi4xMTcgOTAuNyAxMi4xMTcgWiBNIDg1LjUgMTkuMzE3IEMgODUuMSAyMS40MTcgODMuNSAyMi45MTcgODEuMyAyMi45MTcgQyA4MC4yIDIyLjkxNyA3OS40IDIyLjYxNyA3OC44IDIxLjkxNyBDIDc4LjIgMjEuMjE3IDc4IDIwLjMxNyA3OC4yIDE5LjMxNyBDIDc4LjUgMTcuMjE3IDgwLjMgMTUuNzE3IDgyLjQgMTUuNzE3IEMgODMuNSAxNS43MTcgODQuMyAxNi4xMTcgODQuOSAxNi43MTcgQyA4NS41IDE3LjQxNyA4NS43IDE4LjMxNyA4NS41IDE5LjMxNyBaIj48L3BhdGg+PHBhdGggZmlsbD0iIzAwOWNkZSIgZD0iTSA5NS4xIDUuNDE3IEwgOTEuOSAyNS43MTcgQyA5MS44IDI2LjExNyA5Mi4xIDI2LjQxNyA5Mi41IDI2LjQxNyBMIDk1LjcgMjYuNDE3IEMgOTYuMiAyNi40MTcgOTYuNyAyNi4wMTcgOTYuOCAyNS41MTcgTCAxMDAgNS42MTcgQyAxMDAuMSA1LjIxNyA5OS44IDQuOTE3IDk5LjQgNC45MTcgTCA5NS44IDQuOTE3IEMgOTUuNCA0LjkxNyA5NS4yIDUuMTE3IDk1LjEgNS40MTcgWiI+PC9wYXRoPjwvc3ZnPg" alt="" aria-label="paypal">
                      </button>
                    </form> --}}

                </div>
              </div>

            </div>


          </div>

        </div>
      </div>

    </div>
  </div>
@endsection

@section('pagespecificscripts')

<script type="text/javascript">

    $( document ).ready(function(event) {

      get_update_show_cart();

      $(document).on('change','#qty-input', function(e){
        
        let qty = $(this).val();
        let price = $('#price_per_unit').val();
        let factor = $('#factor').val();
        let tax = $('#tax').val();

        $('#subTotal').text(roundToTwo(qty*price));
        $('#vatSubTotal').text(roundToTwo(qty*factor));

        let adjustedPrice = (qty*price) + (qty*factor);
        let taxAmount = ( tax * adjustedPrice ) / 100;

        let adjusted_unit_price = roundToTwo(price) + roundToTwo(factor);
        let unit_price_tax = ( tax * adjusted_unit_price ) / 100;

        let final_unit_price = roundToTwo(adjusted_unit_price + unit_price_tax);

        console.log(adjustedPrice);

        console.log(adjusted_unit_price);

        $('#taxValue').text(roundToTwo(taxAmount));
        $('#total').text(roundToTwo(adjustedPrice + taxAmount));

        $('.total_for_checkout').val(roundToTwo(adjustedPrice + taxAmount));
        $('.credits_for_checkout').val(qty);
        $('.unit_price_for_checkout').val(final_unit_price);
});

$(document).on('click','#showModalCheckout', function(e){
    get_update_show_cart();
});

$(document).on('click','#addToCart', function(e){
    
   $.ajax({
        url: "/add_to_cart",
        type: "POST",
        headers: {'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')},
        data: {
            cart: 1
        },
        success: function(response) {
            console.log(response);
            Swal.fire({
            icon: 'success',
            title: '1 Tuning credit (reseller)',
            text: '{{__('Successfully added to Cart')}}',
            showDenyButton: true,
            denyButtonText: '{{__('Continue Shopping')}}',
            confirmButtonText: '{{__('Go to Cart')}}',
            }).then((result) => {
            /* Read more about isConfirmed, isDenied below */
            if (result.isConfirmed) {
                get_update_show_cart();
            } else if (result.isDenied) {
                location.reload();
            }
        });
        }
    }); 
});

function roundToTwo(value) {
    return(Math.round(value * 100) / 100);
}

function get_update_show_cart(){

    $.ajax({
        url: "/cart_quantity",
        type: "POST",
        headers: {'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')},
        data: {},
        success: function(qty) {
            $('#qty-input').val(qty);
            let price = $('#price_per_unit').val();
            let factor = $('#factor').val();
            let tax = $('#tax').val();
        
        $('#subTotal').text(roundToTwo(qty*price));
        $('#vatSubTotal').text(roundToTwo(qty*factor));

        let adjustedPrice = (qty*price) + (qty*factor);
        let taxAmount = ( tax * adjustedPrice ) / 100;

        let adjusted_unit_price = roundToTwo(price) + roundToTwo(factor);
        let unit_price_tax = ( roundToTwo(tax) * roundToTwo(adjusted_unit_price) ) / 100;

        let final_unit_price = roundToTwo(adjusted_unit_price + unit_price_tax);

        console.log(adjustedPrice);

        console.log(adjusted_unit_price);

        $('#taxValue').text(roundToTwo(taxAmount));
        $('#total').text(roundToTwo(adjustedPrice + taxAmount));

        $('.total_for_checkout').val(roundToTwo(adjustedPrice + taxAmount));
        $('.credits_for_checkout').val(qty);
        $('.unit_price_for_checkout').val(final_unit_price);

        $('#modalcheckout').css("display", "block");

        }
    });
}

$(document).on('click','#remove-item', function(e){
Swal.fire({
    title: 'Are you sure?',
    text: "You won't be able to revert this!",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
    if (result.isConfirmed) {

        $.ajax({
            url: "/clear_cart",
            type: "GET",
            headers: {'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')},
            success: function(response) {
                Swal.fire({
                    title: "Deleted!",
                    text: "Your row has been deleted.",
                    type: "success",
                    timer: 3000
                });

                location.reload();
            }
        });            
    }
    })
});

$(document).on('click','#continueShopping', function(e){
    if($('.modal-overlay').css('display') == 'block'){
        console.log('closed');
        $('.modal-overlay').css("display", "none");
    }
});

$(document).on('click','#cart-close', function(e){
    $(function () {
        $('#modalcheckout').css("display", "none");
    });   
});

$(document).on('click','.modal-close-payment', function(e){
    $(function () {
        $('#payment-modal').css("display", "none");
    });   
});

$(document).on('click','#pay', function(e){

    $(function () {
        $('#modalcheckout').css("display", "none");
    }); 

    $('#payment-modal').css("display", "block");

    console.log('pay button clicked');
});

    });

</script>

@endsection