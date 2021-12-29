<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Device\BulkDestroyDevice;
use App\Http\Requests\Admin\Device\DestroyDevice;
use App\Http\Requests\Admin\Device\IndexDevice;
use App\Http\Requests\Admin\Device\StoreDevice;
use App\Http\Requests\Admin\Device\UpdateDevice;
use App\Models\Device;
use Brackets\AdminListing\Facades\AdminListing;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DevicesController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @param IndexDevice $request
     * @return array|Factory|View
     */
    public function index(IndexDevice $request)
    {
        // create and AdminListing instance for a specific model and
        $data = AdminListing::create(Device::class)->processRequestAndGet(
            // pass the request with params
            $request,

            // set columns to query
            ['id', 'ip', 'uuid', 'os_name', 'os_version', 'browser_name', 'browser_version'],

            // set columns to searchIn
            ['id', 'ip', 'uuid', 'os_name', 'os_version', 'browser_name', 'browser_version']
        );

        if ($request->ajax()) {
            if ($request->has('bulk')) {
                return [
                    'bulkItems' => $data->pluck('id')
                ];
            }
            return ['data' => $data];
        }

        return view('admin.device.index', ['data' => $data]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @throws AuthorizationException
     * @return Factory|View
     */
    public function create()
    {
        $this->authorize('admin.device.create');

        return view('admin.device.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreDevice $request
     * @return array|RedirectResponse|Redirector
     */
    public function store(StoreDevice $request)
    {
        // Sanitize input
        $sanitized = $request->getSanitized();

        // Store the Device
        $device = Device::create($sanitized);

        if ($request->ajax()) {
            return ['redirect' => url('admin/devices'), 'message' => trans('brackets/admin-ui::admin.operation.succeeded')];
        }

        return redirect('admin/devices');
    }

    /**
     * Display the specified resource.
     *
     * @param Device $device
     * @throws AuthorizationException
     * @return void
     */
    public function show(Device $device)
    {
        $this->authorize('admin.device.show', $device);

        // TODO your code goes here
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Device $device
     * @throws AuthorizationException
     * @return Factory|View
     */
    public function edit(Device $device)
    {
        $this->authorize('admin.device.edit', $device);


        return view('admin.device.edit', [
            'device' => $device,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateDevice $request
     * @param Device $device
     * @return array|RedirectResponse|Redirector
     */
    public function update(UpdateDevice $request, Device $device)
    {
        // Sanitize input
        $sanitized = $request->getSanitized();

        // Update changed values Device
        $device->update($sanitized);

        if ($request->ajax()) {
            return [
                'redirect' => url('admin/devices'),
                'message' => trans('brackets/admin-ui::admin.operation.succeeded'),
            ];
        }

        return redirect('admin/devices');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param DestroyDevice $request
     * @param Device $device
     * @throws Exception
     * @return ResponseFactory|RedirectResponse|Response
     */
    public function destroy(DestroyDevice $request, Device $device)
    {
        $device->delete();

        if ($request->ajax()) {
            return response(['message' => trans('brackets/admin-ui::admin.operation.succeeded')]);
        }

        return redirect()->back();
    }

    /**
     * Remove the specified resources from storage.
     *
     * @param BulkDestroyDevice $request
     * @throws Exception
     * @return Response|bool
     */
    public function bulkDestroy(BulkDestroyDevice $request) : Response
    {
        DB::transaction(static function () use ($request) {
            collect($request->data['ids'])
                ->chunk(1000)
                ->each(static function ($bulkChunk) {
                    Device::whereIn('id', $bulkChunk)->delete();

                    // TODO your code goes here
                });
        });

        return response(['message' => trans('brackets/admin-ui::admin.operation.succeeded')]);
    }
}
