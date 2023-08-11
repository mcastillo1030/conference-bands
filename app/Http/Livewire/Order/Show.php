<?php

namespace App\Http\Livewire\Order;

use App\Models\Order;
use App\Providers\ConfirmationResend;
use Livewire\Component;

class Show extends Component
{
    /**
     * The route parameter's value.
     *
     * @var Order
     */
    public Order $order;

    /**
     * Temporary bracelet id to unlink.
     *
     * @var string
     */
    public string $braceletId;

    /**
     * Flag to confirm if the user wants to remove the bracelet.
     *
     * @var bool
     */
    public $confirmingBraceletUnlink = false;

    /**
     * Flag to confirm if the user wants to delete the order.
     *
     * @var bool
     */
    public $confirmingOrderDeletion = false;

    /**
     * Event listeners
     */
    protected $listeners = [
        'unlinkInterstitial',
        'resendConfirmation' => 'emitConfirmation',
        'braceletLinked' => '$refresh',
        'braceletUnlinked' => '$refresh',
    ];

    /**
     * Unlink bracelet interstitial
     *
     * @param int $braceletId
     */
    public function unlinkInterstitial($braceletId)
    {
        $this->confirmingBraceletUnlink = true;
        $this->braceletId = $braceletId;
    }

    /**
     * Emit confirmation email evetn
     *
     * @param int $braceletId
     */
    public function emitConfirmation()
    {
        ConfirmationResend::dispatch($this->order);
        $this->order->refresh();
        // $this->emit('refreshComponent');
    }

    /**
     * Unlink a bracelet from the order.
     *
     * @param int $braceletId
     */
    public function confirmUnlinkBracelet()
    {
        if (!$this->braceletId) {
            return;
        }

        /**
         * Remove the order reference from the bracelet model,
         *  reset the name & status, and save.
         */
        $bracelet = $this->order->bracelets()->find($this->braceletId);
        $bracelet->order_id = null;
        $bracelet->name = null;
        $bracelet->status = 'system';
        $bracelet->save();

        // Remove the bracelet from the order using the HasMany relationship
        $this->order->refresh();

        $this->confirmingBraceletUnlink = false;
    }

    public function deleteOrder()
    {
        // Unlink all bracelets from the order
        $this->order->bracelets()->each(function ($bracelet) {
            $bracelet->order_id = null;
            $bracelet->name = null;
            $bracelet->status = 'system';
            $bracelet->save();
        });

        $this->order->refresh()->delete();
        $this->confirmingOrderDeletion = false;
        $this->emit('orderDeleted');
        return redirect()->route('orders.index');
    }

    public function render()
    {
        return view('livewire.order.show')
            ->layout('layouts.app', ['header' => 'Order Details' ]);
    }
}
