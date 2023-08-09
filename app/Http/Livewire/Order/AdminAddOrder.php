<?php

namespace App\Http\Livewire\Order;

use App\Models\Bracelet;
use App\Models\Customer;
use Illuminate\Validation\Rule;
use Livewire\Component;

class AdminAddOrder extends Component
{
    /**
     * Bracelets.
     *
     * @var array
     */
    public $bracelets = [];

    /**
     * Current bracelet.
     */
    public $clone = [
        'number' => '',
        'name' => '',
    ];

    /**
     * Customer first name.
     */
    public $firstName = '';

    /**
     * Customer last name.
     */
    public $lastName = '';

    /**
     * Customer email.
     */
    public $email = '';

    /**
     * Customer phone.
     */
    public $phone = '';

    protected $listeners = [
        'orderSaved' => 'clearForm',
    ];

    /**
     * Validation rules
     *
     * @return array
     */
    protected function rules() {
        return [
            'bracelets' => 'required|array|min:1',
            'bracelets.*.number' => [
                'required',
                'numeric',
                'digits_between:1,4',
                Rule::exists('bracelets', 'number')->where(function ($query) {
                    return $query->where('status', 'system');
                }),
            ],
            'bracelets.*.name' => 'nullable|string|max:255',
            'firstName' => 'required|string|max:255',
            'lastName' => 'required|string|max:255',
            'email' => 'required_without:phone|email|max:255',
            'phone' => 'required_without:email|string|max:255',
            'clone.number' => [
                'required_without:bracelets',
                'numeric',
                'digits_between:1,4',
                Rule::exists('bracelets', 'number')->where(function ($query) {
                    return $query->where('status', 'system');
                }),
            ],
            'clone.name' => 'nullable|string|max:255',
        ];
    }

    /**
     * Validation attribute names
     *
     * @return array
     */
    protected function validationAttributes() {
        return [
            'bracelets.*.number' => 'bracelet number',
            'bracelets.*.name' => 'bracelet name',
            'clone.number' => 'bracelet number',
            'clone.name' => 'bracelet name',
            'firstName' => 'first name',
            'lastName' => 'last name',
        ];
    }

    /**
     * Validation messages
     *
     * @return array
     */
    protected function messages() {
        return [
            '*.required' => 'The :attribute field is required.',
            '*.array' => 'The :attribute data was formatted incorrectly.',
            '*.numeric' => 'The :attribute must be a number.',
            '*.digits_between' => 'The :attribute must be between :min and :max digits.',
            '*.exists' => 'The :attribute does not exist or has already been registered.',
            '*.required_without' => 'Please provide either an email or phone number.',
            'clone.number.required_without' => 'Please provide at least one bracelet number.',
        ];
    }

    public function addBracelet() {
        if (empty($this->clone['number'])) {
            return;
        }

        $this->clone['number'] = sprintf('%04d', $this->clone['number']);
        $this->bracelets[] = $this->clone;
        $this->clone = [
            'number' => '',
            'name' => '',
        ];
    }

    public function removeBracelet($idx) {
        if (!isset($idx) || !isset($this->bracelets[$idx])) {
            return;
        }

        // Remove bracelet from array.
        unset($this->bracelets[$idx]);
        $this->bracelets = array_values($this->bracelets);
    }

    public function adminAddOrder() {
        $this->addBracelet();
        $validData = $this->validate();


        // create or update customer
        $customer = Customer::firstOrCreate(
            [
                'email' => $validData['email'],
                'phone_number' => $validData['phone'],
            ],
            [
                'first_name' => $validData['firstName'],
                'last_name' => $validData['lastName'],
            ],
        );

        // create an order
        $order = $customer->orders()->create([
            'order_type' => 'in-person',
        ]);

        // add bracelets to order
        foreach ($validData['bracelets'] as $bracelet) {
            // update the bracelet's name & status
            $bracelet = Bracelet::where('number', $bracelet['number'])->first();
            $bracelet->update([
                'name' => $bracelet['name'] ?? $customer->fullName(),
                'status' => 'registered',
            ]);
            $order->bracelets()->save($bracelet);
        }

        // emit saved event
        $this->emit('orderSaved', $order->id);
    }

    public function clearForm() {
        $this->reset();
    }

    public function render()
    {
        return view('livewire.order.admin-add-order', [
            'hasBracelets' => Bracelet::where('status', 'system')->exists(),
        ]);
    }
}
