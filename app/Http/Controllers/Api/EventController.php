<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\EventResource;
use App\Http\Traits\CanLoadRelationships;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use App\Models\Event;

class EventController extends Controller
{
    use CanLoadRelationships, AuthorizesRequests;

    private array $relations = ['user', 'attendees', 'attendees.user'];

    public function index()
    {
        $this->authorize('viewAny', Event::class);
        $query = $this->loadRelationships(Event::query());

        return EventResource::collection(
            $query->latest()->paginate());
    }

    public function store(Request $request)
    {
        $this->authorize('create', Event::class);

        $event = Event::create([
            ...$request->validate([
                "name"=> "required|string|max:255",
                "description" => "nullable|string",
                "start_time" => "required|date",
                "end_time" => "required|date|after:start_time"
            ]), 'user_id' => 1
        ]);

        return new EventResource($this->loadRelationships($event));
    }

    /**
     * Display the specified resource.
     */
    public function show(Event $event)
    {
        $this->authorize('view', Event::class);
        $event->load('user', 'attendees');
        // return new EventResource($this->loadRelationships($event));
        return $event;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Event $event)
    {
        $this->authorize('update', Event::class);
        $event->update($request->validate([
                "name"=> "sometimes|string|max:255",
                "description" => "nullable|string",
                "start_time" => "sometimes|date",
                "end_time" => "sometimes|date|after:start_time"
            ])
        );

        return new EventResource($event);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event)
    {
        $this->authorize('delete', Event::class);
        $event->delete();
        return response(status:204);
    }
}
