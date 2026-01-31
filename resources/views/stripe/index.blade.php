<form action="{{ route('checkout') }}" method="POST">
    @csrf
    <input type="number" name="amount" required placeholder="Amount">
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <button type="submit" class="btn btn-primary">Pay with Stripe</button>
</form>
