<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use App\Models\Plan;
use App\Models\Subscription;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class AdminUserManager extends Component
{
    public $users;
    public $plans;
    
    // Form fields
    public $user_id = null;
    public $name = '';
    public $email = '';
    public $password = '';
    public $is_admin = false;
    public $plan_id = null;

    public $isModalOpen = false;

    public function mount()
    {
        $this->loadData();
    }

    public function loadData()
    {
        $this->users = User::with('subscription.plan')->get();
        $this->plans = Plan::where('is_active', true)->get();
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
        $this->user_id = null;
        $this->name = '';
        $this->email = '';
        $this->password = '';
        $this->is_admin = false;
        $this->plan_id = null;
    }

    public function store()
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $this->user_id,
        ];
        
        if (!$this->user_id || !empty($this->password)) {
            $rules['password'] = 'required|min:8';
        }

        $this->validate($rules);

        $userData = [
            'name' => $this->name,
            'email' => $this->email,
            'is_admin' => $this->is_admin,
        ];

        if (!empty($this->password)) {
            $userData['password'] = Hash::make($this->password);
        }

        $user = User::updateOrCreate(['id' => $this->user_id], $userData);

        if ($this->plan_id) {
            Subscription::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'plan_id' => $this->plan_id,
                    'status' => 'active',
                    'started_at' => Carbon::now(),
                    'expired_at' => Carbon::now()->addMonth()
                ]
            );
        }

        session()->flash('message', $this->user_id ? 'User Updated Successfully.' : 'User Created Successfully.');
        $this->closeModal();
        $this->loadData();
    }

    public function edit($id)
    {
        $user = User::with('subscription')->findOrFail($id);
        $this->user_id = $id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->is_admin = $user->is_admin;
        $this->plan_id = $user->subscription ? $user->subscription->plan_id : null;
        $this->password = '';
        
        $this->openModal();
    }

    public function delete($id)
    {
        User::find($id)->delete();
        session()->flash('message', 'User Deleted Successfully.');
        $this->loadData();
    }

    public function render()
    {
        return view('livewire.admin-user-manager');
    }
}
