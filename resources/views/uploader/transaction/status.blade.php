@foreach (getVal($transactions, ['transactions'], []) as $index => $transaction)
    <div class="panel panel-default">
        <div class="panel-heading">
            <label>
                <input type="checkbox" name="transaction[]" value="{{ $index }}" {{ getVal($transaction, ['validity']) ?! 'disabled' : '' }}/>
                <input type="hidden" name="filename" value="{{ $filename }}">
                {{ getVal($transaction, ['reference']) ? getVal($transaction, ['reference']) : 'No Reference Provided.' }}
            </label>
            <span class="pull-right">{{ getVal($transaction, ['validity']) ? 'Valid' : 'Invalid' }}</span>
        </div>
    </div>
@endforeach
