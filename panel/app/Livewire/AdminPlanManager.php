<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Plan;
use Illuminate\Support\Str;

class AdminPlanManager extends Component
{
    public $plans;
    
    // Form fields
    public $plan_id = null;
    public $name = '';
    public $price = 0;
    public $max_devices = 1;
    public $max_messages = 1000;
    public $max_api_keys = 1;
    public $max_webhooks = 1;
    public $is_active = true;

    public $isModalOpen = false;

    public function mount()
    {
        $this->loadPlans();
    }

    public function loadPlans()
    {
        $this->plans = Plan::all();
    }

    public function create()
    {
        $this->resetInputFields();
        $this->openModal();
    }

    public function openModal()
    {
        $this->isModalOpen = true;
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
        $this->resetInputFields();
    }

    public function resetInputFields()
    {
        $this->plan_id = null;
        $this->name = '';
        $this->price = 0;
        $this->max_devices = 1;
        $this->max_messages = 1000;
        $this->max_api_keys = 1;
        $this->max_webhooks = 1;
        $this->is_active = true;
    }

    public function store()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'max_devices' => 'required|integer|min:0',
            'max_messages' => 'required|integer|min:0',
            'max_api_keys' => 'required|integer|min:0',
            'max_webhooks' => 'required|integer|min:0',
        ]);

        Plan::updateOrCreate(['id' => $this->plan_id], [
            'name' => $this->name,
            'slug' => Str::slug($this->name),
            'price' => $this->price,
            'max_devices' => $this->max_devices,
            'max_messages' => $this->max_messages,
            'max_api_keys' => $this->max_api_keys,
            'max_webhooks' => $this->max_webhooks,
            'is_active' => $this->is_active,
        ]);

        session()->flash('message', $this->plan_id ? 'Plan Updated Successfully.' : 'Plan Created Successfully.');
        $this->closeModal();
        $this->loadPlans();
    }

    public function edit($id)
    {
        $plan = Plan::findOrFail($id);
        $this->plan_id = $id;
        $this->name = $plan->name;
        $this->price = $plan->price;
        $this->max_devices = $plan->max_devices;
        $this->max_messages = $plan->max_messages;
        $this->max_api_keys = $plan->max_api_keys;
        $this->max_webhooks = $plan->max_webhooks;
        $this->is_active = $plan->is_active;
        
        $this->openModal();
    }

    public function delete($id)
    {
        Plan::find($id)->delete();
        session()->flash('message', 'Plan Deleted Successfully.');
        $this->loadPlans();
    }

    public function render()
    {
        return view('livewire.admin-plan-manager');
    }
}
