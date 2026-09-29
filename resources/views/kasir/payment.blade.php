@extends('layouts.app')

@section('title', 'Pembayaran — ' . $order->order_number)
@section('header', 'Proses Pembayaran')

@section('content')
    <div class="max-w-lg">
        <div class="bg-white rounded-xl border p-6 mb-4">
            <div class="flex justify-between items-center mb-4 pb-4 border-b">
                <div>
                    <h3 class="font-bold text-lg">{{ $order->table->name }}</h3>
                    <p class="text-gray-500 text-sm">{{ $order->order_number }}</p>
                </div>
                <span class="text-2xl font-bold text-amber-600">Rp {{ number_format($order->total) }}</span>
            </div>

            <!-- Order summary -->
            <div class="space-y-2 mb-4">
                @foreach($order->items as $item)
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">{{ $item->quantity }}x {{ $item->product_name }}{{ $item->size ? ' ('.$item->size.')' : '' }}</span>
                        <span class="font-medium">Rp {{ number_format($item->subtotal) }}</span>
                    </div>
                @endforeach
                <div class="flex justify-between font-bold pt-2 border-t">
                    <span>TOTAL</span>
                    <span class="text-amber-600">Rp {{ number_format($order->total) }}</span>
                </div>
            </div>
        </div>

        <form method="POST" action="{{ route('kasir.payment.process', $order) }}" class="bg-white rounded-xl border p-6 space-y-4" id="payment-form">
            @csrf

            <div>
                <label class="block font-medium text-gray-700 mb-2">Metode Pembayaran</label>
                <div class="grid grid-cols-3 gap-3">
                    @foreach(['tunai' => ['label' => 'Tunai', 'icon' => '💵'], 'kartu' => ['label' => 'Kartu', 'icon' => '💳'], 'ewallet' => ['label' => 'E-Wallet', 'icon' => '📱']] as $value => $info)
                        <label class="cursor-pointer">
                            <input type="radio" name="payment_method" value="{{ $value }}" class="sr-only payment-method-radio" {{ old('payment_method') === $value ? 'checked' : '' }}>
                            <div class="border-2 rounded-xl p-3 text-center transition payment-method-card hover:border-amber-400">
                                <p class="text-2xl">{{ $info['icon'] }}</p>
                                <p class="text-sm font-medium mt-1">{{ $info['label'] }}</p>
                            </div>
                        </label>
                    @endforeach
                </div>
                @error('payment_method')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Cash fields -->
            <div id="tunai-fields" class="hidden space-y-3">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Uang Diterima</label>
                    <input type="number" name="cash_received" id="cash_received" min="{{ $order->total }}"
                        class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-amber-500"
                        placeholder="{{ $order->total }}" value="{{ old('cash_received') }}">
                    @error('cash_received')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="bg-amber-50 border border-amber-200 rounded-lg p-3 flex justify-between">
                    <span class="font-medium text-amber-700">Kembalian</span>
                    <span class="font-bold text-amber-700" id="change-display">Rp 0</span>
                </div>
            </div>

            <!-- E-wallet fields -->
            <div id="ewallet-fields" class="hidden">
                <label class="block text-sm font-medium text-gray-700 mb-1">Pilih E-Wallet</label>
                <select name="ewallet_type" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-amber-500">
                    <option value="">Pilih...</option>
                    <option value="GoPay">GoPay</option>
                    <option value="OVO">OVO</option>
                    <option value="DANA">DANA</option>
                    <option value="ShopeePay">ShopeePay</option>
                    <option value="QRIS">QRIS</option>
                </select>
                @error('ewallet_type')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3 rounded-xl text-lg transition">
                ✅ Proses Pembayaran — Rp {{ number_format($order->total) }}
            </button>
        </form>
    </div>

    @push('scripts')
    <script>
        const totalAmount = {{ $order->total }};
        const radios = document.querySelectorAll('.payment-method-radio');
        const cards = document.querySelectorAll('.payment-method-card');

        radios.forEach((radio, i) => {
            radio.addEventListener('change', () => {
                cards.forEach(c => c.classList.remove('border-amber-500', 'bg-amber-50'));
                cards[i].classList.add('border-amber-500', 'bg-amber-50');

                document.getElementById('tunai-fields').classList.toggle('hidden', radio.value !== 'tunai');
                document.getElementById('ewallet-fields').classList.toggle('hidden', radio.value !== 'ewallet');
            });

            if (radio.checked) {
                radio.dispatchEvent(new Event('change'));
            }
        });

        document.getElementById('cash_received')?.addEventListener('input', function() {
            const change = parseInt(this.value || 0) - totalAmount;
            document.getElementById('change-display').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(Math.max(0, change));
        });
    </script>
    @endpush
@endsection
