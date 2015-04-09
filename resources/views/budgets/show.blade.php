@extends('layouts.default')
@section('content')
{!! Breadcrumbs::renderIfExists(Route::getCurrentRoute()->getName(), $budget, $repetition) !!}
<div class="row">
    <div class="col-lg-9 col-md-9 col-sm-7">
        <div class="panel panel-default">
            <div class="panel-heading">
                Overview


                <!-- ACTIONS MENU -->
                <div class="pull-right">
                    <div class="btn-group">
                        <button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown">
                            Actions
                            <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu pull-right" role="menu">
                            <li><a href="{{route('budgets.edit',$budget->id)}}"><i class="fa fa-pencil fa-fw"></i> Edit</a></li>
                            <li><a href="{{route('budgets.delete',$budget->id)}}"><i class="fa fa-trash fa-fw"></i> Delete</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="panel-body">
                <div id="budgetOverview"></div>
            </div>
        </div>

         <div class="panel panel-default">
            <div class="panel-heading">
                Transactions
            </div>
                @include('list.journals-full',['sorting' => false])
        </div>
    </div>
    <div class="col-lg-3 col-md-3 col-sm-5">
        @if(count($limits) == 1)
        <p class="small text-center"><a href="{{route('budgets.show',$budget->id)}}">Show everything</a></p>
        @endif

        @foreach($limits as $limit)
            @foreach($limit->limitrepetitions as $rep)
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <a href="{{route('budgets.show',[$budget->id,$rep->id])}}">{{$rep->startdate->format('F Y')}}</a>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-6">
                                Amount: {!! Amount::format($rep->amount) !!}
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6">
                                Spent: {!! Amount::format($rep->spentInRepetition()) !!}
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12">
                                <?php
                                $overspent = $rep->spentInRepetition() > $rep->amount;
                                ?>
                                @if($overspent)
                                <?php
                                $spent = floatval($rep->spentInRepetition());
                                $pct = $spent != 0 ? ($rep->amount / $spent)*100 : 0;
                                ?>
                                <div class="progress progress-striped">
                                  <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="{{ceil($pct)}}" aria-valuemin="0" aria-valuemax="100" style="width: {{ceil($pct)}}%;"></div>
                                  <div class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="{{100-ceil($pct)}}" aria-valuemin="0" aria-valuemax="100" style="width: {{100-ceil($pct)}}%;"></div>
                                </div>
                                @else
                                <?php
                                $amount = floatval($rep->amount);
                                $pct = $amount != 0 ? ($rep->spentInRepetition() / $amount)*100 : 0;
                                ?>
                                <div class="progress progress-striped">
                                  <div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="{{ceil($pct)}}" aria-valuemin="0" aria-valuemax="100" style="width: {{ceil($pct)}}%;">
                                  </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @endforeach

        @if(count($limits) == 1)
            <p class="small text-center"><a href="{{route('budgets.show',$budget->id)}}">Show everything</a></p>
        @endif

    </div>
</div>

@stop
@section('scripts')
<script type="text/javascript">
    var budgetID = {{$budget->id}};
    var currencyCode = '{{Amount::getCurrencyCode()}}';
    @if(!is_null($repetition->id))
        var repetitionID = {{$repetition->id}};
        var year = {{$repetition->startdate->format('Y')}};
    @else
        var year = {{Session::get('start',\Carbon\Carbon::now()->startOfMonth())->format('Y')}};
    @endif

</script>

<!-- load the libraries and scripts necessary for Google Charts: -->
<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<script type="text/javascript" src="js/gcharts.options.js"></script>
<script type="text/javascript" src="js/gcharts.js"></script>
<script type="text/javascript" src="js/budgets.js"></script>

@stop