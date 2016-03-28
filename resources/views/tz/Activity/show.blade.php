@extends('app')

@section('title', 'Activity Data')

@inject('getCode', 'App\Helpers\GetCodeName')
@section('content')
    <div class="container main-container">
        <div class="row">
            @include('includes.side_bar_menu')
            <div class="col-xs-9 col-md-9 col-lg-9 content-wrapper">
                @include('includes.response')
                @include('includes.breadcrumb')
                <?php
                $activity_workflow = $activityData['activity_workflow'];
                $status_label = ['draft', 'completed', 'verified', 'published'];
                $btn_status_label = ['Completed', 'Verified', 'Published'];
                $btn_text = $activity_workflow > 2 ? "" : $btn_status_label[$activity_workflow];
                ?>
                <div class="element-panel-heading">
                    <div>
                        <span>{{ $activityData['title'] ? $activityData['title'][0]['narrative'] : 'No Title' }}</span>
                        <div class="element-panel-heading-info">
                            <span>{{$activityData['identifier']['iati_identifier_text']}}</span>
                            <span class="last-updated-date">Last Updated on: {{changeTimeZone($activityData['updated_at'], 'M d, Y H:i')}}</span>
                        </div>
                    </div>
                </div>
                <div class="col-xs-12 activity-content-wrapper">
                    <div class="activity-status activity-status-{{ $status_label[$activity_workflow] }}">
                        <ol>
                            @foreach($status_label as $key => $val)
                                @if($key == $activity_workflow)
                                    <li class="active"><span>{{ $val }}</span></li>
                                @else
                                    <li><span>{{ $val }}</span></li>
                                @endif
                            @endforeach
                        </ol>
                        @if($btn_text != "")
                            <form method="POST" id="change_status" class="pull-right"
                                  action="{{ url('/activity/' . $id . '/update-status') }}">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
                                <input type="hidden" name="activity_workflow" value="{{ $activity_workflow + 1 }}">
                                @if($activity_workflow == 2)
                                    <input type="button" value="Mark as {{ $btn_text }}" class="btn_confirm"
                                           data-title="Confirmation" data-message="Are you sure you want to Publish?">
                                @else
                                    <input type="submit" value="Mark as {{ $btn_text }}">
                                @endif
                            </form>
                        @endif
                    </div>

                    <div class="switch-tabs pull-left">
                        <ul>
                            <li><a href="{{route('activity.show', $id)}}" class="active">Activity View</a></li>
                            <li><a href="{{route('activity.transaction.index', $id)}}">Transactions</a></li>
                        </ul>
                    </div>
                    <div class="pull-right">
                        <a href="{{route('activity.edit', $id)}}" class="edit-activity"><span>Edit this activity</span></a>
                    </div>
                    <div class="panel panel-default panel-element-detail panel-activity-detail">
                        <div class="panel-body panel-element-body">
                            <div class="col-sm-12">
                                <div class="col-sm-4">Description:</div>
                                <div class="col-sm-8">{{ $activity['description'][0]['general'] }}</div>
                            </div>
                            <div class="col-sm-12">
                                <div class="col-sm-4">Objectives:</div>
                                <div class="col-sm-8">{{ $activity['description'][0]['objectives'] }}</div>
                            </div>
                            <div class="col-sm-12">
                                <div class="col-sm-4">Target Groups:</div>
                                <div class="col-sm-8">{{ $activity['description'][0]['target_groups'] }}</div>
                            </div>
                            <div class="col-sm-12">
                                <div class="col-sm-4">Funding Organization:</div>
                                <div class="col-sm-8">{{ implode(', ', $activity['participating_organization'][0]['funding_organization']) }}</div>
                            </div>
                            <div class="col-sm-12">
                                <div class="col-sm-4">Implementing Organization:</div>
                                <div class="col-sm-8">{{ implode(', ', $activity['participating_organization'][0]['implementing_organization']) }}</div>
                            </div>
                            {{-- */
                                $sectors = [];
                                foreach($activity['sector_category_code'] as $code) {
                                    $sectors[] = $getCode->getActivityCodeName('SectorCategory', $code);
                                }
                            /* --}}
                            <div class="col-sm-12">
                                <div class="col-sm-4">Sectors:</div>
                                <div class="col-sm-8">{{ implode(', ', $sectors) }}</div>
                            </div>
                            <div class="col-sm-12">
                                <div class="col-sm-4">Start Date:</div>
                                <div class="col-sm-8">{{ formatDate($activity['start_date']) }}</div>
                            </div>
                            <div class="col-sm-12">
                                <div class="col-sm-4">End Date:</div>
                                <div class="col-sm-8">{{ $activity['end_date'] ? formatDate($activity['end_date']) : '' }}</div>
                            </div>
                            <div class="col-sm-12">
                                <div class="col-sm-4">Activity Status:</div>
                                <div class="col-sm-8">{{ $getCode->getActivityCodeName('ActivityStatus', $activity['activity_status']) }}</div>
                            </div>
                            {{-- */
                                $countries = [];
                                foreach($activity['recipient_country'] as $code) {
                                    $countries[] = $getCode->getOrganizationCodeName('Country', $code);
                                }
                            /* --}}
                            <div class="col-sm-12">
                                <div class="col-sm-4">Recipient Country:</div>
                                <div class="col-sm-8">{{ implode(', ', $countries) }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
