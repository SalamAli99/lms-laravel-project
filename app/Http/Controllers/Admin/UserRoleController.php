<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AssignRoleRequest;
use App\Http\Requests\UpdateRoleRequest;
use App\Http\Resources\UserRoleResource;
use App\Models\User;
use App\Services\UserRoleService;
use App\Traits\ApiResponse;
use Exception;
use Spatie\Permission\Exceptions\RoleDoesNotExist;

class UserRoleController extends Controller
{
    use ApiResponse;

    public function __construct(
        protected UserRoleService $service
    ) {}

    public function assign(AssignRoleRequest $request, User $user)
    {
        try {
            $user = $this->service->assign($user, $request->role);

            return $this->success(
                new UserRoleResource($user),
                'Role assigned successfully'
            );
        } catch (RoleDoesNotExist $e) {
            return $this->error(
                'Role does not exist',
                $e->getMessage(),
                422
            );
        } catch (Exception $e) {
            return $this->error(
                'Failed to assign role',
                $e->getMessage(),
                500
            );
        }
    }

    public function update(UpdateRoleRequest $request, User $user)
    {
        try {
            $user = $this->service->update($user, $request->role);

            return $this->success(
                new UserRoleResource($user),
                'Role updated successfully'
            );
        } catch (RoleDoesNotExist $e) {
            return $this->error(
                'Role does not exist',
                $e->getMessage(),
                422
            );
        } catch (Exception $e) {
            return $this->error(
                'Failed to update role',
                $e->getMessage(),
                500
            );
        }
    }

    public function revoke(AssignRoleRequest $request, User $user)
    {
        try {
            $user = $this->service->revoke($user, $request->role);

            return $this->success(
                new UserRoleResource($user),
                'Role revoked successfully'
            );
        } catch (RoleDoesNotExist $e) {
            return $this->error(
                'Role does not exist',
                $e->getMessage(),
                422
            );
        } catch (Exception $e) {
            return $this->error(
                'Failed to revoke role',
                $e->getMessage(),
                500
            );
        }
    }
}
