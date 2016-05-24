@if(!emptyOrHasEmptyTemplate($total_expenditure))
    <div class="activity-element-wrapper">
        <div class="activity-element-list">
            <div class="activity-element-label">@lang('activityView.total_expenditure')</div>
            <div class="activity-element-info">
                @foreach($total_expenditure as $expenditure)
                    <li>{!! getCurrencyValueDate($expenditure['value'][0], "planned") !!}</li>
                    <div class="toggle-btn">
                        <a href="#" class="show-more-info">Show more info</a>
                        <a href="#" class="hide-more-info hidden">Hide more info</a>         
                    </div>
                    <div class="more-info">
                        <dl>
                            <dt>@lang('activityView.period')</dt>
                            <dd>{!! checkIfEmpty(getBudgetInformation('period',$expenditure)) !!}</dd>
                        </dl>
                        <dl>
                            <dt>@lang('activityView.expense_line')</dt>
                            @foreach($expenditure['expense_line'] as $expense)
                               <dd>
                                   <li>{!! getCurrencyValueDate($expense['value'][0], 'planned') !!}</li>
                                   <dd>@lang('activityView.reference'): {!! checkIfEmpty($expense['reference']) !!}</dd>
                                   <dd>
                                       @lang('activityView.narrative'): {!! checkIfEmpty(getFirstNarrative($expense)) !!}
                                       @include('Activity.partials.viewInOtherLanguage' ,['otherLanguages' => getOtherLanguages($expense['narrative'])])
                                   </dd>
                               </dd>
                            @endforeach
                        </dl>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    <a href="{{ url('/organization/' . $orgId . '/total-expenditure') }}" class="edit-element">edit</a>
@endif
