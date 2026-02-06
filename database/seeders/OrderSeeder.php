<?php

namespace Database\Seeders;

use App\Enums\PaymentMethodEnum;
use App\Enums\StatusEnum;
use App\Models\Address;
use App\Models\Design;
use App\Models\ItemOptionOrderSelected;
use App\Models\ItemOrder;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Review;
use App\Models\User;
use App\Models\Invoice;
use App\Models\City;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::role('user')->get();
        if ($users->isEmpty()) {
            $users = User::all();
        }

        $designs = Design::where('status', StatusEnum::ACTIVE)->get();
        if ($designs->isEmpty()) {
            $designs = Design::all();
        }

        $statuses = [
            StatusEnum::PENDING,
            StatusEnum::CONFIRMED,
            StatusEnum::DELIVERED,
            StatusEnum::CANCELLED,
        ];

        foreach ($users as $index => $user) {
            $address = Address::where('user_id', $user->id)->first();
            if (! $address) {
                $cityId = City::inRandomOrder()->first()?->id ?? 1;
                $address = Address::create([
                    'user_id' => $user->id,
                    'city_id' => $cityId,
                    'street' => 'Sample Street '.$index,
                    'details' => 'Sample details',
                ]);
            }

            foreach ($statuses as $statusIndex => $status) {
                $method = match ($status) {
                    StatusEnum::PENDING => PaymentMethodEnum::STRIPE,
                    StatusEnum::CONFIRMED => PaymentMethodEnum::WALLET,
                    StatusEnum::DELIVERED => PaymentMethodEnum::AFTER_DELIVERY,
                    StatusEnum::CANCELLED => PaymentMethodEnum::STRIPE,
                    default => PaymentMethodEnum::STRIPE,
                };

                $paymentStatus = match ($status) {
                    StatusEnum::PENDING => StatusEnum::PENDING,
                    StatusEnum::CONFIRMED => StatusEnum::CONFIRMED,
                    StatusEnum::DELIVERED => StatusEnum::CONFIRMED,
                    StatusEnum::CANCELLED => StatusEnum::FAILED,
                    default => StatusEnum::PENDING,
                };

                $payment = Payment::create([
                    'user_id' => $user->id,
                    'method' => $method,
                    'status' => $paymentStatus,
                    'amount' => 0,
                    'type' => 'pay',
                ]);
                $payment->num = $payment->created_at->format('Ymd').$payment->id;
                $payment->save();

                $order = Order::create([
                    'user_id' => $user->id,
                    'address_id' => $address->id,
                    'status' => $status,
                    'subtotal' => 0,
                    'discount' => 0,
                    'payment_id' => $payment->id,
                ]);
                $order->num = $order->created_at->format('Ymd').$order->id;
                $order->save();

                $subtotal = 0;
                $selectedDesigns = $designs->shuffle()->take(2);

                foreach ($selectedDesigns as $design) {
                    $measurementId = $design->measurements()->pluck('measurements.id')->first()
                        ?? 1;
                    $quantity = rand(1, 3);
                    $price = $design->price;

                    $itemOrder = ItemOrder::create([
                        'order_id' => $order->id,
                        'design_id' => $design->id,
                        'measurement_id' => $measurementId,
                        'quantity' => $quantity,
                        'price' => $price,
                        'discount' => 0,
                    ]);

                    $subtotal += ($price * $quantity);

                    $options = $design->designOptions()->get()->groupBy('type');
                    foreach ($options as $group) {
                        $option = $group->first();
                        if ($option) {
                            ItemOptionOrderSelected::create([
                                'item_order_id' => $itemOrder->id,
                                'design_option_id' => $option->id,
                            ]);
                        }
                    }
                }

                $order->update([
                    'subtotal' => $subtotal,
                ]);
                $payment->update([
                    'amount' => $subtotal,
                ]);

                if ($status === StatusEnum::DELIVERED) {
                    Review::firstOrCreate([
                        'user_id' => $user->id,
                        'order_id' => $order->id,
                    ], [
                        'rating' => rand(3, 5),
                        'comment' => 'Order delivered and reviewed.',
                    ]);

                    Invoice::firstOrCreate([
                        'order_id' => $order->id,
                    ], [
                        'num' => (string) $order->num,
                        'total' => $subtotal,
                        'pdf_url' => null,
                    ]);
                }
            }
        }
    }
}
