<?php

namespace App\Http\Controllers\Api;

use Throwable;
use App\Models\Course;
use App\Traits\ApiResponse;
use App\Services\CouponService;
use App\Http\Controllers\Controller;
use App\Http\Resources\CouponResource;
use App\Http\Requests\Coupon\CreateCouponRequest;
use App\Http\Requests\Coupon\ApplyCouponRequest;

class CouponController extends Controller
{
    use ApiResponse;

    public function __construct(private CouponService $service) {}

    // Instructor creates coupon
    public function store(CreateCouponRequest $request)
    {
        try {
            $coupon = $this->service->create($request->validated());

            return $this->success(
                new CouponResource($coupon),
                'Coupon created successfully',
                201
            );

        } catch (Throwable $e) {
            return $this->error($e->getMessage(), null, 500);
        }
    }

    // List course coupons
    public function index(Course $course)
    {
        try {
            $coupons = $this->service->list($course);

            return $this->success(
                CouponResource::collection($coupons),
                'Coupons retrieved successfully'
            );

        } catch (Throwable $e) {
            return $this->error($e->getMessage(), null, 500);
        }
    }

    // Student applies coupon
    public function apply(ApplyCouponRequest $request, Course $course)
    {
        try {
            $data = $this->service->apply($course, $request->code);

            return $this->success([
                'original_price' => $data['course_price'],
                'final_price' => $data['discounted_price'],
                'discount' => $data['discount_amount'],
                'coupon' => new CouponResource($data['coupon']),
            ], 'Coupon applied successfully');

        } catch (Throwable $e) {
            return $this->error($e->getMessage(), null, 422);
        }
    }
    public function preview(ApplyCouponRequest $request, Course $course)
{
    try {
        $data = $this->service->previewPrice($course, $request->code);

        return $this->success([
            'original_price' => $data['original_price'],
            'discount' => $data['discount'],
            'final_price' => $data['final_price'],
            'coupon' => $data['coupon']
                ? new CouponResource($data['coupon'])
                : null,
        ], 'Price calculated successfully');

    } catch (\Throwable $e) {
        return $this->error($e->getMessage(), null, 422);
    }
}

}
