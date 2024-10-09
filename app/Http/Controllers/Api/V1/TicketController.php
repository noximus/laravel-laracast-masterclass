<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Filters\V1\TicketFilter;
use App\Http\Requests\Api\V1\ReplaceTicketRequest;
use App\Models\Ticket;
use App\Http\Requests\Api\V1\StoreTicketRequest;
use App\Http\Requests\Api\V1\UpdateTicketRequest;
use App\Http\Resources\V1\TicketResource;
use App\Policies\V1\TicketPolicy;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class TicketController extends ApiController
{
    protected $policyClass = TicketPolicy::class;
    // GET ALL
    public function index(TicketFilter $filters)
    {
        return TicketResource::collection(Ticket::filter($filters)->paginate());
    }
    // Create Ticket POST
    public function store(StoreTicketRequest $request)
    {
        try {
            $this->isAble('store', Ticket::class);

            return new TicketResource(Ticket::create($request->mappedAttributes()));
        } catch (AuthorizationException $ex) {
            return $this->error('You are not authroized to update that resource', 401);
        }
    }

    // Single Ticket GET
    public function show($ticket_id)
    {
        try {
            $ticket = Ticket::findOrFail($ticket_id);

            if ($this->include('author')) {
                return new TicketResource($ticket->load('user'));
            }

            return new TicketResource($ticket);
        } catch (ModelNotFoundException $exception) {
            return $this->error('Ticket cannot be found.', 404);
        }
    }
    // PATCH
    public function update(UpdateTicketRequest $request, $ticket_id)
    {
        try {
            $ticket = Ticket::findOrFail($ticket_id);

            $this->isAble('update', $ticket);

            $ticket->update($request->mappedAttributes());

            return new TicketResource($ticket);
        } catch (ModelNotFoundException $exception) {
            return $this->error('Ticket cannot be found.', 404);
        } catch (AuthorizationException $ex) {
            return $this->error('You are not authorized to update that resource', 401);
        }
    }
    // PUT
    public function replace(ReplaceTicketRequest $request, $ticket_id)
    {
        try {
            $ticket = Ticket::findOrFail($ticket_id);

            $this->isAble('replace', $ticket);

            $ticket->update($request->mappedAttributes());

            return new TicketResource($ticket);
        } catch (ModelNotFoundException $exception) {
            return $this->error('Ticket cannot be found.', 404);
        }
    }
    // DELETE
    public function destroy($ticket_id)
    {
        try {
            $ticket = Ticket::findOrFail($ticket_id);

            $this->isAble('delete', $ticket);

            $ticket->delete();

            return $this->ok('Ticket successfully deleted');
        } catch (ModelNotFoundException $exception) {
            return $this->error('Ticket cannot found.', 404);
        }
    }
}
