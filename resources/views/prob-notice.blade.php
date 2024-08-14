@php
    use App\Models\Prize;

    $current_probability = floatval(Prize::sum('probability'));
    $remaining_probability = 100 - $current_probability;
@endphp

@if ($remaining_probability > 0)
    <div class="alert alert-danger">
        The sum of all prize probabilities must be 100%. Currently, it's {{ $current_probability }}%.
        You have yet to add {{ $remaining_probability }}% to the prize.
    </div>
@endif

@if (session('probability_error'))
    <div class="alert alert-danger">
        The probability field must not be greater than {{ $remaining_probability }}%.
    </div>
@endif
