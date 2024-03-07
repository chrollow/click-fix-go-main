<?php

namespace App\Http\Controllers;
use App\Models\Queue;
use App\Models\Device;
use App\Models\Ticket;
use App\Models\Customer;
use Illuminate\Http\Request;
use App\Models\Deviceservice;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;

class QueueController extends Controller
{
    public function create($id)
    {
        $deviceServices = Deviceservice::where('device_id', $id)->get();
        return View::make('queue.create', compact('deviceServices'));
    }

    public function store(Request $request)
    {
        //dd($request);
        $existingCustomer = Customer::where('user_id', $request->user_id)->first();

        if ($existingCustomer) {
            // If a customer record already exists, you can update the existing record if needed
            // For example, you might want to update the customer's details based on the new order
            $existingCustomer->update([
                'phone_number' => $request->phone_number,
                // Add more fields to update as needed
            ]);

            $queue = new Queue();
            $queue->customer_id = $existingCustomer->customer_id;
            $queue->customer_name = $request->customer_name;
            $queue->date_placed = $request->date_placed;
            $queue->scheduled_date = $request->scheduled_date;
            $queue->phone_number = $request->phone_number;
            $queue->status = 'for diagnosis';
            $queue->save();

            foreach($request->service_id as $service){
                $ticket = new Ticket();
                $ticket->queue_id = $queue->id;
                $ticket->customer_id = $existingCustomer->customer_id;
                $ticket->customer_name = $request->customer_name;
                $ticket->device_id = $request->device_id;
                $ticket->device_type = $request->device_type[0];
                $ticket->service_id = $service;
                $service_type = Deviceservice::where('service_id', $service)->pluck('service_type')->first();

                $ticket->service_type = $service_type;
                $ticket->technician_id = $request->technician_id;
                $ticket->technician_name = $request->technician_name;
                $ticket->status = 'for diagnosis';
                $ticket->save();
            }
        }else 
        {
            $customer = new Customer();
            $customer->user_id = $request->user_id;
            $customer->customer_name = $request->customer_name;
            $customer->phone_number = $request->phone_number;
            $customer->email = $request->email;
            $customer->save();

            $queue = new Queue();
            $queue->customer_id = $customer->customer_id;
            $queue->customer_name = $request->customer_name;
            $queue->date_placed = $request->date_placed;
            $queue->scheduled_date = $request->scheduled_date;
            $queue->phone_number = $request->phone_number;
            $queue->status = 'for diagnosis';
            $queue->save();

            foreach($request->service_id as $service){
                $ticket = new Ticket();
                $ticket->queue_id = $queue->id;
                $ticket->customer_id = $customer->customer_id;
                $ticket->customer_name = $request->customer_name;
                $ticket->device_id = $request->device_id;
                $ticket->device_type = $request->device_type[0];
                $ticket->service_id = $service;
                $service_type = Deviceservice::where('service_id', $service)->pluck('service_type')->first();

                $ticket->service_type = $service_type;
                $ticket->technician_id = $request->technician_id;
                $ticket->technician_name = $request->technician_name;
                $ticket->status = 'for diagnosis';
                $ticket->save();
            }
        }
    }

    public function index()
    {
        $tickets = DB::table('tickets')->get();
        $queues = DB::table('queues')->pluck('queue_id')->toArray();
        return View::make('queues.index', compact('tickets', 'queues'));
    }
}