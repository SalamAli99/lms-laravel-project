<?php
namespace App\Services;

use App\Models\Coupon;
use App\Models\Course;
use Illuminate\Support\Facades\Auth;

class CouponService
{
    public function validate(string $code, Course $course): Coupon
{
    $coupon = Coupon::where('code', $code)
        ->where('course_id', $course->id)
        ->first();

    if (!$coupon)
        throw new \Exception('Invalid coupon code');

    if (!$coupon->isValid())
        throw new \Exception('Coupon expired or limit reached');

    if ($coupon->usedBy(Auth::user()))
        throw new \Exception('You have already used this coupon');

    return $coupon;
}

    public function create(array $data): Coupon
    {
        return Coupon::create($data);
    }

    public function list(Course $course)
    {
        return $course->coupons()->latest()->paginate();
    }

    public function apply(Course $course, string $code): array
    {
        $coupon = $this->validate($code, $course);

        $original = $course->price;
        $final = $coupon->applyDiscount($original);

        return [
            'course_price' => (float) $original,
            'discounted_price' => (float) $final,
            'discount_amount' => (float) ($original - $final),
            'coupon' => $coupon,
        ];
    }
    public function markUsed(Coupon $coupon): void
{
    $user = Auth::user();

    $coupon->users()->attach($user->id, [
        'used_at' => now(),
    ]);

    $coupon->increment('used_count');
}
public function previewPrice(Course $course, ?string $code = null): array
{
    // Free course
    if (!$course->is_paid) {
        return [
            'original_price' => 0,
            'discount' => 0,
            'final_price' => 0,
            'coupon' => null,
        ];
    }

    $originalPrice = (float) $course->price;
    $finalPrice = $originalPrice;
    $discountAmount = 0;
    $couponData = null;

    if ($code) {
        $coupon = $this->validate($code, $course);

        $finalPrice = $coupon->applyDiscount($originalPrice);
        $discountAmount = $originalPrice - $finalPrice;

        $couponData = $coupon;
    }

    return [
        'original_price' => $originalPrice,
        'discount' => $discountAmount,
        'final_price' => $finalPrice,
        'coupon' => $couponData,
    ];
}
}
