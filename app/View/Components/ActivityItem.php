<?php

namespace App\View\Components;

use Illuminate\View\Component;

class ActivityItem extends Component
{
    /**
     * The activity to display.
     *
     * @var mixed
     */
    public $activity;

    /**
     * The index of the activity (for animation delay).
     *
     * @var int
     */
    public $index;

    /**
     * Create a new component instance.
     *
     * @param  mixed  $activity
     * @param  int  $index
     * @return void
     */
    public function __construct($activity, $index = 0)
    {
        $this->activity = $activity;
        $this->index = $index;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.activity-item');
    }
}
