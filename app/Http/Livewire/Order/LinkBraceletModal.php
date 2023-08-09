<?php

namespace App\Http\Livewire\Order;

use App\Models\Bracelet;
use App\Models\Order;
use Illuminate\Validation\Rule;
use Livewire\Component;

class LinkBraceletModal extends Component
{
    /**
     * Whether the modal is visilbe or not.
     *
     * @var bool
     */
    public $showModal = false;

    /**
     * The Order instance.
     *
     * @var Order
     */
    public $order;

    /**
     * The Bracelet number.
     *
     * @var string
     */
    public $number;

    /**
     * The Bracelet name.
     *
     * @var string
     */
    public $name;

    protected $listeners = [
        'showLinkBraceletModal',
        'cancelClick',
        'modal-close' => 'clearInputs',
    ];

    /**
     * Validation rules
     *
     * @return array
     */
    protected function rules()
    {
        return [
            'number' => [
                'required',
                'numeric',
                'digits_between:1,4',
                Rule::exists('bracelets', 'number')->where(function ($query) {
                    return $query->where('status', 'system');
                }),
            ],
            'name' => 'nullable|string|max:255',
        ];
    }

    /**
     * Validation attribute names
     *
     * @return array
     */
    protected function validationAttributes()
    {
        return [
            'number' => 'bracelet number',
            'name' => 'bracelet name',
        ];
    }

    /**
     * Validation messages
     *
     * @return array
     */
    protected function messages()
    {
        return [
            '*.required' => 'The :attribute field is required.',
            '*.numeric' => 'The :attribute must be a number.',
            '*.digits_between' => 'The :attribute must be between :min and :max digits.',
            '*.exists' => 'The :attribute does not exist or has already been registered.',
        ];
    }

    /**
     * Show the modal.
     */
    public function showLinkBraceletModal()
    {
        $this->showModal = true;
    }

    /**
     * Clear inputs
     */
    public function clearInputs()
    {
        $this->number = null;
        $this->name = null;
        $this->resetErrorBag();
    }

    /**
     * Cancel button click.
     */
    public function cancelClick()
    {
        $this->showModal = false;
        $this->clearInputs();
    }

    /**
     * Link the bracelet to the order.
     */
    public function linkBracelet()
    {
        if (!$this->order) {
            return;
        }

        $this->number = sprintf('%04d', $this->number);
        $data = $this->validate();

        // retrieve the bracelet
        $bracelet = Bracelet::where('number', $data['number'])->first();
        $bracelet->update([
            'status' => 'registered',
            'name' => $data['name'],
        ]);

        // link the bracelet to the order
        $this->order->bracelets()->save($bracelet);

        // hide the modal
        $this->cancelClick();

        // Emit the event to refresh the order
        $this->emit('braceletLinked', $this->order->id);
    }

    /**
     * Mount the component.
     */
    public function mount(Order $order)
    {
        $this->order = $order;
    }

    public function render()
    {
        return view('livewire.order.link-bracelet-modal');
    }
}
