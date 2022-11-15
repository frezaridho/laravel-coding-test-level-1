<?php

namespace App\Http\Controllers\API;

use App\Mail\MailEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\EventRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use App\Http\Resources\EventResource;
use Illuminate\Support\Facades\Redis;
use App\Http\Repositories\Contracts\EventContract;

class EventController extends Controller
{
    /** @var $repository*/
    protected $repository;

    public function __construct(EventContract $repository)
    {
        $this->repository = $repository;
    }

    public function index()
    {
        $data = $this->repository->all();
        
        return EventResource::collection($data);
    }

    public function active()
    {
        $data = $this->repository->active();

        return EventResource::collection($data);
    }

    public function show($id)
    {   
        $cached = Redis::get('slug_' . $id);
        
        if (isset($cached)) {
            $data = json_decode($cached, FALSE);
            
            return new EventResource($data);
        }

        $data = $this->repository->find($id);
        Redis::set('slug_'. $id, $data);

        return new EventResource($data);
    }

    public function store(EventRequest $request)
    {
        return DB::transaction(function () use ($request) {
            $data = $this->repository->store($request->all());

            $details = [
                'event' => $data->name
            ];

            Mail::to('abrahamlincoln@mail.com')->send(new MailEvent($details));

            return new EventResource($data); 
        });
    }

    public function update(EventRequest $request, $id)
    {
        return DB::transaction(function () use ($request, $id) {
            $update = $this->repository->update($request->all(), $id);

            if ($update) {
                Redis::del('slug_'. $id);

                $data = $this->repository->find($id);
                Redis::set('slug_'. $id, $data);
            }
        
            return response()->json([
                'status' => 'success',
                'message' => 'Event updated successfully',
            ]);
        });
    }

    public function destroy($id)
    {
        return DB::transaction(function () use ($id) {
            $this->repository->delete($id);
            Redis::del('slug_'. $id);
        
            return response()->json([
                'status' => 'success',
                'message' => 'Event deleted successfully',
            ]);
        });
    }
}
